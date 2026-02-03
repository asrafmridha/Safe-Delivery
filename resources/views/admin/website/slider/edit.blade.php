@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sliders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.slider.index') }}">Slider</a></li>
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
                            <h3 class="card-title">Edit Slider </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.slider.update', $slider->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this)" >
                                                <div id="preview_file" style="margin-top: 10px;">
                                                @if ($slider->image != null)
                                                    <img src="{{ asset('uploads/slider/' . $slider->image) }}"
                                                        class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" id="title" value="{{ $slider->title ??old('title') }}" class="form-control" placeholder="Slider Title" >
                                            </div>
                                            <div class="form-group">
                                                <label for="details">Details</label>
                                                <textarea type="text" name="details" id="details" class="form-control" placeholder="Slider Details" >{{ $slider->details ?? old('details') }}</textarea>
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


@push('script_js')
    <script>
        function editForm() {

        }

    </script>

@endpush
