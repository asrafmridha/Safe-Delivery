<div class="row">
    <div class="col-md-12">
        <label for="attachment">Add Attachment</label>
        <input type="file" id="attachment" name="attachment"
               class="form-control @error('attachment') is-invalid @enderror">
        @if (file_exists("uploads/attachments/".$transaction->attachment))
            <a class="badge badge-primary float-right mt-2"
               href="{{asset("uploads/attachments/".$transaction->attachment )}}" download
               title="Download old attachment">Download old attachment</a>
        @endif
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control">{{$transaction->remarks}}</textarea>
        </div>
    </div>
</div>


