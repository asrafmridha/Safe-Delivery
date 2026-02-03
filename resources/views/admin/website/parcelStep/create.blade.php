@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Parcel Step</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.parcelStep.index') }}">Parcel Steps</a></li>
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
                        <h3 class="card-title">Create New Parcel Step </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.parcelStep.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return adminCreateForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="Parcel Step Title" >
                                        </div>
                                        <div class="form-group">
                                            <label for="short_details">Short Details</label>
                                            <textarea type="text" name="short_details" id="short_details" class="form-control" placeholder="Parcel Step Short Details" >{{ old('short_details') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="long_details">Long Details</label>
                                            <textarea type="text" name="long_details" id="long_details" class="form-control textarea" placeholder="Parcel Step Long Details" >{{ old('long_details') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image </label>
                                            <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_image')" required>
                                            <div id="preview_file_image" style="margin-top: 10px;"></div>
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
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
  <script>
    $(function () {
        $('.textarea').summernote({
            placeholder: 'Enter Parcel Step Long Details',
            height: 250
        })
    })

    function createForm(){

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
