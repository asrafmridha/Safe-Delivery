<div class="modal-header bg-default">
    <h4 class="modal-title">Delivery Rider Run Reconciliation </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmDeliveryRiderRunReconciliation') }}" id="confirmAssignDeliveryBranch" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <input type="hidden" name="rider_run_id" id="rider_run_id" value="{{ $riderRun->id }}">
        <input type="hidden" name="total_run_complete_parcel" id="total_run_complete_parcel" value="{{ $riderRun->total_run_complete_parcel }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Delivery Rider Run  Information</legend>
                        <div class="row">
                            <div class="col-md-6 informationRiderRun" style="padding-bottom: 40px;">
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Consignment </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $riderRun->run_invoice }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Create Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->create_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>

                                @if($riderRun->start_date_time)
                                <tr>
                                    <th style="width: 40%">Start Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->start_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                @if($riderRun->cancel_date_time)
                                <tr>
                                    <th style="width: 40%">Cancel Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->cancel_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                @if($riderRun->complete_date_time)
                                <tr>
                                    <th style="width: 40%">Complete Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->complete_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                <tr>
                                    <th style="width: 40%">Total Run </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $riderRun->total_run_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Total Run Complete </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" id="view_total_run_complete_parcel"> {{ $riderRun->total_run_complete_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Status </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                    @switch($riderRun->status)
                                        @case(1)
                                            <div class="badge badge-success">Run Create </div>
                                            @break
                                        @case(2)
                                            <div class="badge badge-success">Run Start </div>
                                            @break
                                        @case(3)
                                            <div class="badge badge-danger " >Run Cancel </div>
                                            @break
                                        @case(4)
                                            <div class="badge badge-success">Run Complete </div>
                                            @break
                                        @default
                                    @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <textarea name="run_note" class="form-control" placeholder="Rider Run Not">{{ $riderRun->note }}</textarea>
                                    </th>
                                </tr>
                            </table>

                            <h4 class="total_complete_collection_amount">Complete Collection Amount: <span>0</span></h4>

                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Rider Information </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $riderRun->rider->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact Number </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $riderRun->rider->contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $riderRun->rider->address }} </td>
                                        </tr>

                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        @if($riderRun->rider_run_details->count() > 0)
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center">Order ID </th>
                                        <th width="10%" class="text-center">Run Status</th>
                                        <th width="10%" class="text-center">M.Company</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="10%" class="text-center">Total Collection Amount</th>
                                        <th width="15%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Delivery Type </th>
                                        <th width="10%" class="text-center"></th>
                                        <th width="30%" class="text-center">Complete Note </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riderRun->rider_run_details as $rider_run_detail)
                                    <tr>
                                        <td class="text-center"> {{ $rider_run_detail->parcel->parcel_invoice }} </td>
                                        <td class="text-center">
                                            @switch($rider_run_detail->status)
                                                @case(1) Run Create  @break
                                                @case(2) Run Start @break
                                                @case(3) Run Cancel @break
                                                @case(4) Rider Accept @break
                                                @case(5) Rider Reject @break
                                                @case(7) Rider Complete @break
                                                @default  @break
                                            @endswitch
                                            @if($rider_run_detail->status == 7)
                                                <br>
                                                {{ \Carbon\Carbon::parse($rider_run_detail->complete_date_time)->format('d/m/Y H:i:s') }} <br>
                                            @endif
                                        </td>
                                        <td class="text-center"> {{ ($rider_run_detail->parcel->merchant) ? $rider_run_detail->parcel->merchant->company_name : "" }} </td>
                                        <td class="text-center"><b>  {{ $rider_run_detail->parcel->customer_name }}</b> <br> {{ $rider_run_detail->parcel->customer_address }} </td>
                                        <td class="text-center">
                                            
                                             {{  $rider_run_detail->parcel->total_collect_amount }}
                                             
                                             <input type="hidden"
                                                    id="total_collect_amount{{ $rider_run_detail->id }}"
                                                    value="{{ $rider_run_detail->parcel->total_collect_amount }}">
                                             
                                             </td>
                                        <td class="text-center">
                                            <select name="rider_run_status[]"  class="form-control select2 rider_run_status"
                                                style="width: 100%" onchange="return rider_run_status(this,{{ $rider_run_detail->id }})">
                                                <option value="7" @if($rider_run_detail->status == 7) selected ="" @endif >Run Complete</option>
                                                <option value="5" @if($rider_run_detail->status != 7) selected ="" @endif >Run Reject</option>
                                            </select>
                                            <input type="hidden" name="rider_run_details_id" class="rider_run_details_id" value="{{$rider_run_detail->id }}">
                                            <input type="hidden" name="parcel_id" class="parcel_id" value="{{$rider_run_detail->parcel_id }}">
                                        </td>
                                        <td class="text-center">
                                            <select name="complete_type[]"
                                                class="form-control select2 complete_type"
                                                id="complete_type{{ $rider_run_detail->id }}"
                                                style="width: 100%"
                                                onchange="return check_complete_type(this,{{ $rider_run_detail->id }})">
                                                <option value="0">Select Delivery Type</option>
                                                <option
                                                    @if($rider_run_detail->parcel->status == 22) value="22" @else value="21" @endif
                                                    @if($rider_run_detail->parcel->status == 21 || $rider_run_detail->parcel->status == 22) selected ="" @endif
                                                    >Delivered</option>
                                                <option value="22" @if($rider_run_detail->parcel->status == 22) selected ="" @endif >Partial Delivered</option>
                                                <option value="23" @if($rider_run_detail->parcel->status == 23) selected ="" @endif >Hold/Reschedule</option>
                                                <option value="24" @if($rider_run_detail->parcel->status == 24) selected ="" @endif > Canceled / Return</option>
{{--                                                <option value="24" @if($rider_run_detail->parcel->status == 24) selected ="" @endif >Delivery Cancel</option>--}}
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div id="div_customer_collect_amount{{ $rider_run_detail->id }}" style="display: {{ ($rider_run_detail->parcel->status == 21 || $rider_run_detail->parcel->status == 22) ? "inline" : "none" }}">
                                                <input type="number" name="customer_collect_amount[]"
                                                    class="form-control customer_collect_amount"
                                                    id="customer_collect_amount{{ $rider_run_detail->id }}"
                                                    value="{{ $rider_run_detail->parcel->customer_collect_amount }}"
                                                    placeholder="Customer Payment Amount"
                                                    total_collect_amount="{{ $rider_run_detail->parcel->total_collect_amount }}"
                                                    customer_collect_amount="{{ $rider_run_detail->parcel->customer_collect_amount }}"
                                                    style="width: 100%" >
                                            </div>
                                            <div id="div_reschedule_parcel_date{{ $rider_run_detail->id }}" style="display: {{ ($rider_run_detail->parcel->status == 23) ? "inline" : "none" }}">

                                                @php
                                                    if(!is_null($rider_run_detail->parcel->reschedule_parcel_date) && "" != $rider_run_detail->parcel->reschedule_parcel_date) {
                                                        $reschedule_date = date('Y-m-d', strtotime($rider_run_detail->parcel->reschedule_parcel_date) );
                                                    }else{
                                                        $reschedule_date = date("Y-m-d", strtotime("+ 1 day"));
                                                    }
                                                @endphp

                                                <input type="date" name="reschedule_parcel_date[]"  class="form-control reschedule_parcel_date" id="reschedule_parcel_date{{ $rider_run_detail->id }}" value="{{ $reschedule_date }}">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <textarea name="complete_note[]" class="form-control complete_note" placeholder="Complete Not">{{ $rider_run_detail->complete_note }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">Confirm </button>
            <button type="reset" class="btn btn-primary">Reset</button>
        </div>
    </form>

</div>
<div class="modal-footer">
    <button  type="button" id="viewModalCloseBtn"  class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
    .card-body{
        padding: 0.25rem;
    }

    .informationRiderRun {
        position: relative;
    }
    .total_complete_collection_amount {
        position: absolute;
        bottom: 0;
        left: 7px;
        /*z-index: 2;*/
    }

</style>
<script>

    function rider_run_status(object, rider_run_detail_id){
        var complete = 0;
        var rider_run_status = $('.rider_run_status option:selected').map(function(){
                if(this.value == 7){
                    complete++;
                }
        }).get();
        $("#view_total_run_complete_parcel").html(complete);
        $("#total_run_complete_parcel").val(complete);

        if($(object).val() == '5'){
            $("#div_customer_collect_amount"+rider_run_detail_id).hide();
            $("#div_reschedule_parcel_date"+rider_run_detail_id).hide();
            $("#complete_type"+rider_run_detail_id).val(0).change().select2("enable", false)
        }
        else{
            $("#div_customer_collect_amount"+rider_run_detail_id).hide();
            $("#div_reschedule_parcel_date"+rider_run_detail_id).hide();
            $("#complete_type"+rider_run_detail_id).select2("enable");
        }
    }

    function check_complete_type(object, rider_run_detail_id){
        
        let total_collect_amount = $("#customer_collect_amount" + rider_run_detail_id).attr('total_collect_amount');
        console.log(total_collect_amount);

        if($(object).val() == '21' || $(object).val() == '22' ){
            $("#div_customer_collect_amount"+rider_run_detail_id).show();
            $("#div_reschedule_parcel_date"+rider_run_detail_id).hide();
            $("#customer_collect_amount" + rider_run_detail_id).val(total_collect_amount);
        }
        else if($(object).val() == '23') {
            $("#div_customer_collect_amount" + rider_run_detail_id).hide();
            $("#customer_collect_amount" + rider_run_detail_id).val(0);
            $("#div_reschedule_parcel_date" + rider_run_detail_id).show();

        }else{
            $("#div_customer_collect_amount"+rider_run_detail_id).hide();
            $("#customer_collect_amount"+rider_run_detail_id).val(0);
            $("#div_reschedule_parcel_date"+rider_run_detail_id).hide();
        }
    }

    function createForm(object){
        event.preventDefault();

        var rider_run_id                = $("#rider_run_id").val();
        var total_run_complete_parcel   = $("#total_run_complete_parcel").val();
        let run_note                    = $('#run_note').val();

        var rider_run_status        = $('.rider_run_status').map(function(){
                return this.value;
        }).get();



        var rider_run_details_id        = $('.rider_run_details_id').map(function(){
                return this.value;
        }).get();

        var parcel_id        = $('.parcel_id').map(function(){
                return this.value;
        }).get();

        var complete_type          = $('.complete_type').map(function(){
                return this.value;
        }).get();
        var customer_collect_amount  = $('.customer_collect_amount').map(function(){
                return this.value;
        }).get();
        var reschedule_parcel_date   = $('.reschedule_parcel_date').map(function(){
                return this.value;
        }).get();
        var complete_note        = $('.complete_note').map(function(){
                return this.value;
        }).get();


 

        let check = 0;

        

        complete_type.forEach(function(value,index) {

            let t_d_id = rider_run_details_id[index];

            if (value == 0) {

            $('#complete_type' + t_d_id).next('.select2-container--default').find('.select2-selection--single').css('border-color', "red");

            check += 1;

            }else{

            $('#complete_type' + t_d_id).next('.select2-container--default').find('.select2-selection--single').css('border-color', "");  

            }

        });

        

        if (check > 0) {

            alert("Please select a delivery type.");

            return false; 

        }

        
        
        

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                rider_run_id                : rider_run_id,
                total_run_complete_parcel   : total_run_complete_parcel,
                run_note                    : run_note,
                rider_run_status            : rider_run_status,
                rider_run_details_id        : rider_run_details_id,
                parcel_id                   : parcel_id,
                complete_type               : complete_type,
                customer_collect_amount     : customer_collect_amount,
                reschedule_parcel_date      : reschedule_parcel_date,
                complete_note               : complete_note,
                _token                     : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();
                    $('#yajraDatatable').DataTable().ajax.reload();
                    setTimeout(function(){
                        $('#viewModal').modal('hide');
                        $('#viewModalCloseBtn').click();
                    },1000);
                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.run_note){
                        message = getError.run_note[0];
                    }
                    if(getError.rider_run_id){
                        message = getError.rider_run_id[0];
                    }
                    if(getError.total_run_complete_parcel){
                        message = getError.total_run_complete_parcel[0];
                    }
                    else{
                        message = getError;
                    }
                    toastr.error(message);
                }
            }
        })

    }

    $(document).ready(function () {
        $(document).on("keyup", ".customer_collect_amount", function() {
            var total_collect_amount    = returnNumber($(this).attr('total_collect_amount'));
            var customer_collect_amount = returnNumber($(this).attr('customer_collect_amount'));
            var current_amount          = returnNumber($(this).val());
            if(current_amount > total_collect_amount){
                $(this).val(customer_collect_amount);
            }
            else{
                $(this).val(current_amount);
            }
        });
    });

    setInterval(() => {
        var sum = 0;
        $(".customer_collect_amount").each(function(){
            sum += returnNumber($(this).val());
        });
        $(".total_complete_collection_amount span").text(sum);
    }, 500);

</script>
