@extends('shared_template')

@section('customcss')
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
@stop

@section('content')

<div class='col-md-12'>

    <form method="POST" action="">
        <div class="form-group">
            <label for="subject">Subject: </label>
            <input type="text" name="subject" placeholder="" value="" style="width: 100%;">
        </div>

        <div class="form-group">
            <textarea id="editor" name="textValue"  rows="10" cols="80" spellcheck="true">
                
            </textarea>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-block">Submit Changes</button>
                  <input type="hidden" name="_token" value="{{ Session::token() }}">
                  {{-- {{ method_field('PUT') }} --}}
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
    ClassicEditor.create( document.querySelector( '#editor' ), {
            
        } )
	    .then( editor => {
	            console.log( editor );
	    } )
	    .catch( error => {
	            console.error( error );
	    } );

    ClassicEditor.builtinPlugins.map( plugin => plugin.EssentialsPlugin )
</script>
@stop