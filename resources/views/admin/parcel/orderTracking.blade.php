@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Order Tracking </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order Tracking </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content" style="margin-top: 20px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 row" >
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" name="parcel_invoice" id="parcel_invoice"
                            value="{{ $parcel_invoice }}"
                            class="form-control" placeholder="Enter Parcel Invoice Barcode"
                            onkeypress="return add_parcel(event)"
                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                            padding: 3px 0px 3px 3px;
                            margin: 5px 1px 3px 0px;
                            border: 1px solid rgb(62, 196, 118);">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="text" name="merchant_order_id" id="merchant_order_id" class="form-control" placeholder="Enter Merchant Order ID"
                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                            padding: 3px 0px 3px 3px;
                            margin: 5px 1px 3px 0px;
                            border: 1px solid rgb(62, 196, 118);">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-info btn-block" onclick="return parcelResult()">
                            Search
                        </button>
                    </div>
                </div>
                <div class="col-md-12" id="show_order_tracking_result">

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

    function parcelResult(){
        var parcel_invoice      = $("#parcel_invoice").val();
        var merchant_order_id   = $("#merchant_order_id").val();

        // alert(parcel_invoice +' '+merchant_order_id); return false;
        $.ajax({
            cache     : false,
            type      : "POST",
            data      : {
                parcel_invoice    : parcel_invoice,
                merchant_order_id : merchant_order_id,
                _token            : "{{ csrf_token() }}"
            },
            url       : "{{ route('admin.parcel.returnOrderTrackingResult') }}",
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            success   : function(response){
                $("#show_order_tracking_result").html(response);

                // $("#parcel_invoice").val('');
                // $("#merchant_order_id").val('');
                return false;
            }
        });
    }

    @if (!empty($parcel_invoice))
        parcelResult();
    @endif

  </script>
@endpush

