@extends('layouts.branch_layout.branch_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Parcel Pickup Request </h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Parcel Pickup Request </li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('branch.parcel.confirmPickupRequestGenerate', $merchant->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <fieldset>
                                <legend>Pickup Request Information </legend>
                                    <table class="table">
                                        <tr>
                                            <th style="width: 40%">Merchant </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span>{{ $merchant->company_name }}</span>
                                                <input type="hidden" name="merchant_id" id="merchant_id" class="form-control" value="{{ $merchant->id }}">

                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Request Type </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <select name="request_type" id="request_type" class="form-control" required>
                                                    <option value="">--- Select ---</option>
                                                    <option value="1">Regular Delivery</option>
                                                    <option value="2">Express Delivery</option>
                                                </select>

                                                <div id="type_details"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Date </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Total Parcel</th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <input type="number" name="total_parcel" id="total_parcel" class="form-control" placeholder="Total Parcel" min="1" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <textarea name="note" id="note" class="form-control" placeholder="Parcel Pickup Request Note "></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="col-md-12 text-center m-4">
                                        <button type="submit" class="btn btn-success">Send Pickup Request</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                            </fieldset>
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

</style>
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <script>

    $(document).ready(function () {

        $("#request_type").on("change", function () {

            var type = $(this).val();
            var html = "";
            if(type != "") {
                if(type == 1) {

                    html += "<p>Delivery Charge 100 Tk</p>";
                    html += "<p>Delivery Time (6 Hours)</p>";
                    html += "<p>Only Inside Dhaka</p>";

                }else{
                    html += "<p>Delivery Charge 65 Tk</p>";
                    html += "<p>Delivery Time (24 Hours)</p>";
                }
            }

            $("#type_details").html(html);


        })
    });

    function createForm(){
        let date = $('#date').val();
        let total_parcel = returnNumber($('#total_parcel').val());
        if(!date){
            toastr.error("Please Enter Parcel Pickup Request Date..");
            return false;
        }
        if(total_parcel == 0){
            toastr.error("Please Enter Parcel Pickup Request..");
            return false;
        }
    }

  </script>
@endpush
