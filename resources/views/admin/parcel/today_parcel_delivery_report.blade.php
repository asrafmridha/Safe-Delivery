@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Today Parcel For Delivery</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Today Parcel For Delivery</li>
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
                            <h3 class="card-title"> Today Parcel For Delivery </h3>
                        </div>
                        <div class="card-body">


                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Merchant Name</th>
                                        <th width="10%" class="text-center"> Pending Parcel </th>
                                        <th width="10%" class="text-center"> Collection Amount </th>
                                        <th width="10%" class="text-center"> Total Charge </th>


                                    </tr>
                                </thead>
                                <tbody>
                                      @php
                                        $total_charge=0;
                                        $total_collect_amount=0;
                                    @endphp
                                    @foreach ($todayDelivery as $key => $d)
                                    @php
                                        $total_charge+=$d->total_charge;
                                        $total_collect_amount+=$d->total_collect_amount;
                                    @endphp
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $d->merchant_name }}</td>
                                            <td>{{ $d->total_parcel }}</td>
                                            <td>{{ $d->total_collect_amount }}</td>
                                            <td>{{ $d->total_charge }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"></td>

                                        <td>{{ $total_collect_amount }}</td>
                                        <td>{{ $total_charge }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
