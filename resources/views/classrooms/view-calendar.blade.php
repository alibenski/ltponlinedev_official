<link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4/main.min.css,npm/@fullcalendar/daygrid@4/main.min.css,npm/@fullcalendar/timegrid@4/main.min.css">
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

<div class="container" class="flex">
    @foreach ($rooms as $room)
    <div class="row flex-item">
        <div class="col-sm-12">
            <h3 class="text-center">{{ $room->Rl_Room }}</h3>
            <div id="{{ $room->id }}" class="calendar-room">
            </div>
        </div>
    </div>
    @endforeach
</div>
<input type="hidden" name="language" value="{{ $language }}" >
<input type="hidden" name="term" value="{{ $term }}" >
<input type="hidden" name="_token" value="{{ Session::token() }}"> 

<script src="https://cdn.jsdelivr.net/combine/npm/@fullcalendar/core@4,npm/@fullcalendar/daygrid@4,npm/@fullcalendar/timegrid@4"></script>
<script>
$(document).ready(function() {
        var L = $("input[name='language']").val();
        var term = $("input[name='term']").val();
        var token = $("input[name='_token']").val();
        
        $.ajax({
        url: '{{ route('ajax-index-calendar') }}',
        type: 'GET',
        data: {L:L, term:term},
        })
        .done(function(data) {
        console.log(data);
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

        var calendarEl = document.getElementById(roomId);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'timeGrid', 'dayGrid', ],
            aspectRatio: 1.6,
            themeSystem: 'standard',
            defaultView: 'timeGridWeek',
            weekends: false,
            minTime: "07:00:00",
            maxTime: "21:00:00",
            eventSources: [
            {
                events: myDataSet2,
                color: 'gray',     // an option!
                textColor: 'whitesmoke' // an option!
            }
            ],
            eventRender: function(event) {
                const title = $(event.el).find('.fc-title');
                console.log(event.el.style)
                title.html(
                    '<b>'+title.text()+'</b>'+
                    '<p>'+event.event._def.extendedProps.teacher+'</p>'

                );
                if (event.event._def.extendedProps.teacher === 'NA') {
                    event.el.style.backgroundColor = "#eccaca";
                    event.el.style.color = "black";
                }
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