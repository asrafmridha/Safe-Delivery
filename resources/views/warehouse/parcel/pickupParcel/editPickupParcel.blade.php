@extends('layouts.branch_layout.branch_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Pickup Parcel</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Pickup Parcel</li>
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
                        <h3 class="card-title">Edit Pickup Parcel </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <form role="form" action="{{ route('branch.parcel.confirmEditPickupParcel', $parcel->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
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
                                                                $status     = "";
                                                                switch ($parcel->status) {
                                                                    case 1:$status  = "Pickup Request"; break;
                                                                    case 2:$status  = "Parcel Hold"; break;
                                                                    case 3:$status  = "Parcel Cancel"; break;
                                                                    case 4:$status  = "Parcel Reschedule"; break;
                                                                    case 5:$status  = "Pickup Run Create"; break;
                                                                    case 6:$status  = "Pickup Run Start"; break;
                                                                    case 7:$status  = "Pickup Run Cancel"; break;
                                                                    case 8:$status  = "Pickup Rider Accept"; break;
                                                                    case 9:$status  = "Pickup Rider Reject"; break;
                                                                    case 10:$status = "Pickup Rider Complete"; break;
                                                                    case 11:$status = "Pickup Run Complete"; break;
                                                                    case 12:$status = "Delivery Branch Assign"; break;
                                                                    case 13:$status = "Delivery Branch Cancel Assign"; break;
                                                                    case 14:$status = "Delivery Branch Received"; break;
                                                                    case 15:$status = "Delivery Branch Reject"; break;
                                                                    case 16:$status = "Delivery Run Create"; break;
                                                                    case 17:$status = "Delivery Run Start"; break;
                                                                    case 18:$status = "Delivery Run Cancel"; break;
                                                                    case 19:$status = "Delivery Rider Accept"; break;
                                                                    case 20:$status = "Delivery Rider Reject"; break;
                                                                    case 21:$status = "Complete Delivery"; break;
                                                                    case 22:$status = "Partial Delivery"; break;
                                                                    case 23:$status = "Reschedule"; break;
                                                                    case 24:$status = "Parcel Return"; break;
                                                                    case 25:$status = "Delivery Run Complete"; break;
                                                                    case 26:$status = "Assign Return Branch"; break;
                                                                    case 27:$status = "Assign Return Branch Cancel"; break;
                                                                    case 28:$status = "Return Branch Received"; break;
                                                                    case 29:$status = "Return Parcel Reject"; break;
                                                                    case 30:$status = "Return Run Create"; break;
                                                                    case 31:$status = "Return Run Start"; break;
                                                                    case 32:$status = "Return Run Cancel"; break;
                                                                    case 33:$status = "Return Run Rider Accept"; break;
                                                                    case 34:$status = "Return Run Rider Reject"; break;
                                                                    case 35:$status = "Return Run Rider Complete"; break;
                                                                    case 36:$status = "Return Run Complete"; break;
                                                                    default:$status = "None"; break;
                                                                }
                                                            @endphp
                                                            {{ $status }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset>
                                                <legend>Merchant Information</legend>
                                                <table class="table ">
                                                    <tr>
                                                        <th style="width: 40%">Merchant Name</th>
                                                        <td style="width: 10%"> : </td>
                                                        <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
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
                                        </div>
                                        <div class="col-md-6">
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
                                        <div class="col-md-12">
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
                                                    <div class="col-md-4 col-sm-12">
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
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="upazila_id"> Thana/Upazila </label>
                                                            <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                                <option value="0">Select Thana/Upazila</option>
                                                                @foreach ($upazilas as $upazila)
                                                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
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
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset >
                                                <legend>Parcel Information </legend>
                                                <div class="row">
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
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="delivery_option_id"> Delivery Option </label>
                                                            <select name="delivery_option_id" id="delivery_option_id" class="form-control select2" style="width: 100%">
                                                                <option value="1">Cash On Delivery</option>
                                                                <option value="2">Bkash </option>
                                                                <option value="3">Bank </option>
                                                                <option value="4">Card </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="product_details">Product(s) Brief </label>
                                                            <input type="text" name="product_details" id="product_details" value="{{ $parcel->product_details }}" class="form-control" placeholder="Product Details " required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="total_collect_amount">Total Collection Amount</label>
                                                            <input type="number" name="total_collect_amount" id="total_collect_amount" value="{{ $parcel->total_collect_amount }}" class="form-control" placeholder="0.00" >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="parcel_note">Remark</label>
                                                            <textarea name="parcel_note" id="parcel_note" class="form-control" placeholder="Parcel Remark">{{ $parcel->parcel_note }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </div>
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
    $("#upazila_id").val({{ $parcel->upazila_id }});
    $("#area_id").val({{ $parcel->area_id }});
    $("#weight_package_id").val({{ $parcel->weight_package_id }});
    $("#delivery_option_id").val({{ $parcel->delivery_option_id }});

    window.onload = function(){
        $('#district_id').on('change', function(){
            $("#view_delivery_charge").html(0);
            $("#delivery_charge").val(0);
            $("#confirm_weight_package_charge").val(0);
            $("#confirm_merchant_service_area_charge").val(0);

            $("#upazila_id").val(0).attr('disabled', true);
            $("#weight_package_id").val(0).change().attr('disabled', true);

            var district_id   = $("#district_id option:selected").val();
            var cod_percent   = $("#confirm_cod_percent").val();
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        merchant_id : {{ $parcel->merchant_id }},
                        district_id: district_id,
                        _token : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('merchant.returnMerchantUpazilaWeightPackageOptionAndCharge') }}",
                success   : function(response){
                    if(response.success){
                        $("#upazila_id").html(response.upazilaOption).attr('disabled', false);
                        $("#weight_package_id").html(response.weightPackageOption).attr('disabled', false);
                        $("#confirm_merchant_service_area_charge").val(response.charge);
                        $("#confirm_merchant_service_area_return_charge").val(response.return_charge);

                        if(response.charge != 0){
                            $("#confirm_delivery_charge").val(response.charge);
                            $("#view_delivery_charge").html(response.charge.toFixed(2));
                        }
                        if(cod_percent == '' && response.cod_charge != 0){
                            $("#confirm_cod_percent").val(response.cod_charge);
                            $("#view_cod_percent").html(response.cod_charge+"%");
                        }
                        calculate_total_charge();
                    }
                    else{
                        toastr.error("something is wrong");
                    }
                }
            });
        });

        $('#upazila_id').on('change', function(){
            var upazila_id   = $("#upazila_id option:selected").val();
            $("#area_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                    upazila_id : upazila_id,
                    _token  : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('area.areaOption') }}",
                success   : function(response){
                    $("#area_id").html(response.option).attr('disabled', false);
                }
            })
        });

        $('#weight_package_id').on('change', function(){
            var weight_package_id       = $("#weight_package_id option:selected").val();
            var weight_package_name     = $("#weight_package_id option:selected").text();
            var charge                  = returnNumber($("#weight_package_id option:selected").attr('data-charge'));
            var merchant_service_area_charge   = returnNumber($("#confirm_merchant_service_area_charge").val());

            $("#confirm_weight_package_charge").val(charge);
            if(merchant_service_area_charge == 0){
                $("#view_delivery_charge").html(charge.toFixed(2));
                $("#confirm_delivery_charge").val(charge);
            }

            if(weight_package_id != 0){
                $("#view_weight_package").html(weight_package_name);
                $("#view_weight_package_charge").html(charge.toFixed(2));
                $("#confirm_weight_package_charge").html(charge.toFixed(2));
            } else{
                $("#view_weight_package").html("Not Confirm");
                $("#view_weight_package_charge").html("0.00");
                $("#confirm_weight_package_charge").html(0);
            }
            calculate_total_charge();
        });

        $('#total_collect_amount').keyup(function(){
            var cod_percent             = returnNumber($("#confirm_cod_percent").val());
            var total_collect_amount    = returnNumber($("#total_collect_amount").val());

            if(cod_percent == 0 && total_collect_amount == 0){
                $("#view_cod_charge").html("0.00");
                $("#confirm_cod_charge").val(0);
            }
            else{
                var cod_charge = (total_collect_amount/100) * cod_percent;
                $("#view_cod_charge").html(cod_charge.toFixed(2));
                $("#confirm_cod_charge").val(cod_charge);
            }

            calculate_total_charge();
        });
    }

    function calculate_total_charge(){
        var cod_charge              = returnNumber($("#confirm_cod_charge").val());
        var delivery_charge         = returnNumber($("#confirm_delivery_charge").val());
        var weight_package_charge   = returnNumber($("#confirm_weight_package_charge").val());


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



        let upazila_id = $('#upazila_id').val();
        if(upazila_id == '0'){
            toastr.error("Please Select Thana/Upazila..");
            return false;
        }
        let area_id = $('#area_id').val();
        if(area_id == '0'){
            toastr.error("Please Select Area..");
            return false;
        }

        let branch_id = $('#branch_id').val();
        if(branch_id == '0'){
            toastr.error("Please Select Branch..");
            return false;
        }
    }

  </script>
@endpush
