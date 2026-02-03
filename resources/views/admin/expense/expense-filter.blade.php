<table class="table table-bordered" width="100%" style="margin-top: 3rem">
    <thead>
        <tr>
            <th colspan="5">
                <a href="{{ route('admin.filter.expense.print',['type' => $type??0, 'expense_head_id' => $expense_head_id??0, 'from_date' => $from_date??0, 'to_date' => $to_date??0]) }}" class="btn btn-primary" target="_blank" style="float: right;">
                    <i class="fas fa-print"></i> Print
                </a>
            </th>
        </tr>
        <tr>
            <th width="10%" class="text-center"> Date</th>
            <th width="10%" class="text-center"> Type</th>
            <th width="15%" class="text-center"> Expense Head Name </th>
            <th width="10%" class="text-right"> Amount </th>
            <th width="10%" class="text-center"> Note </th>
    </thead>
    <tbody>
        @foreach ($models as $model)
            <tr>
                <td class="text-center"> {{ $model->date }} </td>
                <td>
                    @if ($model->type == 1)
                        <span class="text-center">Expense</span>
                    @elseif ($model->type == 2)
                        <span class="text-center">Income</span>
                    @else
                        <span class="text-center">N/A</span>
                    @endif
                </td>
                <td class="text-center"> {{ $model->expense_heads->name }} </td>

                <td class="text-center"> {{ $model->amount }} </td>
                <td class="text-center"> {{ $model->note }} </td>

            </tr>
        @endforeach

    </tbody>
</table>


