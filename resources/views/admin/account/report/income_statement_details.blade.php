
<div class="row">
    <div class="col-sm-12">
        <h4 class="bg-info p-1">Month: <?= date('M-Y',strtotime($month)); ?></h4>
    </div>
    <div class="col-sm-6">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-success">
                    <th colspan="4">Income</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th width="20%">Amount In Tk.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="3">Opening Balance</th>
                    <th class="text-right">{{ $opening_balance }}</th>
                </tr>
                @php
                    $sl=0;
                    $receipt = 0;
                @endphp
                @foreach ($receiveds as $received)
                    @php
                        $receipt += $received->amount;
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ date('d/m/Y',strtotime($received->date_time)) }}</td>
                    <td>
                        {{ $received->parcel->merchant->name }} - 
                        {{ $received->parcel->merchant->company_name }}<br/>
                        {{ $received->parcel->merchant->address }} 
                        ({{ $received->parcel->merchant->contact_number }})
                    </td>
                    <td class="text-right">{{ number_format($received->amount) }}</td>
                </tr> 
                @endforeach
                    @php
                        $receipt += ($delivery_charge+$return_charge+$weight_package_charge+$cod_charge);
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>--</td>
                    <td>
                        Delivery Charge
                    </td>
                    <td class="text-right">{{ number_format($delivery_charge+$return_charge+$weight_package_charge) }}</td>
                </tr> 
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>--</td>
                    <td>
                        COD Charge
                    </td>
                    <td class="text-right">{{ number_format($cod_charge) }}</td>
                </tr> 
                
                @foreach ($incomes as $income)
                    @php
                        $receipt += $income->amount;
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ date('d/m/Y',strtotime($income->date)) }}</td>
                    <td>
                        {{ $income->expense_heads->name }}<br/>
                    </td>
                    <td class="text-right">{{ number_format($income->amount) }}</td>
                </tr> 
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Closing Balance</th>
                    <th class="text-right">{{ number_format($receipt) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="col-sm-6">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-success">
                    <th colspan="4">Expenses</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th width="20%">Amount In Tk.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sl=0;
                    $payment = 0;
                @endphp
                @foreach ($marchent_payments as $received)
                    @php
                        $payment += $received->paid_amount;
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ date('d/m/Y',strtotime($received->date_time)) }}</td>
                    <td>
                        {{ $received->parcel->merchant->name }} - 
                        {{ $received->parcel->merchant->company_name }}<br/>
                        {{ $received->parcel->merchant->address }} 
                        ({{ $received->parcel->merchant->contact_number }})
                    </td>
                    <td class="text-right">{{ number_format($received->paid_amount) }}</td>
                </tr> 
                @endforeach
                
                @foreach ($salaries as $salary)
                    @php
                        $payment += $salary->paid_amount;
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ date('d/m/Y',strtotime($salary->payment_date)) }}</td>
                    <td>
                        <b>Salary</b> - 
                        {{ $salary->staff->name }}
                    </td>
                    <td class="text-right">{{ number_format($salary->paid_amount) }}</td>
                </tr> 
                @endforeach
                @foreach ($expenses as $expense)
                    @php
                        $payment += $expense->amount;
                    @endphp
                <tr>
                    <td>{{ ++$sl }}</td>
                    <td>{{ date('d/m/Y',strtotime($expense->date)) }}</td>
                    <td>
                        {{ $expense->expense_heads->name }}<br/>
                    </td>
                    <td class="text-right">{{ number_format($expense->amount) }}</td>
                </tr> 
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Closing Balance</th>
                    <th class="text-right">{{ number_format($payment) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-sm-6">
        <br/>
        <h4 class="bg-info">Total: {{ $opening_balance+$receipt-$payment }}</h4>
    </div>
</div>

