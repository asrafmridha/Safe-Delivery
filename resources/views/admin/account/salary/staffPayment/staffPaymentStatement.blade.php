
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Staff Payments Statement</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Staff Payments Statement</li>
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
                        <h3 class="card-title"> Staff Payment Statement</h3>

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
                        <table id="staffPaymentStatement" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center"> Date </th>
                                    <th width="10%" class="text-center"> Name </th>
                                    <th width="10%" class="text-center"> Position </th>
                                    <th width="10%" class="text-center"> Contact Number </th>
                                    <th width="10%" class="text-center"> Branch </th>
                                    <th width="10%" class="text-center"> Salary Amount</th>
                                    <th width="7%" class="text-center"> Paid Amount </th>
                                    {{--<th width="15%" class="text-center"> Action </th>--}}
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    if(count($staff_payments) > 0) {

                                        $total_salary_amount = 0;
                                        $tota_paid_amount = 0;
                                        $old_month = "";

                                        $counter = 1;
                                        $column = "";
                                        $k = 0;
                                        foreach ($staff_payments as $spayment) {

                                            $total_salary_amount += $spayment->salary_amount;
                                            $tota_paid_amount   += $spayment->paid_amount;

                                            $new_month = $spayment->payment_month;

                                            if($new_month != $old_month) {

                                                $counter = count($final_array[$new_month]);
                                                $column = '<td rowspan="'.$counter.'" style="vertical-align: middle;">'.date("M Y", strtotime($spayment->payment_month)).'</td>';
                                            }else{
                                                $column = '';
                                                //$column = '<td>'.date("M Y", strtotime($spayment->payment_month)).'</td>';
                                            }

                                            echo '<tr>
                                                    '.$column.'
                                                    <td>'.$spayment->name.'</td>
                                                    <td>'.$spayment->designation.'</td>
                                                    <td>'.$spayment->phone.'</td>
                                                    <td>'.$spayment->branch_name.'</td>
                                                    <td class="text-right">'.$spayment->salary_amount.'</td>
                                                    <td class="text-right">'.$spayment->paid_amount.'</td>
                                                </tr>';

                                            $old_month = $new_month;
                                        }

                                        echo '<tr>
                                                    <th colspan="5" class="text-right"> Total Amount: </th>
                                                    <th class="text-right">'.number_format($total_salary_amount, 2, '.', '').'</th>
                                                    <th class="text-right">'.number_format($tota_paid_amount, 2, '.', '').'</th>
                                                </tr>';
                                    }else{
                                        echo '<tr>
                                                <td colspan="6"> No Data Available Here!</td>
                                            </tr>';
                                    }
                                @endphp
                            </tbody>
                        </table>
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

@push('script_js')
  <script>
    window.onload = function(){

        $('#filter').click(function(){
            var branch_id   = $('#branch_id option:selected').val();
            var staff_id      = $('#staff_id option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();
            //alert(staff_id);

            if((branch_id != "" && branch_id != 0) || (staff_id != "" && staff_id != 0) || from_date != "" || to_date != "") {
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        branch_id: branch_id,
                        staff_id: staff_id,
                        from_date: from_date,
                        to_date: to_date,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('admin.account.getFilterStaffPaymentStatement') }}",
                    success: function (response) {
                        $("#staffPaymentStatement tbody").html(response);
                    }
                });
            }

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

