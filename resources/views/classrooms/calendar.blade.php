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
      <div class="row">
        <div class="col-sm-12">
          <div id="calendar-room1"></div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4,npm/@fullcalendar/daygrid@4,npm/@fullcalendar/bootstrap@4,npm/@fullcalendar/timegrid@4"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar-room1');

        $.ajax({
          url: '{{ route('ajax-index-calendar') }}',
          type: 'GET',
          data: {},
        })
        .done(function(data) {
          console.log(data.data);
          getCalendarData(data.data);
          
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });

        function getCalendarData(data) {
          let myDataSet = [];
          let x = [];
          let gotoDateString = '';
          $.each(data, function (indexInArray, valueOfElement) {
              x = {
                  'id' : indexInArray,
                  'title' : valueOfElement['title'],
                  'start' : valueOfElement['start'],
                  'end' : valueOfElement['end'],
                  'startTime' : valueOfElement['startTime'],
                  'endTime' : valueOfElement['endTime'],
                  'daysOfWeek' : valueOfElement['daysOfWeek'],
                  'startRecur' : valueOfElement['startRecur'],
                  'endRecur' : valueOfElement['endRecur'],
                  'teacher' : valueOfElement['teacher']
              }
              myDataSet.push(x);
              gotoDateString = valueOfElement['start'];
          });
          console.log(myDataSet)
          var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'timeGrid', 'dayGrid',  ],
            defaultView: 'timeGridWeek',
            weekends: false,
            eventSources: [
              {
                events: myDataSet,
                color: 'gold',     // an option!
                textColor: 'black' // an option!
              }
            ],
            eventRender: function(info) {
              console.log(info.event.extendedProps.teacher);
            }
          });

          calendar.gotoDate( gotoDateString );
          calendar.render();
        }
      });
    </script>
  </body>
</html>