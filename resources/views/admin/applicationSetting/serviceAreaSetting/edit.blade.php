@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Service Area Setting</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.serviceAreaSetting.index') }}">Service Area Settings </a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Service Area </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.serviceAreaSetting.update', $serviceAreaSetting->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="service_area_id"> Service Area  </label>
                                                <select name="service_area_id" id="service_area_id" class="form-control select2" style="width: 100%">
                                                  <option value="0">Select Service Area</option>
                                                  @foreach ($serviceAreas as $serviceArea)
                                                    <option value="{{ $serviceArea->id }}">{{ $serviceArea->name }}</option>
                                                  @endforeach
                                                </select>
                                            </div>

                                            <div class="row text-center">
                                                @foreach ($weightPackages as $weightPackage)
                                                    @php
                                                        $checked    = "";
                                                        $disabled   = "disabled=''";
                                                        $rate   = $weightPackage->rate;
                                                        foreach($serviceAreaSetting->weight_packages as $weight_package){
                                                            // dd($addon_materials);
                                                            if($weight_package->pivot->weight_package_id == $weightPackage->id){
                                                                $checked    = "checked=''";
                                                                $disabled   = "";
                                                                $rate   = $weight_package->pivot->rate;
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="col-md-3" style="margin-bottom: 15px ">
                                                        <label for="vat">{{ $weightPackage->name }}</label> &nbsp;&nbsp;&nbsp;
                                                        <input type="checkbox" name="weight_package_id[]" value="{{ $weightPackage->id }}"
                                                            id="weight_package_id_{{ $weightPackage->id }}"
                                                            onchange="enbl_textbox('{{ $weightPackage->id }}')"
                                                            {{ $checked }}
                                                            data-bootstrap-switch data-off-color="danger"
                                                            data-on-color="success">

                                                        <input class="form-control" type="number" {{ $disabled }}
                                                            name="rate[]" step="any" value="{{ $rate }}"
                                                            id="weight_package_rate_{{ $weightPackage->id }}"
                                                            placeholder="{{ $weightPackage->name }}" />
                                                    </div>
                                                @endforeach
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
    </div>


@endsection

@push('style_css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
    $("#service_area_id").val('{{ $serviceAreaSetting->service_area_id }}');

    function editForm(){
        var service_area_id = $('#service_area_id').val();
        if(service_area_id == '0'){
            toastr.error("Please Select Service Area");
            return false;
        }
    }

    function enbl_textbox(weight_package_id){
        var check = $('#weight_package_id_'+weight_package_id+'').is(":checked");
        if(check == true){
            $("#weight_package_rate_"+weight_package_id)
            .prop({ 'disabled' : false, 'required' : true})
            .css('border', "1px solid rgb(88, 213, 119)");
        }
        else{
            $("#weight_package_rate_"+weight_package_id)
            .prop({ 'disabled' : true, 'required' : false})
            .css('border', "1px solid orange");
        }
    }
    </script>
@endpush
