@extends('layouts.adminLTE3.index')
@section('content')
    <form action="{{ route('post-captcha') }}" method="POST" id="contactForm">
        {{ csrf_field() }}
      <div id="html_element"></div>
      <br>
      {!! NoCaptcha::renderJs() !!}
      {!! NoCaptcha::display() !!}
      <input type="submit" value="Submit">
    </form>


    <div class="container-fluid">
    <div class="flex-row">
        <div class="col-sm-12">
            <div class="card">
                <div style="text-align: left;"><span style="text-align: left; font-size: 0.9375rem;"><span class="" style="font-size: medium;"><img src="https://moodle-clone.unog.ch/unog/draftfile.php/85307/user/draft/821748846/CLM%20images%20%283%29.png" alt="CLM banner" width="1396" height="243" class="img-fluid atto_image_button_text-bottom"><br></span></span></div>
                <div style="text-align: left;"><span style="text-align: left; font-size: 0.9375rem;"><span class="" style="font-size: medium;">&nbsp; </span><strong><span class="" style="font-size: medium;">&nbsp;</span></strong></span></div>
                <div style="text-align: left;"><span style="text-align: left; font-size: 0.9375rem;"><strong><span class="" style="font-size: medium;">&nbsp; &nbsp; &nbsp;</span></strong></span></div>
                <div style="text-align: left;"><span style="text-align: left; font-size: 0.9375rem;"><strong><span class="" style="font-size: medium;">&nbsp; &nbsp; &nbsp;Hello {firstname},&nbsp;</span></strong></span><br></div>

                <div class="d-flex">
                <div class="col-sm-6">
                    <p style="text-align: left;"><span class="" style="font-size: medium;">This platform is designed for your language courses. Y</span><span class="" style="font-size: medium;">ou will have access to your&nbsp;</span><span class="" style="font-size: medium;">course materials,&nbsp;</span><span class="" style="font-size: medium;">autonomous activities, course&nbsp;</span><span class="" style="font-size: medium;">assignments, tasks&nbsp;</span><span class="" style="font-size: medium;">and a variety of additional resources.&nbsp;</span></p>
                    <p></p>
                    <p style="text-align: left;"><span class="" style="font-size: medium;">We wish you an excellent learning experience!</span></p>
                    <p></p>
                </div>
                <div class="col-sm-6">
                    <p><img src="https://picsum.photos/id/25/100/100" alt=""></p>
                </div>
                </div>
            </div>
        </div>
    </div>
@stop
