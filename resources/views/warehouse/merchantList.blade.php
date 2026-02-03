@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if($branchUser->branch->merchants->count() > 0)
                        <fieldset>
                            <legend>Merchants List</legend>
                            <table class="table table-style table-bordered">
                                <tr>
                                    <th style="width: 10%"> #</th>
                                    <th style="width: 30%"> Name</th>
                                    <td style="width: 30%"> Address </td>
                                    <td style="width: 30%">  Contact Number</td>
                                </tr>
                                @foreach($branchUser->branch->merchants as $merchant)
                                <tr>
                                    <td >{{ $loop->iteration }} </th>
                                    <td >{{ $merchant->name }} </th>
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
