@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Tavs aktivitāšu grafiks
    </h1>

    <div id="calendar"></div>

    <!-- Modal for participation confirmation -->
    <div id="participationModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4" id="modalTitle"></h2>
            <p class="mb-4" id="modalDescription"></p>
            <div class="flex justify-between">
                <button id="participateBtn" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">
                    Piedalīties
                </button>
                <button id="notParticipateBtn" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                    Nepiedalīties
                </button>
            </div>
            <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-600">X</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {!! json_encode($practices->map(function($practice) {
                    return [
                        'title' => $practice->title,
                        'start' => $practice->scheduled_at,
                        'description' => $practice->description,
                        'id' => $practice->id,
                        'backgroundColor' => $practice->type === 'spele' ? '#ef4444' : '#3b82f6',
                        'textColor' => '#ffffff'
                    ];
                })) !!},
                eventClick: function(info) {
                    var practiceId = info.event.id;
                    var userId = {{ Auth::id() }}; // Spēlētāja ID

                    // Show modal to confirm participation
                    showModal(practiceId, userId);
                }
            });

            calendar.render();

            // Show modal function
            function showModal(practiceId, userId) {
                var modal = document.getElementById('participationModal');
                var modalTitle = document.getElementById('modalTitle');
                var modalDescription = document.getElementById('modalDescription');
                var participateBtn = document.getElementById('participateBtn');
                var notParticipateBtn = document.getElementById('notParticipateBtn');

                // Fetch current participation status from the server
                fetch(`/participate/${practiceId}/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        let participationStatus = data.status;
                        let actionText = '';

                        if (participationStatus === 'būs') {
                            actionText = 'Vai vēlaties atteikties no piedalīšanās?';
                            participateBtn.innerText = 'Nepiedalīties';
                        } else {
                            actionText = 'Vai vēlaties piedalīties šajā aktivitātē?';
                            participateBtn.innerText = 'Piedalīties';
                        }

                        modalTitle.innerText = data.title;
                        modalDescription.innerText = actionText;

                        // Show the modal
                        modal.classList.remove('hidden');

                        // Handle participant decision
                        participateBtn.onclick = function() {
                            updateParticipation(practiceId, userId, participationStatus === 'būs' ? 'nebus' : 'būs');
                            modal.classList.add('hidden');
                        };

                        notParticipateBtn.onclick = function() {
                            updateParticipation(practiceId, userId, 'nebus');
                            modal.classList.add('hidden');
                        };

                        // Close modal
                        document.getElementById('closeModalBtn').onclick = function() {
                            modal.classList.add('hidden');
                        };
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Function to update participation status
            function updateParticipation(practiceId, userId, status) {
                fetch(`/participate/${practiceId}/${userId}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
@endsection
