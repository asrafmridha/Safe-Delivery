@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Merchant</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.merchant.index') }}">Merchants</a></li>
            <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">Create New Merchant </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="offset-md-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.merchant.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="company_name">Company Name <span style="font-weight: bold; color: red;">*</span></label>
                                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" class="form-control" placeholder="Company Name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="name">Name <span style="font-weight: bold; color: red;">*</span></label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Merchant Name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="address">Full Address <span style="font-weight: bold; color: red;">*</span></label>
                                                <textarea name="address" id="address" class="form-control" placeholder="Merchant Address" required>{{ old('address') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="business_address">Business Address</label>
                                                <textarea name="business_address" id="business_address" class="form-control" placeholder="Business Address" >{{ old('business_address') }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="district_id"> Districts <span style="font-weight: bold; color: red;">*</span></label>
                                                    <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                                      <option value="0">Select District</option>
                                                      @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                      @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="upazila_id"> Thana/Upazila <span style="font-weight: bold; color: red;">*</span> </label>
                                                    <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                        <option value="0">Select Thana/Upazila</option>
                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="area_id"> Area <span style="font-weight: bold; color: red;">*</span> </label>
                                                    <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                                        <option value="0">Select Area</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="branch_id"> Branch <span style="font-weight: bold; color: red;">*</span> </label>
                                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%">
                                                    <option value="0">Select Branch</option>
                                                    @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contact_number"> Contact Number <span style="font-weight: bold; color: red;">*</span> </label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                    <div class="input-group-text">+88</div>
                                                    </div>
                                                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" class="form-control"  placeholder="Merchant Contact Number" required="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email">Facebook</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                      <div class="input-group-text">http://</div>
                                                    </div>
                                                    <input type="text" class="form-control" id="fb_url" name="fb_url" value="{{ old('fb_url') }}" placeholder="Merchant Facebook Url" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email">Website</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                      <div class="input-group-text">http://</div>
                                                    </div>
                                                    <input type="text" class="form-control" id="web_url" name="web_url" value="{{ old('web_url') }}" placeholder="Merchant Website Url" >
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_image')" >
                                                <div id="preview_file_image" style="margin-top: 10px;"></div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email">Email <span style="font-weight: bold; color: red;">*</span> </label>
                                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="password">Password</label>
                                                <input type="text" name="password" id="password" value="{{ old('password') }}" class="form-control" placeholder="Password" >
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <label for="cod_charge">COD %</label>
                                                <input type="number" name="cod_charge" id="cod_charge" value="{{ old('cod_charge') }}" class="form-control" placeholder="COD %" >
                                            </div> --}}


                                            @if($serviceAreas->count() > 0)
                                                <div class="col-md-12 row" style="margin-top: 20px;">
                                                    <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                        <label for="cod_charge" > Service Area COD</label>
                                                    </div>
                                                    @foreach ($serviceAreas as $serviceArea)
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="cod_charge{{ $serviceArea->id }}">{{ $serviceArea->name }} COD  </label>
                                                                <input type="number" name="cod_charge[]" id="cod_charge{{ $serviceArea->id }}" value="0" placeholder="{{ $serviceArea->name }} COD " class="form-control" step="any" required>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($serviceAreas->count() > 0)
                                                <div class="col-md-12 row" style="margin-top: 20px;">
                                                    <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                        <label for="charge" > Service Area Delivery Charge</label>
                                                    </div>
                                                    @foreach ($serviceAreas as $serviceArea)
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="charge{{ $serviceArea->id }}">{{ $serviceArea->name }} Delivery Charge  </label>
                                                                <input type="number" name="charge[]" id="charge{{ $serviceArea->id }}" value="{{ floatval($serviceArea->default_charge) }}" placeholder="{{ $serviceArea->name }} Charge " step="any" class="form-control">
                                                                <input type="hidden" name="service_area_id[]" id="service_area_id{{ $serviceArea->id }}" value="{{ $serviceArea->id }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($serviceAreas->count() > 0)
                                                <div class="col-md-12 row" style="margin-top: 20px;">
                                                    <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                        <label for="charge" > Service Area Return Charge</label>
                                                    </div>
                                                    @foreach ($serviceAreas as $serviceArea)
                                                        <div class="col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="return_charge{{ $serviceArea->id }}">{{ $serviceArea->name }} Return Charge  </label>
                                                                <input type="number" name="return_charge[]" id="return_charge{{ $serviceArea->id }}" placeholder="{{ $serviceArea->name }} Return Charge " step="any"  class="form-control">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif


                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="bank_account_name">Bank Account Name</label>-->
                                            <!--    <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >-->
                                            <!--</div>-->
                                            
                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="bank_account_no">Bank Account Number</label>-->
                                            <!--    <input type="text" name="bank_account_no" id="bank_account_no" value="{{ old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >-->
                                            <!--</div>-->
                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="bank_name">Bank Name</label>-->
                                            <!--    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                            <!--</div>-->

                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="bkash_number">BKash Number</label>-->
                                            <!--    <input type="text" name="bkash_number" id="bkash_number" value="{{ old('bkash_number') }}" class="form-control" placeholder="BKash Number" >-->
                                            <!--</div>-->
                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="nagad_number">Nagad Number</label>-->
                                            <!--    <input type="text" name="nagad_number" id="nagad_number" value="{{ old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >-->
                                            <!--</div>-->
                                            <!--<div class="col-md-4">-->
                                            <!--    <label for="rocket_name">Rocket Number</label>-->
                                            <!--    <input type="text" name="rocket_name" id="rocket_name" value="{{ old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >-->
                                            <!--</div>-->

                                            <div class="col-md-4">
                                                <label for="nid_card"> NID Card  </label>
                                                <input type="file" name="nid_card" id="nid_card"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_nid_card')" >
                                                <div id="preview_file_nid_card" style="margin-top: 10px;"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="trade_license">Trade License  </label>
                                                <input type="file" name="trade_license" id="trade_license"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_trade_license')" >
                                                <div id="preview_file_trade_license" style="margin-top: 10px;"></div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="tin_certificate">TIN Certificate </label>
                                                <input type="file" name="tin_certificate" id="tin_certificate"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_tin_certificate')" >
                                                <div id="preview_file_tin_certificate" style="margin-top: 10px;"></div>
                                            </div>


                                        </div>
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
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

        $('#district_id').on('change', function(){
            var district_id   = $("#district_id option:selected").val();
            // $("#upazila_id").val(0).change().attr('disabled', true);
            $("#area_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        district_id: district_id,
                        _token : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                // url       : "{{ route('upazila.districtOption') }}",
                url       : "{{ route('area.districtWiseAreaOption') }}",
                success   : function(response){
                    // $("#upazila_id").html(response.option).attr('disabled', false);
                    $("#area_id").html(response.option).attr('disabled', false);
                }
            })
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
        //             },
        //         error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
        //         url       : "{{ route('area.areaOption') }}",
        //         success   : function(response){
        //             $("#area_id").html(response.option).attr('disabled', false);
        //         }
        //     })
        // });
    }

    function createForm(){
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }

        // let upazila_id = $('#upazila_id').val();
        // if(upazila_id == '0'){
        //     toastr.error("Please Select Thana/Upazila..");
        //     return false;
        // }

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

    function filePreview(input, div) {
        $('#'+div).html('');
        if (input.files && input.files[0]) {
            $('#'+div).html('<img src="{{ asset('image/image_loading.gif') }}" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            var reader = new FileReader();

            if(input.files[0].size > 3000000){
                input.value='';
                $('#'+div).html('');
            }
            else{
                reader.onload = function (e) {
                $('#'+div).html('<img src="'+e.target.result+'" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
    }

  </script>
@endpush
