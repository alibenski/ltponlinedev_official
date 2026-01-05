@extends('layouts.adminLTE3.index')
@section('content')
<div class="container-fluid min-vh-100">
    <div class="flex-row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Undefined Offset</h3>
                </div>
                <div class="card-body">
                    <p>Please find below the list of student(s) and class code(s) with undefined offset. 
                        Go to the profile(s) via the link provided and assign the correct course/class. After the correction, you must <strong><a href="/admin/preview-vsa-page-1">re-run the batch process</a></strong> to make sure that the correct class is assigned to the student.
                    </p>
                    <p>Count: {{ count($emptyArraySection) }}</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>cs_unique in LTP_TEVENTCur table</th>
                                    <th>Student Assigned</th>
                                    <th>Student Profile Page</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($arrayStudents as $items)
                                    @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->Code }}</td>
                                        <td>{{ $item->users->name }}</td>
                                        <td><a href="/admin/user/{{ $item->users->id }}/manage-user-enrolment-data" target="_blank">Go to Student LTP Data</a></td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>        
                </div>
            </div>
        </div>
    </div>
</div>      
@stop