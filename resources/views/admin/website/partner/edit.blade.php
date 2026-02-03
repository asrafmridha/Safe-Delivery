@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Partners</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.partner.index') }}">Partner</a></li>
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
                            <h3 class="card-title">Edit Partner </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.partner.update', $partner->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $partner->name ?? old('name') }}" class="form-control" placeholder="Company Name" >
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this)" >
                                                <div id="preview_file" style="margin-top: 10px;">
                                                @if ($partner->image != null)
                                                    <img src="{{ asset('uploads/partner/' . $partner->image) }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="url">url</label>
                                                <input type="text" name="url" id="url" value="{{ $partner->url ?? old('url') }}" class="form-control" placeholder="Company Url" >
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
    <script>
        $("#designation_id").val('{{ $partner->designation_id}}');
        function editForm() {
            let type = $('#designation_id').val();

            if(type == '0'){
            alert('Please Select Designation..');
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
