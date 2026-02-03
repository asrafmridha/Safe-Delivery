@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Service Area</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.serviceArea.index') }}">Service Area</a></li>
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
                        <h3 class="card-title">Create New Service Area </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.serviceArea.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Service Area Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="cod_charge">COD Charge %</label>
                                            <input type="number" name="cod_charge" id="cod_charge" value="{{ old('cod_charge') }}" class="form-control" placeholder="COD Charge" step="any" min="0" >
                                        </div>
                                        <div class="form-group">
                                            <label for="default_charge">Default Charge </label>
                                            <input type="number" name="default_charge" id="default_charge" value="{{ old('default_charge') }}" class="form-control" placeholder="Default Charge" step="any" min="0" >
                                        </div>

                                        <div class="form-group">
                                            <label for="delivery_time">Delivery Time </label>
                                            <input type="text" name="delivery_time" id="delivery_time" value="{{ old('delivery_time') }}" class="form-control" placeholder="Delivery Time Ex: 24">
                                        </div>

                                        <div class="form-group">
                                            <label for="weight_type"> Weight Type </label>
                                            <select name="weight_type" id="weight_type" class="form-control select2" style="width: 100%">
                                              <option value="1" {{ old('type') == '1' ? 'selected':'' }}>KG</option>
                                              <option value="2" {{ old('type') == '2' ? 'selected':'' }}>CFT</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="details">Details</label>
                                            <textarea type="text" name="details" id="details" class="form-control" placeholder="Service Area Details" >{{ old('details') }}</textarea>
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
