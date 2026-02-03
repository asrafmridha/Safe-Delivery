@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Office</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.office.index') }}">Offices</a></li>
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
                            <h3 class="card-title">Edit Office </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.office.update', $office->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Office Name</label>
                                                <input type="text" name="name" id="name" value="{{ $office->name ?? old('name') }}" class="form-control" placeholder="Office Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="contact_number">Contact Number</label>
                                                <input type="text" name="contact_number" id="contact_number" value="{{ $office->contact_number ?? old('contact_number') }}" class="form-control" placeholder="Office Contact Number" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" id="email" value="{{ $office->email ?? old('email') }}" class="form-control" placeholder="Office Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea type="text" name="address" id="address" class="form-control" placeholder="Office Address" required>{{ $office->address ??  old('address') }}</textarea>
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
@endpush

@push('script_js')
    <script>
        function editForm() {

        }
    </script>
@endpush
