<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4.3.1/main.min.css,npm/@fullcalendar/daygrid@4.3.0/main.min.css,npm/@fullcalendar/bootstrap@4.3.0/main.min.css">
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />

    <script src="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4.3.1,npm/@fullcalendar/daygrid@4.3.0,npm/@fullcalendar/bootstrap@4.3.0"></script>


    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'bootstrap' ],
            defaultView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            events: [
                {
                id: 'a',
                title: 'my event',
                start: '2020-02-10'
                }
            ]
        });

        calendar.render();
      });

    </script>
  </head>
  <body>
    <div class="container-fluid">
    <div class="row">
    <div class="col-sm m-5 p-5">
        <div id='calendar'></div>
    </div>
    </div>
    </div>

  </body>
</html>