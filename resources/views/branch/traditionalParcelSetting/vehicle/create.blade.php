@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Vehicle</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.vehicle.index') }}">Vehicle</a></li>
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
                        <h3 class="card-title">Create New Vheicle </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.vehicle.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        
                                        <div class="form-group">
                                            <label for="vehicle_name">Vehicle Name</label>
                                            <input type="text" name="vehicle_name" id="vehicle_name" value="{{ old('vehicle_name') }}" class="form-control" placeholder="Vehicle Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="vehicle_sl_no">Vehicle Sl No</label>
                                            <input type="text" name="vehicle_sl_no" id="vehicle_sl_no" value="{{ old('vehicle_sl_no') }}" class="form-control" placeholder="Vehicle Sl No" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="vehicle_no">Vehicle No</label>
                                            <input type="text" name="vehicle_no" id="vehicle_no" value="{{ old('vehicle_no') }}" class="form-control" placeholder="Vehicle No" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="vehicle_driver_name">Vehicle Driver Name</label>
                                            <input type="text" name="vehicle_driver_name" id="vehicle_driver_name" value="{{ old('vehicle_driver_name') }}" class="form-control" placeholder="Vehicle Driver Name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="vehicle_driver_phone"> Driver Contact Number </label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                  <div class="input-group-text">+88</div>
                                                </div>
                                                <input type="text" name="vehicle_driver_phone" id="vehicle_driver_phone" value="{{ old('vehicle_driver_phone') }}" class="form-control"  placeholder="Driver Contact Number" required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="vehicle_root">Vehicle Root</label>
                                            <input type="text" name="vehicle_root" id="vehicle_root" value="{{ old('vehicle_root') }}" class="form-control" placeholder="Vehicle Root" required>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Submit</button>
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
        {{--$('#district_id').on('change', function(){--}}
            {{--var district_id   = $("#district_id option:selected").val();--}}
            {{--$("#upazila_id").val(0).change().attr('disabled', true);--}}
            {{--$.ajax({--}}
                {{--cache     : false,--}}
                {{--type      : "POST",--}}
                {{--dataType  : "JSON",--}}
                {{--data      : {--}}
                        {{--district_id: district_id,--}}
                        {{--_token : "{{ csrf_token() }}"--}}
                    {{--},--}}
                {{--error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },--}}
                {{--url       : "{{ route('upazila.districtOption') }}",--}}
                {{--success   : function(response){--}}
                    {{--$("#upazila_id").html(response.option).attr('disabled', false);--}}
                {{--}--}}
            {{--})--}}
//        });

        {{--$('#upazila_id').on('change', function(){--}}
            {{--var upazila_id   = $("#upazila_id option:selected").val();--}}
            {{--$("#area_id").val(0).change().attr('disabled', true);--}}
            {{--$.ajax({--}}
                {{--cache     : false,--}}
                {{--type      : "POST",--}}
                {{--dataType  : "JSON",--}}
                {{--data      : {--}}
                    {{--upazila_id : upazila_id,--}}
                    {{--_token  : "{{ csrf_token() }}"--}}
                    {{--},--}}
                {{--error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },--}}
                {{--url       : "{{ route('area.areaOption') }}",--}}
                {{--success   : function(response){--}}
                    {{--$("#area_id").html(response.option).attr('disabled', false);--}}
                {{--}--}}
            {{--})--}}
        {{--});--}}
    }


    function createForm(){
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
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
    }

    function filePreview(input) {
        $('#preview_file').html('');
        if (input.files && input.files[0]) {
            $('#preview_file').html('<img src="{{ asset('image/image_loading.gif') }}" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            var reader = new FileReader();

            if(input.files[0].size > 3000000){
                input.value='';
                $('#preview_file').html('');
            }
            else{
                reader.onload = function (e) {
                $('#preview_file').html('<img src="'+e.target.result+'" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
    }
  </script>
@endpush
