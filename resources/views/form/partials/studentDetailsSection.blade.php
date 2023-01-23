<div class="form-group">
    <label for="" class="col-md-12 control-label">Name:</label>

    <div class="col-md-12 inputGroupContainer input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
        </div>
        <input  name="" class="form-control"  type="text" value="{{ Auth::user()->sddextr->FIRSTNAME }} {{ Auth::user()->sddextr->LASTNAME }}" readonly>                                    
    </div>
</div>

<div class="form-group">
    <label for="org" class="col-md-12 control-label">Organization:</label>
    
    <div class="col-md-12 inputGroupContainer input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-globe"></i></span>
        </div>
        <input  name="fakeOrg" class="form-control"  type="text" value="{{ $user->sddextr->torgan['Org name'] }} - {{ $user->sddextr->torgan['Org Full Name'] }}@if (Auth::user()->sddextr->DEPT === 'MSU') @if (Auth::user()->sddextr->countryMission)- {{ Auth::user()->sddextr->countryMission->ABBRV_NAME }} @else - (country update needed) @endif @endif @if (Auth::user()->sddextr->DEPT === 'NGO')@if (Auth::user()->sddextr->ngo_name)- {{ Auth::user()->sddextr->ngo_name }} @else - (NGO name update needed) @endif @endif" readonly> 
        <input  name="org" class="form-control"  type="hidden" value="{{ $user->sddextr->torgan['Org name'] }}" readonly>
        @if (!is_null($user->sddextr->countryMission))
        <input  name="countryMission" class="form-control"  type="hidden" value="{{ $user->sddextr->countryMission->id }}" readonly>
        @endif
        @if (!is_null($user->sddextr->ngo_name))
        <input  name="ngoName" class="form-control"  type="hidden" value="{{ $user->sddextr->ngo_name }}" readonly>
        @endif
    </div>
</div>

<div class="form-group" style="@if(is_null($repos_lang)) display: none @else  @endif ">
    <label for="name" class="col-md-12 control-label">Last/Current UN Language Course:</label>

    <div class="col-md-12 inputGroupContainer input-group">
        @if(is_null($repos_lang)) None
        @else
        @foreach( $repos_lang as $value )
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-graduation-cap"></i></span>
            </div>
            <input  name="" class="form-control"  type="text" value="@if(empty($value->Te_Code)) {{ $value->coursesOld->Description }} @else {{ $value->courses->Description}} @endif last @if(empty($value->terms->Term_Name) || is_null($value->terms->Term_Name))No record found @else {{ $value->terms->Term_Name }} (@if($value->Result == 'P') Passed @elseif($value->Result == 'F') Failed @elseif($value->Result == 'I') Incomplete @else -- @endif) @endif" readonly>                            
        @endforeach
        @endif
    </div>
</div> 