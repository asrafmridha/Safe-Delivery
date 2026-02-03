@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Application</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.application.index') }}">Application</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="offset-md-1 col-md-10">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Update Application </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="offset-md-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.application.update', $application->id) }}"
                                        method="POST" enctype="multipart/form-data" onsubmit="return applicationEditForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Application Name</label>
                                                <input type="text" name="name" id="name"
                                                    value="{{ old('name') ?? $application->name }}" class="form-control"
                                                    placeholder="Enter Application Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_number">Contact Number</label>
                                                <input type="text" name="contact_number" id="contact_number"
                                                    value="{{ old('contact_number') ?? $application->contact_number }}"
                                                    class="form-control" placeholder="Enter Application Contact Number"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" id="email"
                                                    value="{{ old('email') ?? $application->email }}" class="form-control"
                                                    placeholder="Enter Application Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea name="address" id="address" class="form-control"
                                                    placeholder="Enter Address">{{ old('address') ?? $application->address }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="photo">logo </label>
                                                <input type="file" name="photo" id="photo" class="form-control"
                                                    accept="image/*">
                                                @if ($application->photo != null)
                                                    <img src="{{ $application->photo_path }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px"
                                                        alt="Application  Photo">
                                                @endif
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label for="og_image">OG Image </label>
                                                <input type="file" name="og_image" id="og_image" class="form-control"
                                                    accept="image/*">
                                                @if ($application->og_image != null)
                                                    <img src="{{ $application->og_image_path }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px"
                                                        alt="Application  Og Image">
                                                @endif
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label for="favicon">Favicon </label>
                                                <input type="file" name="favicon" id="favicon" class="form-control"
                                                    accept="image/*">
                                                @if ($application->favicon != null)
                                                    <img src="{{ $application->favicon_path }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px"
                                                        alt="Application  favicon">
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="logo">Footer Logo </label>
                                                <input type="file" name="logo" id="logo" class="form-control"
                                                    accept="image/*">
                                                @if ($application->logo != null)
                                                    <img src="{{ $application->logo_path }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px"
                                                        alt="Application  logo">
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Application Play Store Link</label>
                                                <input type="text" name="app_link" id="app_link"
                                                    value="{{ old('app_link') ?? $application->app_link }}" class="form-control"
                                                    placeholder="Enter Play Store App Link" required>
                                            </div>
                                            
                                            

                                            <div class="form-group">
                                                <label for="meta_author">Meta Author</label>
                                                <textarea name="meta_author" id="meta_author" class="form-control"
                                                    placeholder="Enter Meta Author">{{ old('meta_author') ?? $application->meta_author }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="meta_keywords">Meta Keywords</label>
                                                <textarea name="meta_keywords" id="meta_keywords" class="form-control"
                                                    placeholder="Enter Meta Keywords">{{ old('meta_keywords') ?? $application->meta_keywords }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="meta_description">Meta Description</label>
                                                <textarea name="meta_description" id="meta_description" class="form-control"
                                                    placeholder="Enter Meta Description">{{ old('meta_description') ?? $application->meta_description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="google_map">Google Map</label>
                                                <textarea name="google_map" id="google_map" class="form-control"
                                                    placeholder="Enter Google Map">{{ old('google_map') ?? $application->google_map }}</textarea>
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
    <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script>
        function applicationEditForm() {

        }

    </script>
    <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
