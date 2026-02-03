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
                            <li class="breadcrumb-item"><a href="{{route('client')}}"
                                                           class="breadcrumb-link">Clients</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Client Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Edit Client</legend>
        <form action="{{route('client.edit',$data->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               id="name" value="{{old('name')??$data->name}}"
                               placeholder="Enter name">
                        @error('name')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" value="{{old('phone')??$data->phone}}"
                               placeholder="Enter Phone Number">
                        @error('phone')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" value="{{old('email')??$data->email}}"
                               placeholder="Enter email">
                        @error('email')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="country_id">Select Country</label>
                    <select id="country_id" name="country_id"
                            class="form-control select2 @error('country_id') is-invalid @enderror">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option
                                value="{{$country->id}}" {{old('country_id')??$data->country_id == $country->id ? 'selected': ''}}>{{$country->name.' - '.$country->code}}</option>
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
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  id="address" placeholder="Enter address">{{old('address')??$data->address}}</textarea>
                        @error('address')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 float-right">Update</button>
        </form>
    </fieldset>
@endsection

