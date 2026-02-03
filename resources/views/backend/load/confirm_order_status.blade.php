<div class="row">
    <div class="col-md-12">
        @if($transaction->to)
            <div class="row">
                <div class="col-md-12">
                    <label for="payment_type">Select Payment Type</label>
                    <select id="payment_type" name="payment_type"
                            class="form-control @error('payment_type') is-invalid @enderror" onchange="getMethod()">
                        <option value="cash" {{old('payment_type')??$transaction->payment_type == 'cash' ? 'selected': ''}}>Cash</option>
                        <option value="bank" {{old('payment_type')??$transaction->payment_type == 'bank' ? 'selected': ''}}>Bank</option>
                    </select>
                    @error('payment_type')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-12 mt-3" id="show_cash">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Cash Information</legend>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="cash_country_id">Select Country <span class="text-danger">*</span></label>
                                <select id="cash_country_id" name="cash_country_id"
                                        class="form-control select2 @error('cash_country_id') is-invalid @enderror">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option
                                            value="{{$country->id}}" {{old('cash_country_id')??$transaction->to->country_id == $country->id ? 'selected': ''}}>
                                            {{$country->name.' - '.$country->code}}</option>
                                    @endforeach
                                </select>
                                @error('cash_country_id')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="cash_attachment">Add Attachment</label>
                                <input type="file" id="cash_attachment" name="cash_attachment"
                                       class="form-control @error('cash_attachment') is-invalid @enderror">
                                @if ($transaction->to && file_exists("uploads/attachments/".$transaction->to->attachment))
                                    <a class="badge badge-primary float-right mt-2"
                                       href="{{asset("uploads/attachments/".$transaction->to->attachment )}}" download
                                       title="Download old attachment">Download old attachment</a>
                                @endif
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12 mt-3" id="show_bank">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Bank Information</legend>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="bank_account_id">Select Account <span class="text-danger">*</span></label>
                                <select id="bank_account_id" name="bank_account_id"
                                        class="form-control select2 @error('bank_account_id') is-invalid @enderror">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option
                                            value="{{$account->id}}" {{old('bank_account_id')??$transaction->to->account_id == $account->id ? 'selected': ''}}>
                                            {{$account->account_number.' - '.$account->account_name}}</option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="bank_attachment">Add Attachment</label>
                                <input type="file" id="bank_attachment" name="bank_attachment"
                                       class="form-control @error('bank_attachment') is-invalid @enderror">
                                @if ($transaction->to && file_exists("uploads/attachments/".$transaction->to->attachment))
                                    <a class="badge badge-primary float-right mt-2"
                                       href="{{asset("uploads/attachments/".$transaction->to->attachment )}}" download
                                       title="Download old attachment">Download old attachment</a>
                                @endif
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control">{{$transaction->remarks}}</textarea>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <label for="payment_type">Select Payment Type</label>
                    <select id="payment_type" name="payment_type"
                            class="form-control @error('payment_type') is-invalid @enderror">
                        <option value="cash" {{old('payment_type') == 'cash' ? 'selected': ''}}>Cash</option>
                        <option value="bank" {{old('payment_type') == 'bank' ? 'selected': ''}}>Bank</option>
                    </select>
                    @error('payment_type')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-12 mt-3" id="show_cash">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Cash Information</legend>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="cash_country_id">Select Country</label>
                                <select id="cash_country_id" name="cash_country_id"
                                        class="form-control select2 @error('cash_country_id') is-invalid @enderror">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option
                                            value="{{$country->id}}" {{old('cash_country_id') == $country->id ? 'selected': ''}}>
                                            {{$country->name.' - '.$country->code}}</option>
                                    @endforeach
                                </select>
                                @error('cash_country_id')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="cash_attachment">Add Attachment</label>
                                <input type="file" id="cash_attachment" name="cash_attachment"
                                       class="form-control @error('cash_attachment') is-invalid @enderror">
                                @error('cash_attachment')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12 mt-3" id="show_bank">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Bank Information</legend>
                        <div class="row">
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
                                @error('bank_account_id')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="bank_attachment">Add Attachment</label>
                                <input type="file" id="bank_attachment" name="bank_attachment"
                                       class="form-control @error('bank_attachment') is-invalid @enderror">
                                @error('bank_attachment')
                                <div class="text-danger font-italic">
                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>
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
    (function(){
        var optionValue = $("#payment_type option:selected").val();
        if (optionValue == 'cash') {
            $("#show_cash").show();
            $("#show_bank").hide();
        } else if (optionValue == 'bank') {
            $("#show_bank").show();
            $("#show_cash").hide();
        } else if (optionValue == 'order') {
            $("#show_bank").hide();
            $("#show_cash").hide();
        } else {
            $("#show_cash").hide();
            $("#show_bank").hide();
        }
        $('.select2').select2({
            dropdownParent: $('.select2-dropdownParent')
        });
    })();

    function getMethod(){
        var optionValue = $("#payment_type option:selected").val();
        if (optionValue == 'cash') {
            $("#show_cash").show();
            $("#show_bank").hide();
        } else if (optionValue == 'bank') {
            $("#show_bank").show();
            $("#show_cash").hide();
        } else if (optionValue == 'order') {
            $("#show_bank").hide();
            $("#show_cash").hide();
        } else {
            $("#show_cash").hide();
            $("#show_bank").hide();
        }
        $('.select2').select2({
            dropdownParent: $('.select2-dropdownParent')
        });
    }
</script>


