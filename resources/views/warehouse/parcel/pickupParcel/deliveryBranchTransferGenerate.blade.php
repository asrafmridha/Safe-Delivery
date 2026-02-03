@extends('layouts.branch_layout.branch_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Generate Delivery Branch Transfer (Trip) </h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Generate Delivery Branch Transfer (Trip) </li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('branch.parcel.confirmDeliveryBranchTransferGenerate') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                    @csrf
                    <input type="hidden" name="total_transfer_parcel" id="total_transfer_parcel" value="0" >
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Delivery Branch Transfer</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table ">
                                            <tr>
                                                <th >Branch</th>
                                                <td colspan="2">
                                                    <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                                        <option value="0" >Select Branch </option>
                                                        @foreach ($branches as $branch)
                                                            <option
                                                                value="{{ $branch->id }}"
                                                                branchContactNumber="{{ $branch->contact_number }}"
                                                                branchAddress="{{ $branch->address }}"
                                                                > {{ $branch->name }} </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th >Date</th>
                                                <td colspan="2">
                                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control " required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Branch Name</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_branch_name">Not Confirm</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Branch Contact Number</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_branch_contact_number">Not Confirm</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Branch Address</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_branch_address">Not Confirm</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Total Transfer Parcel</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_total_transfer_parcel"> 0 </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <textarea name="note" id="note" class="form-control" placeholder="Delivery Branch Transfer (Trip) Note "></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset id="div_delivery_branch_transfer_parcel" style="display: none">
                                            <legend>Delivery Branch Transfer Parcel </legend>
                                            <div class="row">
                                                <div class="col-sm-12" id="show_delivery_branch_transfer_parcel">

                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success">Generate</button>
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12 row" style="margin-top: 20px;">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode" onkeypress="return add_parcel(event)"
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

                        <div class="col-md-12" style="margin-top: 20px;">

                                <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
                                    <thead>
                                        <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th colspan="8" class="text-left">
                                                <button type="button" id="addParcelInvoice" class="btn btn-info">Add Parcel to Transfer</button>
                                            </th>
                                        </tr>
                                        <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th width="5%" class="text-center">
                                                All <br>
                                                <input type="checkbox"  id="checkAll" >
                                            </th>
                                            <th width="10%" class="text-center">Invoice </th>
                                            <th width="10%" class="text-center">Merchant Order  </th>
                                            <th width="15%" class="text-center">Merchant Name</th>
                                            <th width="15%" class="text-center">Customer Name</th>
                                            <th width="15%" class="text-center">Customer Number</th>
                                            <th width="10%" class="text-center">District</th>
                                            <th width="15%" class="text-center">Area</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_parcel">
                                        @if($parcels->count() > 0)
                                        @foreach($parcels as $parcel)
                                            <tr style="background-color: #f4f4f4;">
                                                <td class="text-center" >
                                                    <input type="checkbox" id="checkItem"  class="parcelId"  value="{{ $parcel->id }}" >
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->parcel_invoice }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->merchant_order_id }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->merchant->name }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->customer_name }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->customer_contact_number }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->district->name }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->area->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('style_css')
<style>
    .table td, .table th {
        padding: .1rem !important;
    }
</style>
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<script>

    window.onload = function(){
        $('#branch_id').on('change', function(){
            var branch   = $("#branch_id option:selected");
            var branch_id   = branch.val();
            if(branch_id == 0 ){
                $("#view_branch_name").html('Not Confirm');
                $("#view_branch_contact_number").html('Not Confirm');
                $("#view_branch_address").html('Not Confirm');
            } else{
                $("#view_branch_name").html(branch.text());
                $("#view_branch_contact_number").html(branch.attr('branchContactNumber'));
                $("#view_branch_address").html(branch.attr('branchAddress'));
            }
        });


        $('#addParcelInvoice').on('click', function(){
            var parcel_invoices = $('.parcelId:checkbox:checked').map(function() {
                return this.value;
            }).get();
            if(parcel_invoices.length == 0){
                toastr.error("Please Select Parcel Invoice ");
                return false;
            }
            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    parcel_invoices : parcel_invoices,
                    _token  : "{{ csrf_token() }}"
                    },
                url       : "{{ route('branch.parcel.deliveryBranchTransferParcelAddCart') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_delivery_branch_transfer_parcel").html(response);
                    $("#div_delivery_branch_transfer_parcel").show();
                    $('input:checkbox').prop('checked', false);
                    return false;
                }
            });

        });

        $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
        });

    }


    setInterval(function(){
        var cart_total_item = returnNumber($("#cart_total_item").val());
        $("#view_total_transfer_parcel").html(cart_total_item);
        $("#total_transfer_parcel").val(cart_total_item);
    }, 300);

    function add_parcel(event){
        if(event.which == 13) {
            parcelResult();

            return false;
        }
    }

    function delete_parcel(itemId){
        $.ajax({
            cache    : false,
            type     : 'POST',
            data     : {
                itemId           : itemId,
                _token          : "{{ csrf_token() }}",
            },
            url      : "{{ route('branch.parcel.deliveryBranchTransferParcelDeleteCart') }}",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            success : function (response){
                $('#show_delivery_branch_transfer_parcel').html(response);
            }
        });
    }

    function parcelResult(){

        var parcel_invoice          = $("#parcel_invoice").val();
        var merchant_order_id       = $("#merchant_order_id").val();
        $.ajax({
            cache     : false,
            type      : "POST",
            data      : {
                parcel_invoice  : parcel_invoice,
                parcel_invoice          : parcel_invoice,
                merchant_order_id       : merchant_order_id,
                _token  : "{{ csrf_token() }}"
                },
            url       : "{{ route('branch.parcel.returnDeliveryBranchTransferParcel') }}",
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            success   : function(response){
                $("#show_parcel").html(response);

                $("#parcel_invoice").val('');
                $("#parcel_invoice").val('');
                $("#merchant_order_id").val('');
                return false;
            }
        });
    }


    function createForm(){
        let branch_id = $('#branch_id').val();
        if(branch_id == '0'){
            toastr.error("Please Select Branch..");
            return false;
        }

        let total_transfer_parcel = returnNumber($('#total_transfer_parcel').val());

        if(total_transfer_parcel == 0){
            toastr.error("Please Enter Delivery Branch Transfer Parcel..");
            return false;
        }
    }

</script>
@endpush
