@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Tavs aktivitāšu grafiks
    </h1>

    <div id="calendar"></div>

    <!-- Modal for participation confirmation -->
    <div id="participationModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg max-w-sm w-full relative">
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

    <!-- FullCalendar + LV lokalizācija -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'lv',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Šodien',
                    month: 'Mēnesis',
                    week: 'Nedēļa',
                    day: 'Diena',
                    list: 'Saraksts'
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
                    const practiceId = info.event.id;
                    const userId = {{ Auth::id() }};
                    showModal(practiceId, userId);
                }
            });

            calendar.render();

            function showModal(practiceId, userId) {
                const modal = document.getElementById('participationModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalDescription = document.getElementById('modalDescription');
                const participateBtn = document.getElementById('participateBtn');
                const notParticipateBtn = document.getElementById('notParticipateBtn');

                fetch(`/participate/${practiceId}/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        let participationStatus = data.status;
                        let actionText = '';

                        if (participationStatus === 'būs') {
                            actionText = 'Tu jau esi pieteicies. Vai vēlies atteikties no piedalīšanās?';
                            participateBtn.innerText = 'Nepiedalīties';
                        } else {
                            actionText = 'Vai vēlies piedalīties šajā aktivitātē?';
                            participateBtn.innerText = 'Piedalīties';
                        }

                        modalTitle.innerText = data.title;
                        modalDescription.innerText = actionText;

                        modal.classList.remove('hidden');

                        participateBtn.onclick = function() {
                            updateParticipation(practiceId, userId, participationStatus === 'būs' ? 'nebus' : 'būs');
                            modal.classList.add('hidden');
                        };

                        notParticipateBtn.onclick = function() {
                            updateParticipation(practiceId, userId, 'nebus');
                            modal.classList.add('hidden');
                        };

                        document.getElementById('closeModalBtn').onclick = function() {
                            modal.classList.add('hidden');
                        };
                    })
                    .catch(error => console.error('Kļūda:', error));
            }

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
                    alert(data.message || 'Statuss atjaunināts');
                })
                .catch(error => console.error('Kļūda:', error));
            }
        });
    </script>
@endsection
