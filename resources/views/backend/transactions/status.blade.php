
<div class="modal-header bg-default">
    <h4 class="modal-title">Change Status </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body select2-dropdownParent" id="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Change Status</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('transaction.change.status',$transaction->id)}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status">Select Status</label>
                                            <select name="status" id="status"
                                                    class="form-control @error('status') is-invalid @enderror"
                                                    onchange="getStatus()">
                                                <option
                                                    value="0" {{$transaction->status == 0 ? 'selected': ''}}>
                                                    Pending
                                                </option>
                                                <option
                                                    value="3" {{$transaction->status == 3 ? 'selected': ''}}>
                                                    Order
                                                </option>
                                                <option
                                                    value="1" {{$transaction->status == 1 ? 'selected': ''}}>
                                                    Approved
                                                </option>
                                                    <option
                                                        value="4" {{$transaction->status == 4 ? 'selected': ''}}>
                                                        Completed
                                                    </option>
                                                <option
                                                    value="2" {{$transaction->status == 2 ? 'selected': ''}}>
                                                    Rejected
                                                </option>
                                            </select>
                                            @error('status')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="float-right col-md-6">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <input type="text" name="remarks" id="remarks" class="form-control"
                                                   value="{{$transaction->remarks}}">
                                        </div>
                                        @error('pin')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="float-right col-md-2">
                                        <label for="status">Your Pin</label>
                                        <input type="text" class="form-control @error('pin') is-invalid @enderror"
                                               id="pin" name="pin" placeholder="Your Pin">
                                        @error('pin')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="input-group-append" style="margin-top: 20px">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>
{{--<script type="text/javascript" src="{{asset('assets_new/js/jquery-2.1.4.min.js')}}"></script>--}}

{{--<script type="text/javascript" src="{{asset('assets/backend/js/jquery.min.js')}}"></script>--}}

<script>

</script>
