@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Item Type</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.item.type') }}">Item Type</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Edit Item Type </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.item.type.edit', $itemType->id) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="title">Name</label>
                                                <input type="text" name="title" id="title"
                                                       value="{{ $itemType->title }}"
                                                       class="form-control" placeholder="Service Type Title">
                                            </div>
                                            <div class="form-group">
                                                <label for="rate">Rate</label>
                                                <input type="number" name="rate" id="rate"
                                                       value="{{ $itemType->rate }}"
                                                       class="form-control" placeholder="Rate">
                                            </div>
                                            <div class="form-group">
                                                <label for="service_area_id"> Service Area </label>
                                                <select name="service_area_id" id="service_area_id"
                                                        class="form-control select2" style="width: 100%">
                                                    <option value="">Select Service Area</option>
                                                    @if ($serviceAreas->count() > 0 )
                                                        @foreach ($serviceAreas as $serviceArea)
                                                            <option
                                                                value="{{ $serviceArea->id }}" {{$serviceArea->id == $itemType->service_area_id?'selected':''}} >{{ $serviceArea->name }}</option>
                                                        @endforeach
                                                    @endif
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
@endpush
