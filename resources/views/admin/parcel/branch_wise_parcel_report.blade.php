@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Parcels List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> Parcels List </h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ url()->current() }}">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" class="form-control"
                                            value="{{ request('from_date', date('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" class="form-control"
                                            value="{{ request('to_date', date('Y-m-d')) }}">
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            Search
                                        </button>

                                        <a href="{{ url()->current() }}" class="btn btn-secondary">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Branch Name</th>
                                        <th width="10%" class="text-center"> Picked Up </th>
                                        <th width="10%" class="text-center"> Receive Parcel </th>
                                        <th width="10%" class="text-center"> Pending Parcel </th>
                                        <th width="8%" class="text-center"> Delivered </th>
                                        <th width="8%" class="text-center"> Cancel Parcel </th>
                                        <th width="8%" class="text-center"> Dispatched Parcel </th>
                                        <th width="11%" class="text-center"> Return Parcel </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $key => $branch)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $branch->name }}</td>
                                            <td>{{ $branch->picked_count }} (৳{{ $branch->picked_amount }})</td>
                                            <td>{{ $branch->received_count }} (৳{{ $branch->received_amount }})</td>
                                            <td>{{ $branch->pending_count }} (৳{{ $branch->pending_amount }})</td>
                                            <td>{{ $branch->delivered_count }} (৳{{ $branch->delivered_amount }})</td>
                                            <td>{{ $branch->canceled_count }} (৳{{ $branch->canceled_amount }})</td>
                                            <td>{{ $branch->dispatched_count }} (৳{{ $branch->dispatched_amount }})</td>
                                            <td>{{ $branch->returned_count }} (৳{{ $branch->returned_amount }})</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="showResult">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
