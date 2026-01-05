@extends('layouts.adminLTE3.index')
@section('content')
<div class="container-fluid min-vh-100">
    <div class="flex-row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Check Students with Duplicate Forms</h3>
                </div>
                <div class="card-body">
                    <p>Please find below the list of student(s) with duplicate forms. 
                        Go to the profile(s) via the link provided and check the forms for assigned duplicate classes. In most cases, you may need to delete the duplicate form(s) manually to make sure that each student has only one form per class. After correcting the duplicates, please <strong><a href="/admin/preview-vsa-page-1">re-run the batch process</a></strong> to make sure that the correct class is assigned to the student.
                    </p>
                    <p>Count of duplicates: {{ count($duplicates) }}</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Name</th>
                                    <th>Link LTP Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($arrayUsers as $userData)
                                    @foreach ($userData as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td><a href="/admin/user/{{ $user->id }}/manage-user-enrolment-data" target="_blank">Go to Student LTP Data</a></td>
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