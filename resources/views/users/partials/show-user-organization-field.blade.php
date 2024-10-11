<div class="form-group row">
    <label for="org" class="col-md-4 control-label col-form-label">Organization:</label>

    <div class="col-md-8 form-control-static font-weight-bold">
        <p  class="col-form-label">
        @if(empty($student->sddextr)) 
            Update Needed 
        @else  
            @if (empty($student->sddextr->torgan['Org name']))
                Update Needed 
            @else
                {{ $student->sddextr->torgan['Org name'] }} - {{ $student->sddextr->torgan['Org Full Name'] }}
            @endif
        @endif

        @if ($student->sddextr->DEPT === 'MSU')
            @if ($student->sddextr->countryMission)
            - {{ $student->sddextr->countryMission->ABBRV_NAME }} 
            @else 
            - (country update needed)
            @endif
        @endif

        @if ($student->sddextr->DEPT === 'NGO')
            @if ($student->sddextr->ngo_name)
            - {{ $student->sddextr->ngo_name }} 
            @else
            - (NGO name update needed)
            @endif
        @endif
        </p>
    </div>
</div>