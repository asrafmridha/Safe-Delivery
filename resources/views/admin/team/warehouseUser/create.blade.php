@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Warehouse User</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.warehouseUser.index') }}">Warehouse Users</a></li>
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
                        <h3 class="card-title">Create New Warehouse User</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.warehouseUser.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Warehouse Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Full Address</label>
                                            <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control" placeholder="Warehouse Address" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="warehouse_id"> Warehouse </label>
                                            <select name="warehouse_id" id="warehouse_id" class="form-control select2" style="width: 100%">
                                                <option value="0">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_number"> Contact Number </label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                  <div class="input-group-text">+88</div>
                                                </div>
                                                <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" class="form-control"  placeholder="Branch Contact Number" required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="text" name="password" id="password" value="{{ old('password') }}" class="form-control" placeholder="Password" >
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image </label>
                                            <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this)" >
                                            <div id="preview_file" style="margin-top: 10px;"></div>
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

    }


    function createForm(){
        let warehouse_id = $('#warehouse_id').val();
        if(warehouse_id == '0'){
            toastr.error("Please Select Warehouse..");
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
