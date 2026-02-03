@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Become Merchant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Become Merchant</li>
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
                            <h3 class="card-title">Become Merchant</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    @if(!empty($content_data))
                                        <form role="form" action="{{ route('admin.content.update', $content_data->id) }}"
                                            method="POST" enctype="multipart/form-data" onsubmit="return applicationEditForm()">
                                            @csrf
                                            @method('patch')

                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title"
                                                        value="{{ $content_data->title ?? old('title') }}" class="form-control"
                                                        placeholder="Enter Title" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="short_details">Short Details</label>
                                                    <textarea name="short_details" id="short_details" class="form-control"
                                                        placeholder="Enter Address">{{ $content_data->short_details ?? old('short_details') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo">Photo </label>
                                                    <input type="file" name="photo" id="photo" class="form-control"
                                                        accept="image/*">
                                                    @if ($content_data->photo != null)
                                                        <img src="{{ asset('/uploads/contents/' . $content_data->photo) }}"
                                                            class="img-fluid img-thumbnail" style="height: 100px"
                                                            alt="Content  Photo">
                                                    @endif
                                                </div>

                                            </div>

                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <button type="reset" class="btn btn-primary">Reset</button>
                                            </div>
                                        </form>
                                    @else
                                        <form role="form" action="{{ route('admin.content.store') }}"
                                              method="POST" enctype="multipart/form-data" onsubmit="return applicationEditForm()">
                                            @csrf

                                            <input type="hidden" name="content_type" value="become_merchant">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title"
                                                           value="{{ old('title') }}" class="form-control"
                                                           placeholder="Enter Title" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="short_details">Short Details</label>
                                                    <textarea name="short_details" id="short_details" class="form-control"
                                                              placeholder="Enter Short Details">{{ old('short_details') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo">Photo </label>
                                                    <input type="file" name="photo" id="photo" class="form-control"
                                                           accept="image/*">
                                                </div>

                                            </div>

                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                                <button type="reset" class="btn btn-primary">Reset</button>
                                            </div>
                                        </form>
                                    @endif
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
