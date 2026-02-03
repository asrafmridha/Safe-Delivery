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
                            <li class="breadcrumb-item"><a href="{{route('expense.head')}}" class="breadcrumb-link">Expense Head</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Expense Head create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Create expense head</legend>
        <form action="{{route('expense.head.create')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               id="title" value="{{old('title')}}"
                               placeholder="Enter title">
                        @error('title')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="details">Details</label>
                        <input type="text" name="details" class="form-control @error('details') is-invalid @enderror"
                               id="details" value="{{old('details')}}"
                               placeholder="Enter details">
                        @error('details')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 float-right">Save</button>
        </form>
    </fieldset>
@endsection

