
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Staff Payments</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Staff Payments</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Staff Payment List </h3>
                        <a href="{{ route('admin.account.staffPayment') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Make Payment
                        </a>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="branch_id">Branch </label>
                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Branch  </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="staff_id">Staff </label>
                                <select name="staff_id" id="staff_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Staff </option>
                                    @foreach ($staff as $s_staff)
                                        <option value="{{ $s_staff->id }}">{{ $s_staff->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="month" name="from_date" id="from_date" class="form-control"/>
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="month" name="to_date" id="to_date" class="form-control"/>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="6%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Payment Date </th>
                                        <th width="10%" class="text-center"> Name </th>
                                        <th width="10%" class="text-center"> Contact Number </th>
                                        <th width="10%" class="text-center"> Branch </th>
                                        <th width="10%" class="text-center"> Salary Month </th>
                                        <th width="10%" class="text-center"> Salary Amount</th>
                                        <th width="7%" class="text-center"> Paid Amount </th>
                                        {{--<th width="15%" class="text-center"> Action </th>--}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="viewModal">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-primary">
                      <h4 class="modal-title">View Staff </h4>
                      <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="showResult">

                    </div>
                    <div class="modal-footer">
                      <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>

        </div>
    </div>
  </div>
@endsection


@push('style_css')
    <style>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            margin: 0 auto;
        }

        /*
        div.container {
            width: 80%;
        }
        */
    </style>
@endpush

@push('script_js')
  <script>
    window.onload = function(){

        load_data();

        function load_data(branch_id ='', staff_id ='', from_date ='', to_date ='') {
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.account.getStaffPaymentList') !!}',
                    data: {
                        branch_id   :branch_id,
                        staff_id    :staff_id,
                        from_date   :from_date,
                        to_date     :to_date
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'payment_date', name: 'payment_date' , class : "text-center"},
                    { data: 'staff.name', name: 'staff.name' , class : "text-center"},
                    { data: 'staff.phone', name: 'staff.phone' , class : "text-center"},
                    { data: 'staff.branch.name', name: 'staff.branch.name' , class : "text-center"},
                    { data: 'payment_month', name: 'payment_month' , class : "text-center"},
                    { data: 'salary_amount', name: 'salary_amount' , class : "text-center"},
                    { data: 'paid_amount', name: 'paid_amount' , searchable: false , class : "text-center"},
//                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ],
                order: [[1, 'DESC']]
            });
        }

        $('#filter').click(function(){
            var branch_id   = $('#branch_id option:selected').val();
            var staff_id      = $('#staff_id option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();
            //alert(staff_id);

            $('#yajraDatatable').DataTable().destroy();
            load_data(branch_id, staff_id, from_date, to_date);
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var staff_id = $(this).attr('staff_id');
            var url = "{{ route('admin.staff.show', ":staff_id") }}";
            url = url.replace(':staff_id', staff_id);
            $('#showResult').html('');
            if(staff_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showResult').html(response);
                    },
                })
            }
        });

        $('#yajraDatatable').on('click', '.delete-btn', function(){
            var status_object = $(this);
            var staff_id   = status_object.attr('staff_id');
            var url         = "{{ route('admin.account.staffDelete') }}";

            $.ajax({
                cache       : false,
                type        : "DELETE",
                dataType    : "JSON",
                data        : {
                    staff_id: staff_id,
                    _token : "{{ csrf_token() }}"
                },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : url,
                success   : function(response){
                    if(response.success){
                        toastr.success(response.success);
                        $('#yajraDatatable').DataTable().ajax.reload();
                    }
                    else{
                        toastr.error(response.error);
                    }
                }
            })
        });

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

    }
  </script>
@endpush

