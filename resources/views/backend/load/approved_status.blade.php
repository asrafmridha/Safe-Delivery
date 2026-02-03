
<div class="row">
    <div class="col-md-12">
        @if($transaction->from)
            <div class="row">
                @if($transaction->payment_type=="cash")
                    <div class="col-md-8">
                        <label for="country_id">Select Country</label>
                        <select id="country_id" name="country_id"
                                class="form-control select2 @error('country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{$country->id}}" {{$transaction->from->country_id == $country->id ? 'selected': ''}} >{{$country->name.' - '.$country->code}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="cash_attachment">Add Attachment</label>
                        <input type="file" id="cash_attachment" name="cash_attachment"
                               class="form-control @error('cash_attachment') is-invalid @enderror">
                        @if ($transaction->from && file_exists("uploads/attachments/".$transaction->from->attachment))
                            <a class="badge badge-primary float-right mt-2"
                               href="{{asset("uploads/attachments/".$transaction->to->attachment )}}" download
                               title="Download old attachment">Download old attachment</a>
                        @endif
                    </div>
                @endif
                @if($transaction->payment_type=="bank")
                    <div class="col-md-8">
                        <label for="bank_account_id">Select Account <span class="text-danger">*</span></label>
                        <select id="bank_account_id" name="bank_account_id"
                                class="form-control select2 @error('bank_account_id') is-invalid @enderror">
                            <option value="">Select Account</option>
                            @foreach($accounts as $account)
                                <option
                                    value="{{$account->id}}" {{old('bank_account_id')??$transaction->from->account_id == $account->id ? 'selected': ''}}>
                                    {{$account->account_number.' - '.$account->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="bank_attachment">Add Attachment</label>
                        <input type="file" id="bank_attachment" name="bank_attachment"
                               class="form-control @error('bank_attachment') is-invalid @enderror">
                        @if ($transaction->from && file_exists("uploads/attachments/".$transaction->from->attachment))
                            <a class="badge badge-primary float-right mt-2"
                               href="{{asset("uploads/attachments/".$transaction->from->attachment )}}" download
                               title="Download old attachment">Download old attachment</a>
                        @endif
                    </div>
                @endif
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control">{{$transaction->remarks}}</textarea>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                @if($transaction->payment_type=="cash")
                    <div class="col-md-8">
                        <label for="country_id">Select Country</label>
                        <select id="country_id" name="country_id"
                                class="form-control select2 @error('country_id') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{$country->id}}" {{$transaction->to->country_id == $country->id ? 'selected': ''}} >{{$country->name.' - '.$country->code}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="cash_attachment">Add Attachment</label>
                        <input type="file" id="cash_attachment" name="cash_attachment"
                               class="form-control @error('cash_attachment') is-invalid @enderror">
                    </div>
                @endif
                @if($transaction->payment_type=="bank")
                    <div class="col-md-8">
                        <label for="bank_account_id">Select Account <span class="text-danger">*</span></label>
                        <select id="bank_account_id" name="bank_account_id"
                                class="form-control select2 @error('bank_account_id') is-invalid @enderror">
                            <option value="">Select Account</option>
                            @foreach($accounts as $account)
                                <option
                                    value="{{$account->id}}" {{old('bank_account_id') == $account->id ? 'selected': ''}}>
                                    {{$account->account_number.' - '.$account->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="col-md-4">
                            <label for="bank_attachment">Add Attachment</label>
                            <input type="file" id="bank_attachment" name="bank_attachment"
                                   class="form-control @error('bank_attachment') is-invalid @enderror">
                        </div>
                @endif
                {{--@if($transaction->payment_type=="order")
                    <div class="col-md-12">
                        <label for="bank_account_id">Select Account <span class="text-danger">*</span></label>
                        <select id="bank_account_id" name="bank_account_id"
                                class="form-control select2 @error('bank_account_id') is-invalid @enderror">
                            <option value="">Select Account</option>
                            @foreach($accounts as $account)
                                <option
                                    value="{{$account->id}}" {{old('bank_account_id') == $account->id ? 'selected': ''}}>
                                    {{$account->account_number.' - '.$account->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif--}}
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control">{{$transaction->remarks}}</textarea>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    $('.select2').select2({
        dropdownParent: $('.select2-dropdownParent')
    });
</script>
<script type="text/javascript" src="{{asset('assets/backend/js/jquery.min.js')}}"></script>


