@extends('layouts.backend')

@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light rounded-circle"><i
                                            class="fa fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('transaction')}}" class="breadcrumb-link">Transaction</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Transaction Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Edit Transaction</legend>
        <form action="{{route('transaction.edit',$transaction->id)}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-5" id="client_div">
                    <label for="supplier_id">Select Supplier</label>
                    <select id="supplier_id" name="supplier_id"
                            class="form-control select2 @error('supplier_id') is-invalid @enderror">
                        <option value="">Select Supplier</option>
                        @foreach($clients as $client)
                            <option value="{{$client->id}}" {{$transaction->supplier_id == $client->id ? 'selected': ''}}>
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
                <div class="col-md-5" id="client_div">
                    <label for="client_id">Select Client</label>
                    <select id="client_id" name="client_id"
                            class="form-control select2 @error('client_id') is-invalid @enderror">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{$client->id}}" {{$transaction->client_id == $client->id ? 'selected': ''}}>
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
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                               id="date" value="{{date('Y-m-d',strtotime($transaction->date))}}"
                               placeholder="Enter date">
                        @error('date')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="currency_id">Select Currency</label>
                    <select id="currency_id" name="currency_id"
                            class="form-control select2 @error('country_id') is-invalid @enderror">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)
                            <option
                                value="{{$currency->id}}" {{$transaction->currency_id == $currency->id ? 'selected': ''}}>
                                {{$currency->name.' - '.$currency->code}}</option>
                        @endforeach
                    </select>
                    @error('currency_id')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                               id="amount" value="{{$transaction->amount ?? 0}}" step="any"
                               placeholder="Enter amount">
                        @error('amount')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="b_rate">Baying Rate</label>
                        <input type="number" name="b_rate" class="form-control @error('b_rate') is-invalid @enderror"
                               id="b_rate" value="{{$transaction->b_rate??0}}" step="any"
                               placeholder="Enter b_rate">
                        @error('b_rate')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="s_rate">Selling Rate</label>
                        <input type="number" name="s_rate" class="form-control @error('s_rate') is-invalid @enderror"
                               id="rate" value="{{$transaction->s_rate??0}}" step="any"
                               placeholder="Enter selling rate">
                        @error('s_rate')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                {{--<div class="col-md-3">
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
                </div>--}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sl">S/L</label>
                        <input type="text" name="sl" class="form-control @error('sl') is-invalid @enderror"
                               id="sl" value="{{$transaction->sl}}"
                               placeholder="Enter s/l">
                        @error('sl')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="remarks">Beneficiary</label>
                        <input type="text" name="beneficiary"
                               class="form-control @error('beneficiary') is-invalid @enderror"
                               id="beneficiary" value="{{$transaction->beneficiary}}"
                               placeholder="Enter beneficiary">
                        @error('beneficiary')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                {{--<div class="col-md-3">
                    <div class="form-group">
                        <label for="v_date">V.Date</label>
                        <input type="date" name="v_date" class="form-control @error('v_date') is-invalid @enderror"
                               id="v_date" value="{{old('v_date')??date('Y-m-d')}}"
                               placeholder="Enter v.date">
                        @error('v_date')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="v_time">V.Time</label>
                        <input type="time" name="v_time" class="form-control @error('v_time') is-invalid @enderror"
                               id="v_time" value="{{old('v_time')??date('H:i')}}"
                               placeholder="Enter v.time">
                        @error('v_time')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>--}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                               id="remarks" value="{{$transaction->remarks}}"
                               placeholder="Enter remarks">
                        @error('remarks')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="attachment">Add Attachment</label>
                    <input type="file" id="attachment" name="attachment"
                           class="form-control @error('attachment') is-invalid @enderror">
                    @error('attachment')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_representative">Supplier Representative</label>
                        <input type="text" name="supplier_representative"
                               class="form-control @error('supplier_representative') is-invalid @enderror"
                               id="supplier_representative" value="{{$transaction->supplier_representative}}"
                               placeholder="Enter supplier representative">
                        @error('supplier_representative')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="client_representative">Client Representative</label>
                        <input type="text" name="client_representative"
                               class="form-control @error('client_representative') is-invalid @enderror"
                               id="client_representative" value="{{$transaction->client_representative}}"
                               placeholder="Enter client representative">
                        @error('client_representative')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class=" col-md-3" style="float: right">
                    <div class="input-group my-3">
                        <input type="text" class="form-control @error('pin') is-invalid @enderror" id="pin" name="pin"
                               placeholder="Your Pin">
                        <div class="input-group-append" style="float: right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                    @error('pin')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
        </form>
    </fieldset>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#payment_type").change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).attr("value");
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
                });
            }).change();
            $(".transaction_checkbox").change(function () {
                if ($("#clientTransaction").is(":checked")) {
                    $("#client_div").show();
                    $("#user_div").hide();
                } else if ($("#internalTransaction").is(":checked")) {
                    $("#user_div").show();
                    $("#client_div").hide();
                } else {
                    $("#client_div").hide();
                    $("#user_div").hide();
                }
            }).change();
        });
        window.onload = function () {


            $('#amount').keyup(function () {
                calculate_bdt_amount();
            });
            $("#amount").on("change", function () {
                calculate_bdt_amount();
            });
            $('#rate').keyup(function () {
                calculate_bdt_amount();
            });
            $("#rate").on("change", function () {
                calculate_bdt_amount();
            });
        }

        function calculate_bdt_amount() {
            let amount = returnNumber($("#amount").val());
            let rate = returnNumber($("#rate").val());
            let bdt_amount = amount * rate;
            $("#bdt_amount").val(bdt_amount.toFixed(2));
        }

        function returnNumber(value) {
            value = parseFloat(value);
            return !isNaN(value) ? value : 0;
        }
    </script>
@endsection
