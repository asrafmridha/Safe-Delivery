@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Weight Package</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.weightPackage.index') }}">Weight Packages</a></li>
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
                            <h3 class="card-title">Edit Weight Package </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.weightPackage.update', $weightPackage->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $weightPackage->name ?? old('name') }}" class="form-control" placeholder="Weight Package Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" id="title" value="{{ $weightPackage->title ?? old('title') }}" class="form-control" placeholder="Weight Package Title" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="weight_type"> Weight Type </label>
                                                <select name="weight_type" id="weight_type" class="form-control select2" style="width: 100%">
                                                  <option value="1" {{$weightPackage->weight_type == '1' ? 'selected':'' }}>KG</option>
                                                  <option value="2" {{$weightPackage->weight_type == '2' ? 'selected':'' }}>CFT</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="details">Details</label>
                                                <textarea type="text" name="details" id="details" class="form-control" placeholder="Weight Package Details" >{{ $weightPackage->details ?? old('details') }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="rate">Rate</label>
                                                <input type="number" name="rate" id="rate" value="{{ $weightPackage->rate ?? old('rate') }}" class="form-control" placeholder="Weight Package Rate" required step="any" min="0" autocomplete="off">
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

        function editForm() {

        }

    </script>
@endpush
