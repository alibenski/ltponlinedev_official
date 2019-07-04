@extends('shared_template')

@section('customcss')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>
@stop

@section('content')

<div class='col-md-12'>
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                      <h3>
                        ID # {{$writingTip->id}}
                      </h3>

                      <p>{{$writingTip->languages->name}} Writing Tip Entry</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-ios-compose"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <a href="{{ route('writing-tips.index') }}" class="btn btn-default btn-space"><i class="fa fa-arrow-left"></i> Back to Writing Tip Entries</a>
            </div>
            
        </div>
    </div>
    

    <form method="POST" action="{{ route('writing-tips.update', ['id' => $writingTip->id]) }}">

        <div class="form-group">
            <label for="subject">Subject: </label>
            <input type="text" name="subject" placeholder="@if (is_null($writingTip->subject))
                no subject @else {{$writingTip->subject}} @endif" value="" style="width: 100%;" value="" style="width: 100%;">
        </div>

        <div class="form-group">
            <textarea id="editor" name="text"  rows="10" cols="80" spellcheck="true">
                    {{$writingTip->text}}
            </textarea>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> Save Changes</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                  {{ method_field('PUT') }}
                </div>
            </div>
        </div>

    </form>

</div>

@stop

@section('java_script')
<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( 'editor' );
</script>
@stop