@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Customer Feedback</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.customerFeedback.index') }}">Customer Feedbacks</a></li>
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
                        <h3 class="card-title">Create New Customer Feedback </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.customerFeedback.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name </label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Customer Name" >
                                        </div>
                                        <div class="form-group">
                                            <label for="company">Company </label>
                                            <input type="text" name="company" id="company" value="{{ old('company') }}" class="form-control" placeholder="Customer Company" >
                                        </div>

                                        <div class="form-group">
                                            <label for="feedback">Feedback</label>
                                            <textarea type="text" name="feedback" id="feedback" class="form-control" placeholder="Customer Feedback" >{{ old('feedback') }}</textarea>
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
@endpush

@push('script_js')
  <script>
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
