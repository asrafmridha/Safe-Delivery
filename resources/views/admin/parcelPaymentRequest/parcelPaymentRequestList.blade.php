
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Parcel Payment Request List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Parcel Payment Request List</li>
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
                        <h3 class="card-title"> Parcels Payment Request List </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-sm-12 col-md-3">
                                <label for="status"> Status</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                    <option value="">Select Status</option>
                                    <option value="1">Payment Request</option>
                                    <option value="2">Payment Request Accept</option>
                                    <option value="3">Payment Request Reject</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="to_date">Date</label>
                                <div class="input-group">
                                    <input type="date" name="from_date" id="from_date" class="form-control"/>
                                    <input type="date" name="to_date" id="to_date" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="yajraDatatable" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="20%" class="text-center"> Invoice</th>
                                    <th width="15%" class="text-center"> Merchant Company </th>
                                    <th width="10%" class="text-center"> Merchant Phone Number </th>
                                    <th width="10%" class="text-center"> Merchant Address </th>
                                    <th width="10%" class="text-center"> Amount </th>
                                    <th width="15%" class="text-center"> Time </th>
                                    <th width="15%" class="text-center"> Note </th>
                                    <th width="15%" class="text-center"> Status </th>
                                    <th width="15%" class="text-center"> Action </th>
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

        function load_data(status = '', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!! route('admin.parcel.getParcelPaymentRequestList') !!}',
                    data:{
                        status       : status,
                        from_date    : from_date,
                        to_date      : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'payment_request_invoice', name: 'payment_request_invoice' , class : "text-center"},
                    { data: 'merchant.company_name', name: 'merchant.company_name' , class : "text-center"},
                    { data: 'merchant.contact_number', name: 'merchant.contact_number' , class : "text-center"},
                    { data: 'merchant.address', name: 'merchant.address' , class : "text-center"},
                    { data: 'request_amount', name: 'request_amount' , class : "text-center"},
                    { data: 'date', name: 'date' , class : "text-center"},
                    { data: 'note', name: 'note' , class : "text-center"},
                    { data: 'status', name: 'status' , class : "text-center", orderable: false , searchable: false },
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $('#filter').click(function(){
            var status                  = $('#status option:selected').val();
            var from_date               = $('#from_date').val();
            var to_date                 = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(status,from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $('#yajraDatatable').DataTable().destroy();
            load_data();
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_payment_request_id = $(this).attr('parcel_payment_request_id');
            var url = "{{ route('admin.parcel.viewParcelPaymentRequest', ":parcel_payment_request_id") }}";
            url = url.replace(':parcel_payment_request_id', parcel_payment_request_id);
            $('#showResult').html('');
            if(parcel_payment_request_id.length != 0){
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

        $('#yajraDatatable').on('click', '.payment-request-accept', function(){
            if(!confirm("Are you Sure Accept Payment Request?")){
                return false;
            }
            var status_object = $(this);
            var parcel_payment_request_id   = status_object.attr('parcel_payment_request_id');
            var url         = "{{ route('admin.parcel.acceptPaymentRequestParcel') }}";

            $.ajax({
                cache       : false,
                type        : "POST",
                dataType    : "JSON",
                data        : {
                    parcel_payment_request_id    : parcel_payment_request_id,
                    _token                      : "{{ csrf_token() }}"
                },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : url,
                success   : function(response){
                    if(response.success){
                        toastr.success(response.success);
                        $('#yajraDatatable').DataTable().ajax.reload();
                    }
                    else{
                        toastr.error(response.error);
                    }
                }
            })
        });

        $('#yajraDatatable').on('click', '.payment-request-reject', function(){
            if(!confirm("Are you Sure Reject Payment Request?")){
                return false;
            }
            var status_object = $(this);
            var parcel_payment_request_id   = status_object.attr('parcel_payment_request_id');
            var url         = "{{ route('admin.parcel.rejectPaymentRequestParcel') }}";

            $.ajax({
                cache       : false,
                type        : "POST",
                dataType    : "JSON",
                data        : {
                    parcel_payment_request_id    : parcel_payment_request_id,
                    _token                      : "{{ csrf_token() }}"
                },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : url,
                success   : function(response){
                    if(response.success){
                        toastr.success(response.success);
                        $('#yajraDatatable').DataTable().ajax.reload();
                    }
                    else{
                        toastr.error(response.error);
                    }
                }
            })
        });
    }
  </script>
@endpush

