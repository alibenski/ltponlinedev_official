<div class="modal-body">
    <p>You have chosen the following time schedule(s) for this course:</p>
        @if(!empty($schedules))
            <ul>
              @foreach($schedules as $key => $value)
                <p><span><i class="fa fa-clock-o fa-spin fa-lg" style="margin-right: 10px;" aria-hidden="true"></i></span><strong>{{ $value }}</strong></p>
              @endforeach
            </ul>
        @endif
    <p class="alert alert-warning"><strong>Note:</strong> Please note that the class schedules are not absolute and there is a possibility that they could change upon further evaluation of the Language Secretariat.</p>    
</div>
