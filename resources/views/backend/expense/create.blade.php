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
                            <li class="breadcrumb-item"><a href="{{route('expense')}}" class="breadcrumb-link">Expense</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Expense create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Create expense</legend>
        <form action="{{route('expense.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-5" >
                            <label for="expense_head_id">Expense Head</label>
                            <select id="expense_head_id" name="expense_head_id"
                                    class="form-control select2 @error('expense_head_id') is-invalid @enderror">
                                <option value="">Select Expense Head</option>
                                @foreach($expenseHeads as $expenseHead)
                                    <option value="{{$expenseHead->id}}" {{old('expense_head_id') == $expenseHead->id ? 'selected': ''}}>
                                        {{$expenseHead->title}}
                                    </option>
                                @endforeach
                            </select>
                            @error('expense_head_id')
                            <div class="text-danger font-italic">
                                <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                       id="amount" value="{{old('amount')}}"
                                       placeholder="Enter amount">
                                @error('amount')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="remarks">remarks</label>
                                <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                                       id="remarks" value="{{old('remarks')}}"
                                       placeholder="Enter remarks">
                                @error('remarks')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
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
                        <button type="submit" class="btn btn-primary mt-3 float-right">Save</button>
                    </div>
                </div>
            </div>

        </form>
    </fieldset>
@endsection

