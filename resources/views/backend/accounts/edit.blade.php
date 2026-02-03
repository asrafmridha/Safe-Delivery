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
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('account')}}"
                                                           class="breadcrumb-link">Account</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Account Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Edit Account</legend>
        <form action="{{route('account.edit',$data->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="country_id">Select Country <span class="text-danger">*</span></label>
                    <select id="country_id" name="country_id"
                            class="form-control select2 @error('country_id') is-invalid @enderror">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option
                                value="{{$country->id}}" {{old('country_id')??$data->country_id == $country->id ? 'selected': ''}}>
                                {{$country->name.' - '.$country->code}}</option>
                        @endforeach
                    </select>
                    @error('country_id')
                    <div class="text-danger font-italic">
                        <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                    </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               id="bank_name" value="{{old('bank_name')??$data->bank_name}}"
                               placeholder="Enter bank name">
                        @error('bank_name')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank_branch">Bank Branch</label>
                        <input type="text" name="bank_branch"
                               class="form-control @error('bank_branch') is-invalid @enderror"
                               id="bank_branch" value="{{old('bank_branch')??$data->bank_branch}}"
                               placeholder="Enter bank branch">
                        @error('bank_branch')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account_name">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control @error('account_name') is-invalid @enderror"
                               id="account_name" value="{{old('account_name')??$data->account_name}}"
                               placeholder="Enter account name">
                        @error('account_name')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account_number">Account number <span class="text-danger">*</span></label>
                        <input type="text" name="account_number"
                               class="form-control @error('account_number') is-invalid @enderror"
                               id="account_number" value="{{old('account_number')??$data->account_number}}"
                               placeholder="Enter account number">
                        @error('account_number')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="route">Route</label>
                        <input type="text" name="route"
                               class="form-control @error('route') is-invalid @enderror"
                               id="route" value="{{old('route')??$data->route}}"
                               placeholder="Enter route">
                        @error('route')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="beneficiary_address">Beneficiary Address</label>
                        <input type="text" name="beneficiary_address"
                               class="form-control @error('beneficiary_address') is-invalid @enderror"
                               id="beneficiary_address" value="{{old('beneficiary_address')??$data->beneficiary_address}}"
                               placeholder="Enter beneficiary address">
                        @error('beneficiary_address')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="beneficiary_city">Beneficiary City</label>
                        <input type="text" name="beneficiary_city"
                               class="form-control @error('beneficiary_city') is-invalid @enderror"
                               id="beneficiary_city" value="{{old('beneficiary_city')??$data->beneficiary_city}}"
                               placeholder="Enter beneficiary city">
                        @error('beneficiary_city')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="beneficiary_swift_code">Beneficiary Swift Code</label>
                        <input type="text" name="beneficiary_swift_code"
                               class="form-control @error('beneficiary_swift_code') is-invalid @enderror"
                               id="beneficiary_swift_code" value="{{old('beneficiary_swift_code')??$data->beneficiary_swift_code}}"
                               placeholder="Enter beneficiary swift code">
                        @error('beneficiary_swift_code')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status">Select Status</label>
                        <select name="status" id="status"   class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{(old('status')??$data->status) == 1?"selected":''}}>Active</option>
                            <option value="0" {{(old('status')??$data->status) == 0?"selected":''}}>Inactive</option>
                        </select>
                        @error('status')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="float-right">
                <div class="input-group my-3">
                    <input type="text" class="form-control @error('pin') is-invalid @enderror" id="pin" name="pin" placeholder="Your Pin">
                    <div class="input-group-append">
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

