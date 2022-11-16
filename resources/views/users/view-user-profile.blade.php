@extends('layouts.adminLTE3.index')
@section('customcss')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@endsection
@section('content')
  
@include('partials._messages')

<div class="row">
    <div class="col-md-6" style="margin-bottom: 1rem">
        <div class="card">
            <div class="card-header bg-primary"><strong class="text-white">Student Profile</strong></div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal">
                    <div class="form-group row">
                        <label for="fullName" class="col-md-4 col-form-label">User #:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label"> 
                                {{ $student->id }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fullName" class="col-md-4 col-form-label">Index No:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label"> 
                                {{ $student->indexno }}
                            </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fullName" class="col-md-4 col-form-label">Profile:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label"> 
                                @if(empty( $student->profile )) Update Needed 
                                @else
                                    @if( $student->profile == "STF") Staff Member @endif
                                    @if( $student->profile == "INT") Intern @endif
                                    @if( $student->profile == "CON") Consultant @endif
                                    @if( $student->profile == "WAE") When Actually Employed @endif
                                    @if( $student->profile == "JPO") JPO @endif
                                    @if( $student->profile == "MSU") Staff of Permanent Mission @endif
                                    @if( $student->profile == "SPOUSE") Spouse of Staff from UN or Mission @endif
                                    @if( $student->profile == "RET") Retired UN Staff Member @endif
                                    @if( $student->profile == "SERV") Staff of Service Organizations in the Palais @endif
                                    @if( $student->profile == "NGO") Staff of UN-accredited NGO's @endif
                                    @if( $student->profile == "PRESS") Staff of UN Press Corps @endif 
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="org" class="col-md-4 col-form-label">Organization:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->torgan['Org name'] }} - {{ $student->sddextr->torgan['Org Full Name'] }} @endif

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
                    
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label">Title:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty ( $student->sddextr )) Update Needed @else {{ $student->sddextr->TITLE }} @endif</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fullName" class="col-md-4 col-form-label">Full Name:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty( $student->sddextr )) Update Needed @else {{ $student->sddextr->LASTNAME }}, {{ $student->sddextr->FIRSTNAME }} @endif</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label">Email Address:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">{{ $student->email }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-md-4 col-form-label">Gender:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty ( $student->sddextr )) Update Needed 
                                @else 
                                    @if (strtoupper($student->sddextr->SEX) == "M") Male @endif
                                    @if (strtoupper($student->sddextr->SEX) == "F") Female @endif
                                    @if (strtoupper($student->sddextr->SEX) == "O") Other @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="contactNo" class="col-md-4 col-form-label">Contact Number:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->PHONE }} @endif</p>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label for="contactNo" class="col-md-4 col-form-label">Date of Birth:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->BIRTH }} @endif</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jobAppointment" class="col-md-4 col-form-label">Type of Appointment:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->CATEGORY }} @endif</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gradeLevel" class="col-md-4 col-form-label">Grade Level:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">@if(empty($student->sddextr)) Update Needed @else {{ $student->sddextr->LEVEL }}@endif</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="course" class="col-md-4 col-form-label">Last UN Language Course:</label>

                        <div class="col-md-8 font-weight-bold">
                            <p class="col-form-label">
                                @if(empty ($repos_lang))
                                None
                                @else
                                    @if(empty($repos_lang->Te_Code)) {{ $repos_lang->coursesOld->Description }} 
                                    @else {{ $repos_lang->courses->Description}}
                                    @endif 
                                - {{ $repos_lang->terms->Term_Name }} 
                                @endif
                            </p>
                        </div>
                    </div>
                    {{-- <div class="col-md-4 offset-md-4"><a href="{{ route('students.edit', $student->id) }}" class="btn btn-block btn-outline-info">Edit Profile</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>  
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-center"><strong>New User Info</strong></div>
            <div class="card-body">
                <ul  class="list-group">
            
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <label class="control-label">Attachment 1: </label>
                                @if(empty($student->newUserInt->filesId->path)) <strong>None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
                
                                {{-- <div class="form-group">
                                    <label class="control-label col-sm-12">Attach another file to replace attachment 1: </label>
                                    <input name="contractfile" type="file" class="col-md-12 form-control-static mb-1">
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <label class="control-label">Attachment 2: </label>
                                @if(empty($student->newUserInt->filesId2->path)) <strong>None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId2->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
                                
                                {{-- <div class="form-group">
                                    <label class="control-label col-sm-12">Attach another file to replace attachment 2: </label>
                                    <input name="contractfile2" type="file" class="col-md-12 form-control-static mb-1">
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </ul>
            </div>
        </div>
    </div> 

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-center"><strong>New User Info</strong></div>
            <div class="card-body">
                <ul  class="list-group">
            
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <label class="control-label">Attachment 1: </label>
                                @if(empty($student->newUserInt->filesId->path)) <strong>None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
                
                                {{-- <div class="form-group">
                                    <label class="control-label col-sm-12">Attach another file to replace attachment 1: </label>
                                    <input name="contractfile" type="file" class="col-md-12 form-control-static mb-1">
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <label class="control-label">Attachment 2: </label>
                                @if(empty($student->newUserInt->filesId2->path)) <strong>None</strong> @else <a href="{{ Storage::url($student->newUserInt->filesId2->path) }}" target="_blank"><i class="fa fa-file fa-3x" aria-hidden="true"></i></a> @endif
                                
                                {{-- <div class="form-group">
                                    <label class="control-label col-sm-12">Attach another file to replace attachment 2: </label>
                                    <input name="contractfile2" type="file" class="col-md-12 form-control-static mb-1">
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </ul>
            </div>
        </div>
    </div> 
</div>
@endsection

@section('java_script')

@endsection
