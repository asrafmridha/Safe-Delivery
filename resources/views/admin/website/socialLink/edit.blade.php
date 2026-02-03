@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Social Link</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.socialLink.index') }}">Social Link</a></li>
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
                            <h3 class="card-title">Edit Social Link </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.socialLink.update', $socialLink->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="icon"> Social Link Name  </label>
                                                <select name="icon" id="icon" class="form-control select2" style="width: 100%">
                                                    <option value="0">Select Social Link Name </option>
                                                    <option value="fab fa-facebook">Facebook</option>
                                                    <option value="fab fa-twitter">Twitter</option>
                                                    <option value="fab fa-instagram">Instagram</option>
                                                    <option value="fab fa-youtube">Youtube</option>
                                                    <option value="fab fa-linkedin">Linkedin</option>
                                                    <option value="fab fa-skype">Skype</option>
                                                    <option value="fab fa-google-plus">Google+</option>
                                                    <option value="fab fa-whatsapp">Whatsapp</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="url">Social Link  Url</label>
                                                <input type="text" name="url" id="url" value="{{ $socialLink->url ?? old('url') }}" class="form-control" placeholder="Social Link Url" >
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
        $("#icon").val("{{ $socialLink->icon }}");

        function editForm() {
            var icon = $("#icon option:selected").val();
            if(icon == 0){
                toastr.error("Please Select Social Link Name");
                return false;
            }
        }

    </script>
@endpush
