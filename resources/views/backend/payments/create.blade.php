@extends('layouts.backend')
@section('main')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light rounded-circle"><i
                                            class="fa fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('payment')}}"
                                                           class="breadcrumb-link">Payment</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Payment create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Create Payment</legend>
        <form action="{{route('payment.create')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <label for="client_id">From</label>
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
                        <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
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
                <div class="col-md-8">
                    <label for="supplier_id">To</label>
                    <select id="supplier_id" name="supplier_id"
                            class="form-control select2 @error('supplier_id') is-invalid @enderror">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{$client->id}}" {{old('supplier_id') == $client->id ? 'selected': ''}}>
                                {{$client->name.' - (Country: '.$client->country->name.') - '.$client->phone}}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                    <div class="text-danger font-italic">
                        <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
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
                    <label for="payment_method_id">Select Payment Method</label>
                    <select id="payment_method_id" name="payment_method_id"
                            class="form-control @error('payment_method_id') is-invalid @enderror">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $paymentMethod)
                            <option
                                value="{{$paymentMethod->id}}" {{old('payment_method_id') == $paymentMethod->name ? 'selected': ''}}>{{$paymentMethod->name}}</option>
                        @endforeach
                    </select>
                    @error('payment_method_id')
                    <div class="text-danger font-italic">
                        <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="attachment">Add Attachment</label>
                    <input type="file" id="attachment" name="attachment"
                           class="form-control @error('attachment') is-invalid @enderror">
                    @error('attachment')
                    <div class="text-danger font-italic">
                        <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_representative">From Representative</label>
                        <input type="text" name="client_representative"
                               class="form-control @error('client_representative') is-invalid @enderror"
                               id="client_representative" value="{{old('client_representative')}}"
                               placeholder="Enter from representative">
                        @error('client_representative')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_representative">To Representative</label>
                        <input type="text" name="supplier_representative"
                               class="form-control @error('supplier_representative') is-invalid @enderror"
                               id="supplier_representative" value="{{old('supplier_representative')}}"
                               placeholder="Enter to representative">
                        @error('supplier_representative')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </fieldset>

@endsection
