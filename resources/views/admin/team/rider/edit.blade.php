@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Rider</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.rider.index') }}">Rider</a></li>
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
                            <h3 class="card-title">Edit Rider </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.rider.update', $rider->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $rider->name ?? old('name') }}" class="form-control" placeholder="Rider Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Full Address</label>
                                                <input type="text" name="address" id="address" value="{{ $rider->address ?? old('address') }}" class="form-control" placeholder="Rider Address" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="district_id"> Districts </label>
                                                        <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                                          <option value="0">Select District</option>
                                                          @foreach ($districts as $district)
                                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                          @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="upazila_id"> Thana/Upazila </label>
                                                        <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Thana/Upazila</option>
                                                            @foreach ($upazilas as $upazila)
                                                                <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="area_id"> Area </label>
                                                        <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Area</option>
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="branch_id">Branch</label>
                                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                                    <option value="0">Select Branch</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="salary"> Salary </label>
                                                        <div class="input-group mb-2">
                                                            <input type="number" name="salary" id="salary" value="{{ $rider->salary ?? 0 }}" class="form-control"  placeholder="Rider salary" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="contact_number"> Contact Number </label>
                                                        <div class="input-group mb-2">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">+88</div>
                                                            </div>
                                                            <input type="text" name="contact_number" id="contact_number" value="{{ $rider->contact_number ??  old('contact_number') }}" class="form-control"  placeholder="Rider Contact Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" value="{{ $rider->email ??  old('email') }}" class="form-control" placeholder="Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" value="{{ $rider->store_password ?? old('password') }}" class="form-control" placeholder="Password" >
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this)" >
                                                <div id="preview_file_image" style="margin-top: 10px;">
                                                    @if ($rider->image != null)
                                                        <img src="{{ asset('uploads/rider/' . $rider->image) }}"
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
    $("#district_id").val('{{ $rider->district_id }}');
    {{--$("#upazila_id").val('{{ $rider->upazila_id }}');--}}
    $("#area_id").val('{{ $rider->area_id }}');
    $("#branch_id").val('{{ $rider->branch_id }}');
    $("#status").val('{{ $rider->status }}');

    window.onload = function(){
        $('#district_id').on('change', function(){
            var district_id   = $("#district_id option:selected").val();
            // $("#upazila_id").val(0).change().attr('disabled', true);
            $("#area_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        district_id: district_id,
                        _token : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                // url       : "{{ route('upazila.districtOption') }}",
                url       : "{{ route('area.districtWiseAreaOption') }}",
                success   : function(response){
                    // $("#upazila_id").html(response.option).attr('disabled', false);
                    $("#area_id").html(response.option).attr('disabled', false);
                }
            })
        });

        // $('#upazila_id').on('change', function(){
        //     var upazila_id   = $("#upazila_id option:selected").val();
        //     $("#area_id").val(0).change().attr('disabled', true);
        //     $.ajax({
        //         cache     : false,
        //         type      : "POST",
        //         dataType  : "JSON",
        //         data      : {
        //             upazila_id : upazila_id,
        //             _token  : "{{ csrf_token() }}"
        //             },
        //         error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
        //         url       : "{{ route('area.areaOption') }}",
        //         success   : function(response){
        //             $("#area_id").html(response.option).attr('disabled', false);
        //         }
        //     })
        // });
    }

    function editForm() {
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }
        // let upazila_id = $('#upazila_id').val();
        // if(upazila_id == '0'){
        //     toastr.error("Please Select Thana/Upazila..");
        //     return false;
        // }
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
