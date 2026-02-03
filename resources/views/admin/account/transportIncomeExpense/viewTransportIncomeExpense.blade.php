<div class="modal-header bg-default">
    <h4 class="modal-title">Transport Income Expense </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Transport Income ExpenseInformation</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($transportIncomeExpense->date)->format('d/m/Y') }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Vehicle </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $transportIncomeExpense->vehicle->name.' '.$transportIncomeExpense->vehicle->vehicle_sl_no.' '.$transportIncomeExpense->vehicle->vehicle_no }} </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 40%"> Driver Name</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $transportIncomeExpense->driver_name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Driver Phone</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $transportIncomeExpense->vehicle_driver_phone }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>KM</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Starting KM </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->starting_km }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Ending KM </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->ending_km }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Total KM </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->total_km }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Destination</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> To Destination </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->to_destination }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> From Destination </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->from_destination }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Trip</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Advance Trip Amount</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->advance_trip_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Up Trip Amount </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->up_trip_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Down Trip Amount </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->down_to_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Total Trip Amount </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->total_trip_amount }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Income</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> All Expense</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->all_expense_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> All Income </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->all_income_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> All Net Income </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->all_net_income }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Amount</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Received Amount</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->received_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Due Amount</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->due_amount }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Remark </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $transportIncomeExpense->remark }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>


