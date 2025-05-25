@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Aktivitāšu grafiks
    </h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('practices.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Pievienot aktivitāti
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Calendar Container -->
    <div id="calendar"></div>

    <!-- FullCalendar.js Scripts -->
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
                    alert('Practice: ' + info.event.title + '\n' +
                          'Date: ' + info.event.start.toLocaleString() + '\n' +
                          'Details: ' + info.event.extendedProps.description);
                }
            });

            calendar.render();
        });
    </script>
@endsection
