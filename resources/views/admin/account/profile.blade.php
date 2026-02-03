@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <legend>User Information</legend>
                        <table class="table table-style">

                            @if($admin_user->photo)
                                <tr>
                                    <th style="width: 40%">Image</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <img src="{{ asset('uploads/admin/' . $admin_user->photo) }} " class="img-circle bg-success" style="height: 120px; widht: 120px" alt="Admin Photo">
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $admin_user->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Email </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $admin_user->email }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $admin_user->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                {{--<div class="col-md-6">--}}
                    {{--<fieldset>--}}
                        {{--<legend>Branch Information</legend>--}}
                        {{--<table class="table table-style">--}}
                            {{--<tr>--}}
                                {{--<th style="width: 40%"> Name</th>--}}
                                {{--<td style="width: 10%"> : </td>--}}
                                {{--<td style="width: 50%"> {{ $rider->branch->name }} </td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<th style="width: 40%">Contact Number </th>--}}
                                {{--<td style="width: 10%"> : </td>--}}
                                {{--<td style="width: 50%"> {{ $rider->branch->contact_number }} </td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<th style="width: 40%"> Address </th>--}}
                                {{--<td style="width: 10%"> : </td>--}}
                                {{--<td style="width: 50%"> {{ $rider->branch->address }} </td>--}}
                            {{--</tr>--}}
                        {{--</table>--}}
                    {{--</fieldset>--}}
                {{--</div>--}}
                

                 
            </div>
        </div>
    </div>
@endsection
