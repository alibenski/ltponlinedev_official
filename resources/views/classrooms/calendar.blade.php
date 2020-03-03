<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4.3.1/main.min.css,npm/@fullcalendar/daygrid@4.3.0/main.min.css,npm/@fullcalendar/bootstrap@4.3.0/main.min.css,npm/@fullcalendar/timegrid@4.4.0/main.min.css">
    <link href='https://use.fontawesome.com/releases/v5.0.6/css/all.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet' />

    <style>
      .flex {
        display: flex;
        justify-content: space-between;
      }
      .flex-item {
        flex: 1;
        padding: 20px;
      }
    </style>
    
  </head>
  <body>

    <div class="container" class="flex">
      @foreach ($rooms as $room)
      <div class="row flex-item">
        <div class="col-sm-12">
          <h3 class="text-center">Room {{ $room->Rl_Room }}</h3>
          <div id="{{ $room->id }}" class="calendar-room">
          </div>
        </div>
      </div>
          
      @endforeach
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4,npm/@fullcalendar/daygrid@4,npm/@fullcalendar/bootstrap@4,npm/@fullcalendar/timegrid@4"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
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
                  'id' : valueOfElement.class['id'],
                  'title' : valueOfElement.class['title'],
                  'start' : valueOfElement.class['start'],
                  'end' : valueOfElement.class['end'],
                  'startTime' : valueOfElement.class['startTime'],
                  'endTime' : valueOfElement.class['endTime'],
                  'daysOfWeek' : valueOfElement.class['daysOfWeek'],
                  'startRecur' : valueOfElement.class['startRecur'],
                  'endRecur' : valueOfElement.class['endRecur'],
                  'teacher' : valueOfElement.class['teacher'],
                  'roomMon' : valueOfElement.class['roomMon'],
                  'roomTue' : valueOfElement.class['roomTue'],
                  'roomWed' : valueOfElement.class['roomWed'],
                  'roomThu' : valueOfElement.class['roomThu'],
                  'roomFri' : valueOfElement.class['roomFri'],
              }
              myDataSet.push(x);
              gotoDateString = valueOfElement.class['start'];
          });

          var arrayCalendarEL = [];
          $('div.calendar-room').each(function () {
            arrayCalendarEL.push($(this).attr('id'));
          });

          let myDataSet2 = [];
          $.each(arrayCalendarEL, function (ind,roomId) {
            $.each(data, function (i,v) {
              if (v.room == roomId) {
                myDataSet2.push(v.class);
              }            
            })

            console.log(myDataSet2)
            var calendarEl = document.getElementById(roomId);
            var calendar = new FullCalendar.Calendar(calendarEl, {
              plugins: [ 'timeGrid', 'dayGrid',  ],
              defaultView: 'timeGridWeek',
              weekends: false,
              minTime: "07:00:00",
              maxTime: "20:00:00",
              eventSources: [
                {
                  events: myDataSet2,
                  color: 'gold',     // an option!
                  textColor: 'black' // an option!
                }
              ],
              eventRender: function(event) {
                const title = $(event.el).find('.fc-title');
                console.log(event.event._def.extendedProps.teacher)
                title.html(
                  '<b>'+title.text()+'</b>'+
                  '<p>'+event.event._def.extendedProps.teacher+'</p>'
                
                );
              }
            });

            $.each(myDataSet2, function (index,value) {
                  var ev = calendar.getEventById( value.id );
                  // ev.find('.fc-title').append('ev.id');
                  // console.log(ev)
                })  
            calendar.gotoDate( gotoDateString );
            calendar.render();
            myDataSet2 =[];
          });
        }
      });
    </script>
  </body>
</html>