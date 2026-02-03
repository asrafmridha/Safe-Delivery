@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Admins</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.admin.index') }}">Admin</a></li>
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
                            <h3 class="card-title">Edit Admin </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.admin.update', $admin->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return adminEditForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Admin Name</label>
                                                <input type="text" name="name" id="name"
                                                    value="{{ old('name') ?? $admin->name }}" class="form-control"
                                                    placeholder="Enter Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_number">Phone</label>
                                                <input type="text" name="contact_number" id="contact_number"
                                                    value="{{ old('contact_number') ?? $admin->contact_number }}" class="form-control"
                                                    placeholder="Enter phone" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email address</label>
                                                <input type="email" name="email" id="email"
                                                    value="{{ old('email') ?? $admin->email }}" class="form-control"
                                                    placeholder="Enter email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password"
                                                    value="{{ old('password') }}" class="form-control"
                                                    placeholder="Password" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label for="photo">Photo </label>
                                                <input type="file" name="photo" id="photo" class="form-control"
                                                    accept="image/*" onchange="return filePreview(this)">
                                                <div id="preview_file" style="margin-top: 10px;">
                                                    @if ($admin->photo != null)
                                                        <img src="{{ asset('uploads/admin/' . $admin->photo) }}"
                                                            class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="type"> Type </label>
                                                <select name="type" id="type" class="form-control select2" style="width: 100%">
                                                    <option value="null">Select Admin Type</option>
                                                    <option value="1" {{ $admin->type == '1' ? 'selected':'' }}>Admin</option>
                                                    <option value="2" {{ $admin->type == '2' ? 'selected':'' }}>Operation</option>
                                                    <option value="3" {{ $admin->type == '3' ? 'selected':'' }}>Accounts</option>
                                                    <option value="4" {{ $admin->type == '4' ? 'selected':'' }}>CS</option>
                                                    <option value="5" {{ $admin->type == '5' ? 'selected':'' }}>Business Development</option>
                                                    <option value="6" {{ $admin->type == '6' ? 'selected':'' }}>General User</option>
                                                </select>
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
        function adminEditForm() {
            let type = $('#type').val();

            if (type == 'null') {
                toastr.error('Please Select Admin Type..');
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
