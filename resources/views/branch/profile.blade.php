@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
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
                        <legend>Branch User Information</legend>
                        <table class="table table-style">
                            @if($branchUser->image)
                            <tr>
                                <th colspan="4" class="text-center">
                                    <img src="{{ asset('uploads/branchUser/' . $branchUser->image) }} " class="img-circle bg-success" alt="Branch User Photo" style="height: 100px; widht: 140px">
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Email </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->email }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->address }} </td>
                            </tr>

                            <tr>
                                <th style="width: 40%">District</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->branch->district->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Upazila/Thana</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->branch->upazila->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Area</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $branchUser->branch->area->name }} </td>
                            </tr>
                        </table>
                    </fieldset>

                    @if($branchUser->branch->riders->count() > 0)
                        <fieldset>
                            <legend>Riders List</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 5%"> #</th>
                                    <th style="width: 15%"> ID </th>
                                    <th style="width: 25%"> Name</th>
                                    <td style="width: 30%"> Address </td>
                                    <td style="width: 25%">  Contact Number</td>
                                </tr>
                                @foreach($branchUser->branch->riders as $rider)
                                <tr>
                                    <td >{{ $loop->iteration }} </th>
                                    <td >{{ $rider->r_id }} </th>
                                    <td >{{ $rider->name }} </th>
                                    <td >{{ $rider->address }} </td>
                                    <td >{{ $rider->contact_number }} </td>
                                </tr>
                                @endforeach

                            </table>
                        </fieldset>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($branchUser->branch->merchants->count() > 0)
                        <fieldset>
                            <legend>Merchants List</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 5%"> #</th>
                                    <th style="width: 10%"> ID </th>
                                    <th style="width: 25%"> Name</th>
                                    <th style="width: 25%"> Company</th>
                                    <td style="width: 20%"> Address </td>
                                    <td style="width: 20%">  Contact Number</td>
                                </tr>
                                @foreach($branchUser->branch->merchants as $merchant)
                                <tr>
                                    <td >{{ $loop->iteration }} </th>
                                    <td >{{ $merchant->m_id }} </th>
                                    <td >{{ $merchant->name }} </th>
                                    <td >{{ $merchant->company_name }} </td>
                                    <td >{{ $merchant->address }} </td>
                                    <td >{{ $merchant->contact_number }} </td>
                                </tr>
                                @endforeach
                            </table>
                        </fieldset>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection

@push('style_css')
    <style>
        .table-style td, .table-style th {
            padding: .1rem !important;
        }
    </style>
@endpush
