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
                        <li class="breadcrumb-item"><a href="{{ route('admin.rider.index') }}">Riders</a></li>
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
                            <h3 class="card-title">Create New Rider </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.rider.store') }}" method="POST"
                                          enctype="multipart/form-data" onsubmit="return createForm()">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                                       class="form-control" placeholder="Rider Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Full Address</label>
                                                <input type="text" name="address" id="address"
                                                       value="{{ old('address') }}" class="form-control"
                                                       placeholder="Rider Address" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="district_id"> Districts </label>
                                                        <select name="district_id" id="district_id"
                                                                class="form-control select2" style="width: 100%">
                                                            <option value="0">Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option
                                                                    value="{{ $district->id }}">{{ $district->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="upazila_id"> Thana/Upazila </label>
                                                        <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Thana/Upazila</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="area_id"> Area </label>
                                                        <select name="area_id" id="area_id" class="form-control select2"
                                                                style="width: 100%">
                                                            <option value="0">Select Area</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="branch_id">Branch</label>
                                                <select name="branch_id" id="branch_id" class="form-control select2"
                                                        style="width: 100%">
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
                                                            <input type="number" name="salary" id="salary"
                                                                   value="{{ old('salary') ?? 0 }}"
                                                                   class="form-control" placeholder="Rider salary"
                                                                   required="">
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
                                                            <input type="text" name="contact_number" id="contact_number"
                                                                   value="{{ old('contact_number') }}"
                                                                   class="form-control"
                                                                   placeholder="Rider Contact Number" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                                       class="form-control" placeholder="Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="text" name="password" id="password"
                                                       value="{{ old('password') }}" class="form-control"
                                                       placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image </label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                       accept="image/*" onchange="return filePreview(this)">
                                                <div id="preview_file" style="margin-top: 10px;"></div>
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

        window.onload = function () {
            $('#district_id').on('change', function () {
                var district_id = $("#district_id option:selected").val();
                // $("#upazila_id").val(0).change().attr('disabled', true);
                $("#area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        district_id: district_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    // url       : "{{ route('upazila.districtOption') }}",
                    url: "{{ route('area.districtWiseAreaOption') }}",
                    success: function (response) {
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


        function createForm() {
            let district_id = $('#district_id').val();
            if (district_id == '0') {
                toastr.error("Please Select District..");
                return false;
            }
            // let upazila_id = $('#upazila_id').val();
            // if(upazila_id == '0'){
            //     toastr.error("Please Select Thana/Upazila..");
            //     return false;
            // }
            let area_id = $('#area_id').val();
            if (area_id == '0') {
                toastr.error("Please Select Area..");
                return false;
            }

            let branch_id = $('#branch_id').val();
            if (branch_id == '0') {
                toastr.error("Please Select Branch..");
                return false;
            }
        }

        function filePreview(input) {
            $('#preview_file').html('');
            if (input.files && input.files[0]) {
                $('#preview_file').html('<img src="{{ asset('image/image_loading.gif') }}" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
                var reader = new FileReader();

                if (input.files[0].size > 3000000) {
                    input.value = '';
                    $('#preview_file').html('');
                } else {
                    reader.onload = function (e) {
                        $('#preview_file').html('<img src="' + e.target.result + '" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    </script>
@endpush
