@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Tavs aktivitāšu grafiks
    </h1>

    <div id="calendar"></div>

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
                        'id' => $practice->id
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
