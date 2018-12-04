@extends('admin.no_sidebar_admin')

@section('content')

<div class="alert bg-black col-sm-12">
  <h4 class="text-center"><strong>Preview of {{$preview_course->courses->Description}}</strong></h4>
</div>
@include('admin.partials._termSessionMsg')

<div class="form-group">
  <a href="{{ route('preview-vsa-page-2') }}" class="btn btn-danger btn-space"><i class="fa fa-arrow-left"></i> Back to Step 2</a>
</div>


@foreach($arr_count as $key => $value)
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{$value}} Students</h3>

        <p>{{ $key }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
      
      {{-- <a href="#" class="small-box-footer">
        More info <i class="fa fa-arrow-circle-right"></i>
      </a> --}}

    </div>
  </div> 
@endforeach

<div class="row">
  <div class="col-sm-12">
    @foreach($preview as $data)
    <div class="box">
      <div class="box-header">
        {{ $data->schedules->name }}
      </div>
      <div class="box-body">
        {{$data->id}} : {{ $data->users->name }}
      </div>
    </div>
    @endforeach
  </div>
</div>

@stop

@section('java_script')

@stop