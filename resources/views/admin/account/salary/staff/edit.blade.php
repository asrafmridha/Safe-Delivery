@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Staff</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff</a></li>
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
                            <h3 class="card-title">Edit Staff </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.staff.update', $staff->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $staff->name ?? old('name') }}" class="form-control" placeholder="Staff Name" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="area_id"> Contact Number </label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">+88</div>
                                                    </div>
                                                    <input type="text" name="phone" id="phone" value="{{ $staff->phone ??  old('phone') }}" class="form-control"  placeholder="Staff Contact Number" required="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" value="{{ $staff->email ?? old('email') }}" class="form-control" placeholder="Staff Email" >
                                            </div>

                                            <div class="form-group">
                                                <label for="designation">Designation</label>
                                                <input type="text" name="designation" id="designation" value="{{ $staff->designation ?? old('designation') }}" class="form-control" placeholder="Staff Designation" >
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Full Address</label>
                                                <input type="text" name="address" id="address" value="{{ $staff->address ?? old('address') }}" class="form-control" placeholder="Staff Address" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="branch_id">Branch</label>
                                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                                    <option value="0">Select Branch</option>
                                                    @foreach ($branches as $branch)
                                                        @php
                                                            $selected = ($staff->branch_id  == $branch->id) ? "selected" : "";
                                                        @endphp
                                                        <option value="{{ $branch->id }}" {{ $selected }}>{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="salary">Salary</label>
                                                <input type="number" step="any" name="salary" id="salary" value="{{ $staff->salary ??  old('salary') }}" class="form-control" placeholder="Email" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this)" >
                                                <div id="preview_file_image" style="margin-top: 10px;">
                                                    @if ($staff->image != null)
                                                        <img src="{{ asset('uploads/staff/' . $staff->image) }}"
                                                            class="img-fluid img-thumbnail" style="height: 100px" alt="Blog Image">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="status"> Status </label>
                                                <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
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
    $("#district_id").val('{{ $staff->district_id }}');
    $("#upazila_id").val('{{ $staff->upazila_id }}');
    $("#area_id").val('{{ $staff->area_id }}');
    $("#branch_id").val('{{ $staff->branch_id }}');
    $("#status").val('{{ $staff->status }}');

    window.onload = function(){
        $('#district_id').on('change', function(){
            var district_id   = $("#district_id option:selected").val();
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
            })
        });

        $('#upazila_id').on('change', function(){
            var upazila_id   = $("#upazila_id option:selected").val();
            $("#area_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                    upazila_id : upazila_id,
                    _token  : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('area.areaOption') }}",
                success   : function(response){
                    $("#area_id").html(response.option).attr('disabled', false);
                }
            })
        });
    }

    function editForm() {
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }
        let upazila_id = $('#upazila_id').val();
        if(upazila_id == '0'){
            toastr.error("Please Select Thana/Upazila..");
            return false;
        }
        let area_id = $('#area_id').val();
        if(area_id == '0'){
            toastr.error("Please Select Area..");
            return false;
        }
        let branch_id = $('#branch_id').val();
        if(branch_id == '0'){
            toastr.error("Please Select Branch..");
            return false;
        }
    }

</script>

@endpush
