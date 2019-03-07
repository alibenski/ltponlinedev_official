@extends('admin.no_sidebar_admin')

@section('customcss')
<script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>
@stop

@section('content')

<div class='col-md-12'>

    <form>
        <textarea name="editor1" id="editor1" rows="10" cols="80" spellcheck="true">
            This is my textarea to be replaced with CKEditor.
        </textarea>
        <script>
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace( 'editor1' );
        </script>
    </form> 

</div>

@stop

@section('java_script')

@stop