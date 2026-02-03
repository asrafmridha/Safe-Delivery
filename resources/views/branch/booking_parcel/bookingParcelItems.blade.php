<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Item List </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <form role="form" action="{{ route('branch.bookingPercel.printBookingItemBarcode') }}" method="POST" target="_blank" enctype="multipart/form-data">
            @csrf
           <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking_parcel->id }}" >
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <fieldset>
                        <legend>Item Information </legend>
                        <table class="table table-style table-bordered">
                            <tr>
                                <th>SL. No.</th>
                                <th>Item Category </th>
                                <th>Item Name </th>
                                <th>Unit </th>
                                <th>Unit Rate </th>
                                <th>Quantity </th>
                                <th>Total Rate </th>
                                <th>Number Of Barcode</th>
                            </tr>
                            @foreach ($booking_parcel->booking_items as $booking_item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                    <td>{{ $booking_item->unit_price }}</td>
                                    <td>{{ $booking_item->quantity }}</td>
                                    <td>{{ $booking_item->total_item_price }}</td>
                                    <td>
                                        <input type="number" name="number_of_barcode[{{ $booking_item->id }}]" id="number_of_barcode" value="1" class="form-control" min="1" placeholder="Number of barcode" required>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </fieldset>

                    <div class="form-group">
                        <label for="item_name"></label>
                        <button class="btn btn-sm btn-success" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>
