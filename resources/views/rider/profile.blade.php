@extends('layouts.rider_layout.rider_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('rider.home') }}">Home</a></li>
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
                        <legend>Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Email </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->email }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->address }} </td>
                            </tr>
                            @if($rider->image)
                            <tr>
                                <th style="width: 40%">Image</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> 
                                    <img src="{{ asset('uploads/rider/' . $rider->image) }} " class="img-circle bg-success" style="height: 120px; widht: 120px" alt="rider Photo">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">District</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->district->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Upazila/Thana</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->upazila->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Area</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->area->name }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $rider->branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                

                 
            </div>
        </div>
    </div>
@endsection
