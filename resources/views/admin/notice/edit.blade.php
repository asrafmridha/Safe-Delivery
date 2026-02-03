@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Notice Or News</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.notice.index') }}">Notice Or News List</a></li>
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
                            <h3 class="card-title">Edit Notice Or News </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.notice.update', $notice->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="publish_for"> Publish For </label>
                                                <select name="publish_for" id="publish_for" class="form-control select2" style="width: 100%">
                                                    <?php
                                                        $types2 = [
                                                            0 => 'All',
                                                            1 => 'Branch',
                                                            2 => 'Merchant',
                                                        ];

                                                        foreach ($types2 as $k=>$v) {
                                                            $selected = ($notice->publish_for == $k) ? "selected" : "";
                                                            echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" name="title" id="title" value="{{ $notice->title ?? old('title') }}" class="form-control" placeholder="Notice Title" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="short_details">Details</label>
                                                <textarea name="short_details" id="short_details" rows="3" class="form-control" placeholder="Topic Details" required>{{ $notice->short_details ?? old('short_details') }}</textarea>
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

    window.onload = function(){

    }

    function editForm() {
        let title = $('#title').val();
        if(title == ''){
            toastr.error("Please Enter Title");
            return false;
        }
    }


</script>

@endpush
