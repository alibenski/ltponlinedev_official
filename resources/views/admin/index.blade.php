@extends('admin.admin')

@section('customcss')
<style>
	#placeholder {
    width: 500px;
    height: 300px;
	}
	#placeholder2 {
    width: 500px;
    height: 300px;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
	    $.plot($("#placeholder"), data, options);
	    $.plot($("#placeholder2"), data, options);
	});
</script>

@stop

@section('content')

<h1 class="text-danger">Admin dashboard - under construction</h1>
<div id="placeholder"></div>
<div id="placeholder2"></div>

@endsection

@section('java_script')
<script src="{{ asset('bower_components/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('bower_components/Flot/jquery.flot.pie.js') }}"></script>

<script type="text/javascript">
var data = [
    { label: "IE",  data: 19.5, color: "#4572A7"},
    { label: "Safari",  data: 4.5, color: "#80699B"},
    { label: "Firefox",  data: 36.6, color: "#AA4643"},
    { label: "Opera",  data: 2.3, color: "#3D96AE"},
    { label: "Chrome",  data: 36.3, color: "#89A54E"},
    { label: "Other",  data: 0.8, color: "#3D96AE"}
];
$(document).ready(function () {
    $.plot($("#placeholder"), data, {
         series: {
            pie: {
                show: true
            }
         },
         legend: {
            labelBoxBorderColor: "none"
         }
    });
});
</script>
<script>
	var d1 = [[0, 3], [1, 3], [2, 5], [3, 7], [4, 8], [5, 10], [6, 11], [7, 9], [8, 5], [9, 13]];
	$.plot($("#placeholder2"), [
	    {
	        data: d1,
	        bars: {
	            show: true
	        }
	    }
	]);
</script>
@endsection