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
                            <button class="btn btn-primary mb-3 mr-3 float-right" type="button" id="printBtn">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <table class="table table-style table-bordered">
                                <tr>
                                    <th rowspan="2" style="width: 5%"> #</th>
                                    
                                    <th rowspan="2" style="width: 8%">Merchant ID</th>
                                    <th rowspan="2" style="width: 15%"> Name</th>
                                    <th rowspan="2" style="width: 15%"> Company</th>
                                    <td rowspan="2" style="width: 20%"> Address</td>
                                    <td rowspan="2" style="width: 10%"> Contact Number</td>
                                    <td rowspan="2" class="text-center" style="width: 10%"> COD Charge</td>
                                    <td class="text-center" colspan="3" style="width: 30%;"> Service Charge</td>
                                </tr>
                                <tr>
                                    @foreach ($serviceAreas as $serviceArea)
                                        <td class="text-center" style="width: 10%"> {{ $serviceArea->name }} </td>
                                    @endforeach
                                </tr>
                                @foreach($branchUser->branch->merchants as $merchant)
                                    <tr>
                                        <th>{{ $loop->iteration }} </th>
                                        <th>{{ $merchant->m_id }} </th>
                                        
                                        <th>{{ $merchant->name }} </th>
                                        <th>{{ $merchant->company_name }} </th>
                                        <td>{{ $merchant->address }} </td>
                                        <td>{{ $merchant->contact_number }} </td>
                                        <td class="text-center">{{ $merchant->cod_charge ?? 0  }} %</td>

                                        @foreach ($serviceAreas as $serviceArea)
                                            @php
                                                $merchantServiceAreaCharge = $serviceArea->default_charge;
                                                foreach($merchant->service_area_charges as $service_area_charge){
                                                    if($service_area_charge->id == $serviceArea->id){
                                                        $merchantServiceAreaCharge                 = $service_area_charge->pivot->charge;
                                                    }
                                                }
                                            @endphp
                                            <td class="text-center"> {{ number_format($merchantServiceAreaCharge,2) }} </td>

                                        @endforeach
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

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function(){
            $(document).on('click', '#printBtn', function(){
                $.ajax({
                    type: 'GET',
                    url: '{!! route('branch.printMerchantListByBranch') !!}',
                    data: {},
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href,"_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
    </script>
@endpush

