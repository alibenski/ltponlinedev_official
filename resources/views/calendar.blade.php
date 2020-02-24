<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4.3.1/main.min.css,npm/@fullcalendar/daygrid@4.3.0/main.min.css,npm/@fullcalendar/bootstrap@4.3.0/main.min.css,npm/@fullcalendar/timegrid@4.4.0/main.min.css">
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />
  </head>
  <body>
    <div class="container" class="flex">
      <div class="col">
        <div id="calendar-room1"></div>
      </div>
      <div class="col">
        <div id="calendar-room2"></div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4,npm/@fullcalendar/daygrid@4,npm/@fullcalendar/bootstrap@4,npm/@fullcalendar/timegrid@4"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar-room1');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          plugins: [ 'timeGrid', 'dayGrid',  ],
          defaultView: 'timeGridWeek',
          weekends: false,
          events: [
            {
              id: '13',
              title: 'Arabic Level 3',
              start: '2020-02-24',
              end: '2020-02-24',
              startTime: '8:00',
              endTime: '9:30',
              daysOfWeek: [1,3],
              startRecur: '2020-04-20', 
              endRecur: '2020-07-10', 
              teacher: 'Adel'
              
            }
          ],
          eventRender: function(info) {
            console.log(info.event.extendedProps.teacher);
          }
        });

        calendar.gotoDate( '2020-04-20' );
        calendar.render();

        var calendarEl2 = document.getElementById('calendar-room2');
        var calendar2 = new FullCalendar.Calendar(calendarEl2, {
            plugins: [ 'timeGrid', 'dayGrid',  ],
            defaultView: 'timeGridWeek',
            weekends: false,
            events: [
              {
                id: '13',
                title: 'Arabic Level 3',
                start: '2020-02-24',
                end: '2020-02-24',
                startTime: '8:00',
                endTime: '9:30',
                daysOfWeek: [1,3],
                startRecur: '2020-04-20', 
                endRecur: '2020-07-10', 
                teacher: 'Adel'
                
              }
            ],
            eventRender: function(info) {
              console.log(info.event.extendedProps.teacher);
            }
          });
  
          calendar2.gotoDate( '2020-04-20' );
          calendar2.render();
      });
    </script>
  </body>
</html>