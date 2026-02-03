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
                                            <label for="name">Vehicle Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Vehicle Name" required>
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
                                            <label for="vehicle_road">Vehicle Road</label>
                                            <input type="text" name="vehicle_road" id="vehicle_road" value="{{ old('vehicle_road') }}" class="form-control" placeholder="Vehicle Road" required>
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
    function createForm(){

    }
</script>
@endpush
