@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Merchant Shop</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('merchant.shop.index') }}">Merchant Shops</a></li>
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
                            <h3 class="card-title">Edit Merchant Shop</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="offset-md-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('merchant.shop.update', $shop->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="shop_name">Shop Name <span style="font-weight: bold; color: red;">*</span></label>
                                                    <input type="text" name="shop_name" id="shop_name" value="{{ $shop->shop_name ?? old('shop_name') }}" class="form-control" placeholder="Shop Name" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="shop_address">Shop Address <span style="font-weight: bold; color: red;">*</span></label>
                                                    <textarea name="shop_address" id="shop_address" class="form-control" placeholder="Shop Address" required>{{  $shop->shop_address ??  old('shop_address') }}</textarea>
                                                </div>


                                                <div class="col-md-12">
                                                    <label for="status"> Status </label>
                                                    <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                                        <option value="1" <?php if($shop->status == 1)  echo 'selected'; ?>>Active</option>
                                                        <option value="0" <?php if($shop->status == 0)  echo 'selected'; ?>>Inactive</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-success">Update</button>
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


    function editForm() {

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
