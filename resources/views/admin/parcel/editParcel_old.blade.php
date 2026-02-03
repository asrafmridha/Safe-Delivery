@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Parcel</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Edit Parcel</li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Edit New Parcel </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <form role="form" action="{{ route('admin.parcel.confirmEditParcel', $parcel->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                @method('patch')
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <fieldset>
                                                <legend>Parcel Information</legend>
                                                <table class="table table-style">
                                                    <tr>
                                                        <th style="width: 40%">Invoice </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Date </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%"> Current Status </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            @php
                                                               $parcelStatus   = returnParcelStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                                                                $status_name    = $parcelStatus['status_name'];
                                                                $class          = $parcelStatus['class'];
                                                            @endphp
                                                            {{ $status_name }}
                                                        </td>
                                                    </tr>
                                                </table>
                                                {{-- <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="pickup_branch_id"> Pickup Branch</label>
                                                            <select name="pickup_branch_id" id="pickup_branch_id" class="form-control select2" style="width: 100%">
                                                                <option value="0">Select Pickup Branch</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="pickup_rider_id"> Pickup Rider</label>
                                                            <select name="pickup_rider_id" id="pickup_rider_id" class="form-control select2" style="width: 100%">
                                                                <option value="0">Select Pickup Rider </option>
                                                                @if($pickupRiders->count() > 0)
                                                                @foreach ($pickupRiders as $pickupRider)
                                                                    <option value="{{ $pickupRider->id }}">{{ $pickupRider->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="delivery_branch_id"> Delivery Branch</label>
                                                            <select name="delivery_branch_id" id="delivery_branch_id" class="form-control select2" style="width: 100%">
                                                                <option value="0">Select Delivery Branch</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="delivery_rider_id"> Delivery Rider</label>
                                                            <select name="delivery_rider_id" id="delivery_rider_id" class="form-control select2" style="width: 100%">
                                                                <option value="0">Select Delivery Rider </option>
                                                                @if(!empty($deliveryRiders))
                                                                    @foreach ($deliveryRiders as $deliveryRider)
                                                                        <option value="{{ $deliveryRider->id }}">{{ $deliveryRider->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="status">Parcel Status</label>
                                                            <select name="status" id="status" class="form-control select2" style="width: 100%">
                                                                <option value="1"> Parcel Create & Send Pick Request</option>
                                                                <option value="2"> Branch Pickup Request Accept</option>
                                                                <option value="3"> Branch Assign Pickup Rider </option>
                                                                <option value="4"> Pickup Rider Request Accept </option>
                                                                <option value="5"> Pickup Rider Pickup Parcel </option>
                                                                <option value="6">Pickup Branch Received Parcel  </option>
                                                                <option value="7"> Pickup Branch Assign Delivery Branch </option>
                                                                <option value="8"> Delivery Branch Received Parcel </option>
                                                                <option value="9"> Delivery Branch Reject Parcel </option>
                                                                <option value="10"> Delivery Branch Assign Delivery Rider </option>
                                                                <option value="11"> Delivery Rider Request Accept  </option>
                                                                <option value="12">  Delivery Rider Return Delivery Branch</option>
                                                                <option value="13">Delivery Complete</option>
                                                                <option value="14"> Partial Delivery </option>
                                                                <option value="15">  Reschedule</option>
                                                                <option value="16">  Reject</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset >
                                                <legend>Customer Information </legend>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="customer_name">Customer Name </label>
                                                            <input type="text" name="customer_name" id="customer_name" value="{{ $parcel->customer_name }}" class="form-control" placeholder="Customer Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="customer_contact_number">Customer Contact Number</label>
                                                            <input type="text" name="customer_contact_number" id="customer_contact_number" value="{{ $parcel->customer_contact_number }}" class="form-control" placeholder="Customer Contact Number" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="customer_address">Customer Address</label>
                                                            <input type="text" name="customer_address" id="customer_address" value="{{ $parcel->customer_address }}" class="form-control" placeholder="Customer Address" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="district_id"> Districts </label>
                                                            <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                                            <option value="0">Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="upazila_id"> Thana/Upazila </label>
                                                            <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                                <option value="0">Select Thana/Upazila</option>
                                                                @foreach ($upazilas as $upazila)
                                                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="area_id"> Area </label>
                                                            <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                                                <option value="0">Select Area</option>
                                                                @foreach ($areas as $area)
                                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <fieldset >
                                                <legend>Parcel Information </legend>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="merchant_order_id">Merchant Order ID </label>
                                                            <input type="text" name="merchant_order_id" id="merchant_order_id" value="{{ $parcel->merchant_order_id }}" class="form-control" placeholder="Merchant Order ID" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="service_type_id"> Service Type
                                                                <code></code></label>
                                                            <select name="service_type_id" id="service_type_id"
                                                                    class="form-control select2"
                                                                    style="width: 100%">
                                                                <option value="0">Select Service Type</option>
                                                                @foreach ($serviceTypes as $serviceType)
                                                                    <option
                                                                        value="{{ $serviceType->id }}"
                                                                        data-charge="{{$serviceType->rate}}">{{ $serviceType->title ." ".$serviceType->rate}} tk extra</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="item_type_id"> Item Type
                                                                <code>*</code></label>
                                                            <select name="item_type_id" id="item_type_id"
                                                                    class="form-control select2"
                                                                    style="width: 100%">
                                                                <option value="0">Select Item Type </option>
                                                                @foreach ($itemTypes as $itemType)
                                                                    <option
                                                                        value="{{ $itemType->id }}"
                                                                        data-charge="{{$itemType->rate}}">{{ $itemType->title ." ".$itemType->rate}} tk extra</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="weight_package_id"> Weight Package </label>
                                                            <select name="weight_package_id" id="weight_package_id" class="form-control select2" style="width: 100%" >
                                                                <option value="0" data-charge="0">Select Weight Package </option>
                                                                @foreach ($weightPackages as $weightPackage)
                                                                    @php
                                                                        $rate = $weightPackage->rate;
                                                                        if(!empty($weightPackage->service_area)){
                                                                            $rate = $weightPackage->service_area->rate;
                                                                        }
                                                                    @endphp
                                                                    <option value="{{ $weightPackage->id }}"  data-charge="{{ $rate }}">{{ $weightPackage->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
<!--                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="delivery_option_id"> Delivery Option </label>
                                                            <select name="delivery_option_id" id="delivery_option_id" class="form-control select2" style="width: 100%">
                                                                <option value="1">Cash On Delivery</option>
                                                                <option value="2">Bkash </option>
                                                                <option value="3">Bank </option>
                                                                <option value="4">Card </option>
                                                            </select>
                                                        </div>
                                                    </div>-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="total_collect_amount">Total Collection Amount</label>
                                                            <input type="number" name="total_collect_amount" id="total_collect_amount" value="{{ $parcel->total_collect_amount }}" class="form-control" placeholder="0.00" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="product_value">Product Value</label>
                                                            <input type="number" name="product_value"
                                                                   id="product_value"
                                                                   value="{{ $parcel->product_value }}"
                                                                   class="form-control" placeholder="1200.00" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="product_details">Product Details</label>
                                                            <input type="text" name="product_details"
                                                                   id="product_details"
                                                                   value="{{ $parcel->product_details }}"
                                                                   class="form-control" placeholder="product details">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="parcel_note">Remark</label>
                                                            <textarea name="parcel_note" id="parcel_note"
                                                                      class="form-control"
                                                                      placeholder="Parcel Remark">{{ $parcel->parcel_note }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend>Merchant Information</legend>
                                                <table class="table ">
                                                    <tr>
                                                        <th style="width: 40%">Merchant Name</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <input type="hidden" id="merchant_id" value="{{ $parcel->merchant->id }}">
                                                            {{ $parcel->merchant->name }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Merchant Contact </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Branch </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->merchant->branch->name }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Branch Contact Number</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->merchant->branch->contact_number }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Branch Address</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->merchant->branch->address }} </td>
                                                    </tr>
                                                </table>
                                            </fieldset>

                                            <fieldset>
                                                <legend>Parcel Charge </legend>
                                                <table class="table ">
                                                    <tr>
                                                        <th style="width: 40%">Weight Package</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_weight_package"> {{ $parcel->weight_package->name }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Service Type</th>
                                                        <td style="width: 10%"> :</td>
                                                        <td style="width: 50%">
                                                            <span id="view_service_type">{{ optional($parcel->service_type)->title }} </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Item Type</th>
                                                        <td style="width: 10%"> :</td>
                                                        <td style="width: 50%">
                                                            <span id="view_item_type">{{ optional($parcel->item_type)->title }} </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Cod Percent </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_cod_percent">
                                                                {{ $parcel->cod_percent }}%
                                                            </span>
                                                            <input type="hidden" id="confirm_cod_percent" name="cod_percent" value="{{ $parcel->cod_percent }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Weight Charge </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_weight_package_charge">{{ number_format($parcel->weight_package_charge,2) }}</span>
                                                            <input type="hidden" id="confirm_weight_package_charge" name="weight_package_charge" value="{{ $parcel->weight_package_charge }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Cod Charge</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_cod_charge">{{ number_format($parcel->cod_charge,2) }}</span>
                                                            <input type="hidden" id="confirm_cod_charge" name="cod_charge" value="{{ $parcel->cod_charge }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Delivery Charge</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_delivery_charge">{{ number_format($parcel->delivery_charge,2) }}</span>
                                                            <input type="hidden" id="confirm_delivery_charge" name="delivery_charge" value="{{ $parcel->delivery_charge }}">
                                                            <input type="hidden" id="confirm_merchant_service_area_charge" name="merchant_service_area_charge" value="{{ $parcel->weight_package_charge }}">
                                                            <input type="hidden" id="confirm_merchant_service_area_return_charge" name="merchant_service_area_return_charge" value="{{ $parcel->merchant_service_area_charge }}">
                                                            <input type="hidden"
                                                                   id="only_merchant_service_area_charge"
                                                                   name="only_merchant_service_area_charge"
                                                                   value="{{ $parcel->merchant_service_area_charge }}">

                                                            <input type="hidden"
                                                                   id="item_type_charge"
                                                                   name="item_type_charge"
                                                                   value="{{ $parcel->item_type_charge }}">
                                                            <input type="hidden"
                                                                   id="service_type_charge"
                                                                   name="service_type_charge"
                                                                   value="{{ $parcel->service_type_charge }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Total Charge </th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%">
                                                            <span id="view_total_charge">{{ number_format($parcel->total_charge,2) }}</span>
                                                            <input type="hidden" id="confirm_total_charge" name="total_charge" value="{{ $parcel->total_charge }}">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
      $("#pickup_branch_id").val({{ $parcel->pickup_branch_id }});
      $("#pickup_rider_id").val({{ $parcel->pickup_rider_id }});
      $("#delivery_branch_id").val({{ $parcel->delivery_branch_id }});
      $("#delivery_rider_id").val({{ $parcel->delivery_rider_id }});
      $("#status").val({{ $parcel->status }});
      $("#district_id").val({{ $parcel->district_id }});
    //   $("#upazila_id").val({{ $parcel->upazila_id }});
      $("#area_id").val({{ $parcel->area_id }});
      $("#weight_package_id").val({{ $parcel->weight_package_id }});
      $("#delivery_option_id").val({{ $parcel->delivery_option_id }});
      $(`#service_type_id`).val(`{{ $parcel->service_type_id ?? 0 }}`).change();
      $(`#item_type_id`).val(`{{ $parcel->item_type_id ?? 0 }}`).change();

    window.onload = function(){
        $('#district_id').on('change', function () {
            $("#view_delivery_charge").html(0);
            $("#delivery_charge").val(0);
            $("#confirm_weight_package_charge").val(0);
            $("#confirm_merchant_service_area_charge").val(0);
            $("#view_service_type").html("Not Confirm");
            $("#view_item_type").html("Not Confirm");

            // $("#upazila_id").val(0).attr('disabled', true);
            $("#area_id").val(0).attr('disabled', true);
            $("#weight_package_id").val(0).change().attr('disabled', true);

            var merchant_id = $("#merchant_id").val();
            var district_id = $("#district_id option:selected").val();
            var cod_percent = $("#confirm_cod_percent").val();
            var merchant_cod_percent = $("#confirm_merchant_cod_percent").val();
            if (district_id != "" && district_id != 0) {
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        merchant_id: merchant_id,
                        district_id: district_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('merchant.returnMerchantUpazilaWeightPackageOptionAndCharge') }}",
                    success: function (response) {
                        if (response.success) {
                            // $("#upazila_id").html(response.upazilaOption).attr('disabled', false);
                            $("#area_id").html(response.areaOption).attr('disabled', false);
                            $("#weight_package_id").html(response.weightPackageOption).attr('disabled', false);
                            $("#service_type_id").html(response.serviceTypeOption).attr('disabled', false);
                            $("#item_type_id").html(response.itemTypeOption).attr('disabled', false);
                            // Delivery Charge Comes from Service Area default_charge Or Merchant Set Service Charge
                            $("#confirm_merchant_service_area_charge").val(response.charge);
                            $("#confirm_merchant_service_area_return_charge").val(response.return_charge);

                            // Delivery Charge Comes from Service Area default_charge Or Merchant Set Service Charge
                            $("#confirm_delivery_charge").val(response.charge);
                            $("#view_delivery_charge").html(returnNumber(response.charge).toFixed(2));

                            // console.log((merchant_cod_percent != "" || merchant_cod_percent != "0") && response.cod_charge != 0);

                            // if((merchant_cod_percent != "" || merchant_cod_percent != "0") && response.cod_charge != 0){
                            //     $("#confirm_cod_percent").val(merchant_cod_percent);
                            //     $("#view_cod_percent").html(merchant_cod_percent+"%");
                            // }
                            // else{
                            //     $("#confirm_cod_percent").val(response.cod_charge);
                            //     $("#view_cod_percent").html(response.cod_charge+"%");
                            // }
                            $("#confirm_cod_percent").val(response.cod_charge);
                            $("#view_cod_percent").html(response.cod_charge + "%");

                            calculate_total_charge();
                        } else {
                            toastr.error("something is wrong");
                        }
                    }
                });
            }
        });


        // $('#upazila_id').on('change', function(){
        //     var upazila_id   = $("#upazila_id option:selected").val();
        //     $("#area_id").val(0).change().attr('disabled', true);
        //     $.ajax({
        //         cache     : false,
        //         type      : "POST",
        //         dataType  : "JSON",
        //         data      : {
        //             upazila_id : upazila_id,
        //             _token  : "{{ csrf_token() }}"
        //         },
        //         error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
        //         url       : "{{ route('area.areaOption') }}",
        //         success   : function(response){
        //             $("#area_id").html(response.option).attr('disabled', false);
        //         }
        //     })
        // });

        $('#service_type_id').on('change', function () {
            var service_type_id = $("#service_type_id option:selected").val();
            var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
            var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
            var service_type_charge = returnNumber($("#service_type_id option:selected").attr('data-charge'));
            var service_type_title = $("#service_type_id option:selected").text();

            var charge = old_delivery_charge + item_type_charge + service_type_charge;
            $("#confirm_delivery_charge").val(charge);

            $("#service_type_charge").val(service_type_charge);
            $("#item_type_charge").val(item_type_charge);

            $("#view_service_type").html(service_type_title);
            if (service_type_id==0){
                $("#view_service_type").html("Not Confirm");
            }

            $("#view_delivery_charge").html(returnNumber(charge).toFixed(2));


            calculate_total_charge();
        });

        $('#item_type_id').on('change', function () {
            var item_type_id = $("#item_type_id option:selected").val();
            var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
            var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
            var service_type_charge = returnNumber($("#service_type_id option:selected").attr('data-charge'));
            var item_type_title = $("#item_type_id option:selected").text();

            var charge = old_delivery_charge + item_type_charge + service_type_charge;
            $("#confirm_delivery_charge").val(charge);
            $("#view_delivery_charge").html(returnNumber(charge).toFixed(2));

            $("#service_type_charge").val(service_type_charge);
            $("#item_type_charge").val(item_type_charge);

            $("#view_item_type").html(item_type_title);
            if (item_type_id==0){
                $("#view_item_type").html("Not Confirm");
            }

            calculate_total_charge();
        });

        $('#weight_package_id').on('change', function(){
            var weight_package_id       = $("#weight_package_id option:selected").val();
            var weight_package_name     = $("#weight_package_id option:selected").text();
            var charge                  = returnNumber($("#weight_package_id option:selected").attr('data-charge'));
            var merchant_service_area_charge   = returnNumber($("#confirm_merchant_service_area_charge").val());

            // $("#confirm_weight_package_charge").val(charge);
            // if(merchant_service_area_charge == 0){
            //     $("#view_delivery_charge").html(charge.toFixed(2));
            //     $("#confirm_delivery_charge").val(charge);
            // }

            if(weight_package_id != 0){
                $("#view_weight_package").html(weight_package_name);
                $("#view_weight_package_charge").html(charge.toFixed(2));
                $("#confirm_weight_package_charge").val(charge.toFixed(2));
            } else{
                $("#view_weight_package").html("Not Confirm");
                $("#view_weight_package_charge").html("0.00");
                $("#confirm_weight_package_charge").val(0);
            }
            calculate_total_charge();
        });

        $('#total_collect_amount').keyup(function(){
            calculate_total_charge();
        });


        $('#pickup_branch_id').on('change', function(){
            var pickup_branch_id   = $("#pickup_branch_id option:selected").val();
            $("#pickup_rider_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                    branch_id : pickup_branch_id,
                    text : "Pickup",
                    _token  : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('rider.riderOption') }}",
                success   : function(response){
                    $("#pickup_rider_id").html(response.option).attr('disabled', false);
                }
            })
        });

        $('#delivery_branch_id').on('change', function(){
            var delivery_branch_id   = $("#delivery_branch_id option:selected").val();
            $("#delivery_rider_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                    branch_id : delivery_branch_id,
                    text : "Delivery",
                    _token  : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('rider.riderOption') }}",
                success   : function(response){
                    $("#delivery_rider_id").html(response.option).attr('disabled', false);
                }
            });
        });
    }

    function calculate_total_charge(){
        var cod_percent             = returnNumber($("#confirm_cod_percent").val());
        var total_collect_amount    = returnNumber($("#total_collect_amount").val());
        $("#view_collection_amount").html(total_collect_amount.toFixed(2));


        var cod_charge          = 0;
        if(cod_percent == 0 && total_collect_amount == 0){
            $("#view_cod_charge").html("0.00");
            $("#confirm_cod_charge").val(0);
        }
        else{
            cod_charge = (total_collect_amount/100) * cod_percent;
            $("#view_cod_charge").html(cod_charge.toFixed(2));
            $("#confirm_cod_charge").val(cod_charge);
        }


        var delivery_charge         = returnNumber($("#confirm_delivery_charge").val());
        var weight_package_charge   = returnNumber($("#confirm_weight_package_charge").val());

        console.log(cod_charge, delivery_charge, weight_package_charge);

        var total_charge    = cod_charge + delivery_charge + weight_package_charge;
        $("#view_total_charge").html(total_charge.toFixed(2));
        $("#confirm_total_charge").val(total_charge);
    }

    function createForm(){
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }

        let weight_package_id = $('#weight_package_id').val();
        if(weight_package_id == '0'){
            toastr.error("Please Select Weight Package..");
            return false;
        }

        // let upazila_id = $('#upazila_id').val();
        // if(upazila_id == '0'){
        //     toastr.error("Please Select Thana/Upazila..");
        //     return false;
        // }

        // let area_id = $('#area_id').val();
        // if(area_id == '0'){
        //     toastr.error("Please Select Area..");
        //     return false;
        // }

        // let branch_id = $('#branch_id').val();
        // if(branch_id == '0'){
        //     toastr.error("Please Select Branch..");
        //     return false;
        // }

        // let status = $('#status').val();
        // let pickup_branch_id = $('#pickup_branch_id').val();
        // let pickup_rider_id = $('#pickup_rider_id').val();
        // let delivery_branch_id = $('#delivery_branch_id').val();
        // let delivery_rider_id = $('#delivery_rider_id').val();

        // if(status > 9){
        //     if(pickup_branch_id == 0){
        //         toastr.error("Please Select Pickup Branch..");
        //         return false;
        //     }
        //     if(pickup_rider_id == 0){
        //         toastr.error("Please Select Pickup Rider..");
        //         return false;
        //     }
        //     if(delivery_branch_id == 0){
        //         toastr.error("Please Select Delivery Branch..");
        //         return false;
        //     }
        //     if(status > 9 && delivery_rider_id == 0){
        //         toastr.error("Please Select Delivery Rider..");
        //         return false;
        //     }
        // }
        // else if(status > 6){
        //     if(pickup_branch_id == 0){
        //         toastr.error("Please Select Pickup Branch..");
        //         return false;
        //     }
        //     if(pickup_rider_id == 0){
        //         toastr.error("Please Select Pickup Rider..");
        //         return false;
        //     }
        //     if(delivery_branch_id == 0){
        //         toastr.error("Please Select Delivery Branch..");
        //         return false;
        //     }
        // }
        // else if(status > 2){
        //     if(pickup_branch_id == 0){
        //         toastr.error("Please Select Pickup Branch..");
        //         return false;
        //     }
        //     if(pickup_rider_id == 0){
        //         toastr.error("Please Select Pickup Rider..");
        //         return false;
        //     }
        // }
        // else{
        //     if(pickup_branch_id == 0){
        //         toastr.error("Please Select Pickup Branch..");
        //         return false;
        //     }
        // }

    }

  </script>
@endpush
