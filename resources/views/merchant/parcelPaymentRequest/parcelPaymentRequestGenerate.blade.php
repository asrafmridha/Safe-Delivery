@extends('layouts.merchant_layout.merchant_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Parcel Payment Request </h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Parcel Payment Request </li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('merchant.parcel.confirmPaymentRequestGenerate') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <fieldset>
                                <legend>Payment Request Information </legend>
                                    <table class="table">
                                        <tr>
                                            <th style="width: 40%">Merchant </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span>{{ $merchant->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Branch Name</th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span>{{ $merchant->branch->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Branch Address</th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span>{{ $merchant->branch->address }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Branch Contact Number</th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span >{{ $merchant->branch->contact_number }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Request Amount </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <span >{{ $request_amount }}</span>
                                                <input type="hidden" name="request_amount" value="{{ $request_amount }}" readonly>
                                                <input type="hidden" name="parcel_ids" value="{{ $parcel_ids }}" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Date </th>
                                            <td style="width: 5%"> : </td>
                                            <td style="width: 55%">
                                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                                            </td>
                                        </tr>
                                        
                                        
                                        
                                        <!--<tr>-->
                                        <!--    <th style="width: 40%"> Payment Type</th>-->
                                        <!--    <td style="width: 5%"> : </td>-->
                                        <!--    <td style="width: 55%">-->
                                        <!--        <select name="request_payment_type" class="form-control" id="request_payment_type">-->
                                        <!--            <option value="">Select Payment Type</option>-->
                                        <!--            <option value="1">Cash</option>-->
                                        <!--            <option value="2">Bank</option>-->
                                        <!--            <option value="3">Bkash</option>-->
                                        <!--            <option value="4">Rocket</option>-->
                                        <!--            <option value="5">Nagad</option>-->
                                        <!--        </select>-->
                                        <!--    </td>-->
                                        <!--</tr>-->
                                        <!--<tr class="bank_info" style="display: none;">-->
                                        <!--    <td colspan="3">-->
                                        <!--        <table width="100%">-->
                                        <!--            <tr>-->
                                        <!--                <th style="width: 40%;">Bank Name</th>-->
                                        <!--                <td style="width: 5%;">:</td>-->
                                        <!--                <td style="width: 55%;">-->
                                        <!--                    <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $merchant->bank_name }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--            <tr>-->
                                        <!--                <th>Bank Account No</th>-->
                                        <!--                <td>:</td>-->
                                        <!--                <td>-->
                                        <!--                    <input type="text" name="bank_account_no" id="bank_account_no" class="form-control" value="{{ $merchant->bank_account_no }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--            <tr>-->
                                        <!--                <th>Bank Account Name</th>-->
                                        <!--                <td>:</td>-->
                                        <!--                <td>-->
                                        <!--                    <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" value="{{ $merchant->bank_account_name }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--            <tr>-->
                                        <!--                <th>Routing No</th>-->
                                        <!--                <td>:</td>-->
                                        <!--                <td>-->
                                        <!--                    <input type="text" name="routing_no" id="routing_no" class="form-control" value="{{ $merchant->routing_no }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--        </table>-->
                                        <!--    </td>-->
                                        <!--</tr>-->

                                        <!--<tr class="bkash_info" style="display: none;">-->
                                        <!--    <td colspan="3">-->
                                        <!--        <table width="100%">-->
                                        <!--            <tr>-->
                                        <!--                <th style="width: 40%;">Bkash Number</th>-->
                                        <!--                <td style="width: 5%;">:</td>-->
                                        <!--                <td style="width: 55%;">-->
                                        <!--                    <input type="text" name="bkash_number" id="bkash_number" class="form-control" value="{{ $merchant->bkash_number }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--        </table>-->
                                        <!--    </td>-->
                                        <!--</tr>-->

                                        <!--<tr class="rocket_info" style="display: none;">-->
                                        <!--    <td colspan="3">-->
                                        <!--        <table width="100%">-->
                                        <!--            <tr>-->
                                        <!--                <th style="width: 40%;">Rocket Number</th>-->
                                        <!--                <td style="width: 5%;">:</td>-->
                                        <!--                <td style="width: 55%;">-->
                                        <!--                    <input type="text" name="rocket_number" id="rocket_number" class="form-control" value="{{ $merchant->rocket_number }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--        </table>-->
                                        <!--    </td>-->
                                        <!--</tr>-->

                                        <!--<tr class="nagad_info" style="display: none;">-->
                                        <!--    <td colspan="3">-->
                                        <!--        <table width="100%">-->
                                        <!--            <tr>-->
                                        <!--                <th style="width: 40%;">Nagad Number</th>-->
                                        <!--                <td style="width: 5%;">:</td>-->
                                        <!--                <td style="width: 55%;">-->
                                        <!--                    <input type="text" name="nagad_number" id="nagad_number" class="form-control" value="{{ $merchant->nagad_number }}">-->
                                        <!--                </td>-->
                                        <!--            </tr>-->
                                        <!--        </table>-->
                                        <!--    </td>-->
                                        <!--</tr>-->
                                        
                                        
                                        

                                        <tr>
                                            <td colspan="3">
                                                <textarea name="note" id="note" class="form-control" placeholder="Parcel Payment Request Note "></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="col-md-12 text-center m-4">
                                        <button type="submit" class="btn btn-success">Send Payment Request</button>
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

      $("#request_payment_type").on("change", function () {

          var payment_type = $(this).val();
          if( payment_type == 2) {
              $(".bank_info").css("display", "table-row");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_type == 3) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "table-row");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_type == 4) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "table-row");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_type == 5) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "table-row");
          }
          else {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }

      });

    function createForm(){
        let date = $('#date').val();
        let total_parcel = returnNumber($('#total_parcel').val());
        if(!date){
            toastr.error("Please Enter Parcel Payment Request Date..");
            return false;
        }
//        if(total_parcel == 0){
//            toastr.error("Please Enter Parcel Payment Request..");
//            return false;
//        }
    }

  </script>
@endpush
