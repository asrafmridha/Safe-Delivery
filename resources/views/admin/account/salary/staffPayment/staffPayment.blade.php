@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Staff Payment</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.account.staffPaymentList') }}">Staff Payment List</a></li>
            <li class="breadcrumb-item active">Payment</li>
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
                        <h3 class="card-title">Make New Payment </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.account.staffPaymentStore') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="branch_id">Branch</label>
                                            <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                                <option value="0">Select Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="staff_id">Staff</label>
                                            <select name="staff_id" id="staff_id" class="form-control select2" style="width: 100%" >
                                                <option value="0">Select Staff</option>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="salary_amount">Salary</label>
                                            <input type="text" name="salary_amount" id="salary_amount" value="{{ old('salary_amount') }}" class="form-control" required readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="payment_month">Salary Month</label>
                                            <input type="month" name="payment_month" id="payment_month" value="{{ old('payment_month') }}" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="paid_amount">Amount</label>
                                            <input type="number" step="any" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}" class="form-control" required>
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
        $('#branch_id').on('change', function(){
            var branch_id  = $("#branch_id option:selected").val();
            $("#staff_id").val(0).change().attr('disabled', true);
            if(branch_id != "" && branch_id != 0) {
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        branch_id: branch_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('admin.account.getStaffOption') }}",
                    success: function (response) {
                        $("#staff_id").html(response.option).attr('disabled', false);
                    }
                });
            }
        });

        $('#staff_id').on('change', function(){
            var staff_id   = $("#staff_id option:selected").val();
            if(staff_id != "" && staff_id != 0){
                var staff_salary   = $("#staff_id option:selected").data('salary');

                $("#salary_amount").val(staff_salary);
            }else{
                $("#salary_amount").val("");
            }
        });
    }


    function createForm(){
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

    function filePreview(input) {
        $('#preview_file').html('');
        if (input.files && input.files[0]) {
            $('#preview_file').html('<img src="{{ asset('image/image_loading.gif') }}" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            var reader = new FileReader();

            if(input.files[0].size > 3000000){
                input.value='';
                $('#preview_file').html('');
            }
            else{
                reader.onload = function (e) {
                $('#preview_file').html('<img src="'+e.target.result+'" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
    }
  </script>
@endpush
