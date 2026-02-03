@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Area</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.area.index') }}">Areas</a></li>
            <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">Create New Area </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="offset-md-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.area.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="district_id"> Districts </label>
                                            <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                              <option value="0">Select District</option>
                                              @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="upazila_id"> Thana/Upazila </label>
                                            <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                <option value="0">Select Thana/Upazila</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Area Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="post_code">Post Code</label>
                                            <input type="text" name="post_code" id="post_code" value="{{ old('post_code') }}" class="form-control" placeholder="Area Post Code" >
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Submit</button>
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
        $('#district_id').on('change', function(){
            {{--
            /* var district_id   = $("#district_id option:selected").val();
            $("#upazila_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        district_id: district_id,
                        _token : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('upazila.districtOption') }}",
                success   : function(response){
                    $("#upazila_id").html(response.option).attr('disabled', false);
                }
            }) */
            --}}
        });
    }


    function createForm(){
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }
        {{--
        // let upazila_id = $('#upazila_id').val();
        // if(upazila_id == '0'){
        //     toastr.error("Please Select Upazila..");
        //     return false;
        // }
        --}}
    }
  </script>
@endpush
