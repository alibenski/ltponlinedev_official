@extends('admin.no_sidebar_admin')

@section('customcss')
<script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>
@stop

@section('content')

<div class='col-md-12'>

    <form method="POST" action="{{ route('store-enrolment-is-open-text') }}">
        <textarea name="text-value" id="editor1" rows="10" cols="80" spellcheck="true">
            {{$text->text}}
        </textarea>
        <script>
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace( 'editor1' );
        </script>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                  <button type="submit" class="btn btn-success btn-block">Submit Changes</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                  {{ method_field('PUT') }}
            </div>
        </div>
    </form> 

</div>

@stop

@section('java_script')

@stop