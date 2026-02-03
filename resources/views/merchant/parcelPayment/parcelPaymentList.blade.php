
@extends('layouts.merchant_layout.merchant_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Delivery Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
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
                        <h3 class="card-title"> Delivery Parcels List </h3>

                        <div class="row" style="margin-top: 40px">
                            <div class="col-md-2">
                                <label for="delivery_parcel_type">Delivery Type</label>
                                <select name="delivery_parcel_type" id="delivery_parcel_type" class="form-control select2" style="width: 100%" >
                                    <option value="0">All Delivery Type </option>
                                    <option value="1">Complete Delivery </option>
                                    <option value="2">Partial Delivery </option>
                                    <option value="3">Return Parcel </option>
                                    <option value="4">Waiting For Pickup</option>
                                    <option value="5">Waiting For Delivery </option>
                                    <option value="6">Waiting For Return Parcel </option>
                                </select>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode"
                                    style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <input type="text" name="merchant_order_id" id="merchant_order_id" class="form-control" placeholder="Enter Merchant Order ID"
                                    style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <input type="text" name="customer_contact_number" id="customer_contact_number" class="form-control" placeholder="Customer Number"
                                    style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date">Date</label>
                                <div class="input-group">
                                    <input type="date" name="from_date" id="from_date" class="form-control"/>
                                    <input type="date" name="to_date" id="to_date" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-1" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="9%" class="text-center"> Invoice</th>
                                    <th width="9%" class="text-center"> Collectable Amount</th>
                                    <th width="9%" class="text-center"> COD Percent </th>
                                    <th width="9%" class="text-center"> COD Charge </th>
                                    <th width="9%" class="text-center"> Weight Package Charge </th>
                                    <th width="9%" class="text-center"> Delivery Charge</th>
                                    <th width="9%" class="text-center"> Return Charge </th>
                                    <th width="9%" class="text-center"> Collect </th>
                                    <th width="9%" class="text-center"> Total Charge </th>
                                    <th width="9%" class="text-center"> Paid </th>
                                    <th width="5%" class="text-center"> Action </th>
                                </tr>
                            </thead>
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

@push('style_css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    window.onload = function(){
        load_data();

        function load_data(delivery_parcel_type = '', parcel_invoice = '', merchant_order_id = '', customer_contact_number='', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!! route('merchant.account.getParcelPaymentList') !!}',
                    data:{
                        delivery_parcel_type       : delivery_parcel_type,
                        parcel_invoice      : parcel_invoice,
                        merchant_order_id   : merchant_order_id,
                        customer_contact_number   : customer_contact_number,
                        from_date           : from_date,
                        to_date             : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'parcel_invoice', name: 'parcel_invoice' , class : "text-center"},
                    { data: 'total_collect_amount', name: 'total_collect_amount' , class : "text-center"},
                    { data: 'cod_percent', name: 'cod_percent' , class : "text-center"},
                    { data: 'cod_charge', name: 'cod_charge' , class : "text-center"},
                    { data: 'weight_package_charge', name: 'weight_package_charge', class : "text-center" },
                    { data: 'delivery_charge', name: 'delivery_charge', class : "text-center" },
                    { data: 'return_charge', name: 'return_charge', class : "text-center" },
                    { data: 'customer_collect_amount', name: 'customer_collect_amount', class : "text-center" },
                    { data: 'total_charge', name: 'total_charge', class : "text-center" },
                    { data: 'merchant_paid_amount', name: 'merchant_paid_amount', class : "text-center" },
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $('#filter').click(function(){
            var delivery_parcel_type           = $('#delivery_parcel_type option:selected').val();
            var parcel_invoice          = $('#parcel_invoice').val();
            var merchant_order_id       = $('#merchant_order_id').val();
            var customer_contact_number = $('#customer_contact_number').val();
            var from_date               = $('#from_date').val();
            var to_date                 = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(delivery_parcel_type, parcel_invoice, merchant_order_id,customer_contact_number, from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $('#yajraDatatable').DataTable().destroy();
            load_data();
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_id = $(this).attr('parcel_id');
            var url = "{{ route('merchant.parcel.viewParcel', ":parcel_id") }}";
            url = url.replace(':parcel_id', parcel_id);
            $('#showResult').html('');
            if(parcel_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showResult').html(response);
                    },
                })
            }
        });
    }
  </script>
@endpush

