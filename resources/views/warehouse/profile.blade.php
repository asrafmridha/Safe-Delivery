@extends('layouts.warehouse_layout.warehouse_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('warehouse.home') }}">Home</a></li>
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
                        <legend>Warehouse User Information</legend>
                        <table class="table table-style">
                            @if($warehouseUser->image)
                            <tr>
                                <th colspan="4" class="text-center">
                                    <img src="{{ asset('uploads/warehouseUser/' . $warehouseUser->image) }} " class="img-circle bg-success" alt="Warehouse User Photo" style="height: 100px; width: 140px">
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $warehouseUser->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Warehouse </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $warehouseUser->warehouse->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Email </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $warehouseUser->email }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $warehouseUser->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $warehouseUser->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>

    </style>
@endpush
