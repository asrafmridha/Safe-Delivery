<div class="col-md-4" id="client_div">
    <label for="client_id">Select Client</label>
    <select id="client_id" name="client_id"
            class="form-control select2 @error('client_id') is-invalid @enderror">
        <option value="">Select Client</option>
        @foreach($clients as $client)
            <option value="{{$client->id}}" {{old('client_id') == $client->id ? 'selected': ''}}>
                {{$client->name.' - (Country: '.$client->country->name.') - '.$client->phone}}
            </option>
        @endforeach
    </select>
    @error('client_id')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
<div class="col-md-4">
    <label for="currency_id">Select Currency</label>
    <select id="currency_id" name="currency_id"
            class="form-control select2 @error('country_id') is-invalid @enderror">
        <option value="">Select Currency</option>
        @foreach($currencies as $currency)
            <option
                value="{{$currency->id}}" {{old('currency_id') == $currency->id ? 'selected': ''}}>
                {{$currency->name.' - '.$currency->code}}</option>
        @endforeach
    </select>
    @error('currency_id')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
<div class="col-md-4">
    <label for="transaction_type">Select Transaction Type</label>
    <select id="transaction_type" name="transaction_type"
            class="form-control @error('transaction_type') is-invalid @enderror">
        <option value="debit" {{old('transaction_type') == 'debit' ? 'selected': ''}}>Debit</option>
        <option value="credit" {{old('transaction_type') == 'credit' ? 'selected': ''}}>Credit</option>
    </select>
    @error('transaction_type')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
               id="date" value="{{old('date')??date('Y-m-d')}}"
               placeholder="Enter date">
        @error('date')
        <p class="text-danger">{{$message}}</p>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
               id="remarks" value="{{old('remarks')}}"
               placeholder="Enter remarks">
        @error('remarks')
        <p class="text-danger">{{$message}}</p>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
               id="amount" value="{{old('amount')??0}}" step="any"
               placeholder="Enter amount">
        @error('amount')
        <p class="text-danger">{{$message}}</p>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="rate">Rate</label>
        <input type="number" name="rate" class="form-control @error('rate') is-invalid @enderror"
               id="rate" value="{{old('rate')??0}}" step="any"
               placeholder="Enter rate">
        @error('rate')
        <p class="text-danger">{{$message}}</p>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="bdt_amount">BDT Amount </label>
        <input type="number" name="bdt_amount"
               class="form-control @error('bdt_amount') is-invalid @enderror"
               id="bdt_amount" value="{{old('bdt_amount')??0}}" step="any"
               placeholder="Enter BDT Amount" readonly>
        @error('bdt_amount')
        <p class="text-danger">{{$message}}</p>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <label for="status">Select Status</label>
    <select id="status" name="status"
            class="form-control @error('status') is-invalid @enderror">
        <option value="">Select Status</option>
        <option value="order" {{old('status') == 'order' ? 'selected': ''}}>Order</option>
        <option value="current" {{old('status') == 'current' ? 'selected': ''}}>Current</option>
    </select>
    @error('status')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
<div class="col-md-4">
    <label for="payment_method_id">Select Payment Method</label>
    <select id="payment_method_id" name="payment_method_id"
            class="form-control @error('payment_method_id') is-invalid @enderror">
        <option value="">Select Payment Method</option>
        @foreach($paymentMethods as $paymentMethod)
            <option value="{{$paymentMethod->id}}" {{old('payment_method_id') == $paymentMethod->name ? 'selected': ''}}>{{$paymentMethod->name}}</option>
        @endforeach
    </select>
    @error('payment_method_id')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
<div class="col-md-4">
    <label for="attachment">Add Attachment</label>
    <input type="file" id="attachment" name="attachment"
           class="form-control @error('attachment') is-invalid @enderror">
    @error('attachment')
    <div class="text-danger font-italic">
        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
    </div>
    @enderror
</div>
