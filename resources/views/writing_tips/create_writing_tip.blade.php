@extends('shared_template')

@section('customcss')
{{-- <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script> --}}
<script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>
@stop

@section('content')

<div class='col-md-12'>

    <form method="POST" action="{{ route('writing-tips.store') }}">
        <div class="form-group">
          <label for="L" class="control-label"> Language:</label>
          <div class="col-sm-12">
            @foreach ($languages as $id => $name)
            <div class="col-sm-4">
                <div class="input-group"> 
                  <span class="input-group-addon">       
                    <input type="radio" name="L" value="{{ $id }}" >                 
                  </span>
                    <label type="text" class="form-control">{{ $name }}</label>
                </div>
            </div>
            @endforeach 
          </div>
        </div>

        <div class="form-group">
            <label for="subject">Subject: </label>
            <input type="text" name="subject" placeholder="" value="" style="width: 100%;" required="required">
        </div>

        <div class="form-group">
            <textarea id="editor" name="text"  rows="10" cols="80" spellcheck="true" required="required">
                
            </textarea>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-block">Submit</button>
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
    CKEDITOR.replace( 'editor' );
</script>
@stop