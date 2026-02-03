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
                            <li class="breadcrumb-item"><a href="{{route('payment.method')}}" class="breadcrumb-link">Payment
                                    Method</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Payment Method Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Edit Payment Method</legend>
        <form action="{{route('payment.method.edit',$data->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
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
                        <label for="details">Details</label>
                        <input type="text" name="details" class="form-control @error('details') is-invalid @enderror"
                               id="details" value="{{old('name')??$data->details}}"
                               placeholder="Enter details">
                        @error('details')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="details">Details</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{$data->status==1?"selected":""}}>Active</option>
                            <option value="0" {{$data->status==0?"selected":""}}>Inactive</option>
                        </select>
                        @error('details')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 float-right">Update</button>
        </form>
    </fieldset>
@endsection

