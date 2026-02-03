<div class="report-header" style="margin-top: 10px;">
    <h3 class="text-center">Rider Delivery Parcel Report </h3>
    <h5 class="text-center">Date: <b>{{ $start_date }}</b> to <b>{{ $end_date }}</b></h5>
</div>
<table id="riderWiseReport" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="5%" class="text-center"> SL</th>
        <th width="5%" class="text-center"> Name</th>
        <th width="15%" class="text-center"> Area </th>
        <th width="10%" class="text-center"> Total Parcel </th>
        <th width="5%" class="text-center"> Done </th>
        <th width="5%" class="text-center"> Pending </th>
        <th width="5%" class="text-center"> Cancel</th>
        <th width="10%" class="text-center"> Collection Amount</th>
        <th width="20%" class="text-center"> Invoice No</th>
    </tr>
    </thead>

    <tbody>
    @if(count($report_data) > 0)
        @php
            $i = 0;
            $total_parcel = 0;
            $total_done_parcel = 0;
            $total_pending_parcel = 0;
            $total_cancel_parcel = 0;
            $total_collection_amount = 0;
        @endphp
        @foreach($report_data as $report)
            @php
                $i++;

                $total_parcel += $report->total_parcel;
                $total_done_parcel += $report->done_parcel;
                $total_pending_parcel += $report->pending_parcel;
                $total_cancel_parcel += $report->cancel_parcel;
                $total_collection_amount += $report->collection_amount;
            @endphp
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td class="text-center">{{ $report->name }}</td>
                <td class="text-center">{{ $report->branch_name }}</td>
                <td class="text-center">{{ $report->total_parcel }}</td>
                <td class="text-center">{{ $report->done_parcel }}</td>
                <td class="text-center">{{ $report->pending_parcel }}</td>
                <td class="text-center">{{ $report->cancel_parcel }}</td>
                <td class="text-center">{{ $report->collection_amount }}</td>
                <td class="text-center">{{ $report->parcel_invoices }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-center"> <h5><b>Total</b></h5></td>
            <td class="text-center text-bold"> <h5><b>{{ $total_parcel }}</b></h5></td>
            <td class="text-center text-bold"> <h5><b>{{ $total_done_parcel }}</b></h5></td>
            <td class="text-center text-bold"> <h5><b>{{ $total_pending_parcel }}</b></h5></td>
            <td class="text-center text-bold"> <h5><b>{{ $total_cancel_parcel }}</b></h5></td>
            <td class="text-center text-bold"> <h5><b>{{ $total_collection_amount }}</b></h5></td>
            <td class="text-center text-bold"></td>
        </tr>
    @endif
    </tbody>

</table>
