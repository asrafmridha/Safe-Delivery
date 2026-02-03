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
                            <li class="breadcrumb-item"><a href="{{route('internal.transaction')}}" class="breadcrumb-link">Internal Transaction
                                    </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Internal Transaction Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Edit Internal Transaction</legend>
        <form action="{{route('internal.transaction.edit',$data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                       id="date" value="{{date('Y-m-d',strtotime($data->date))}}"
                                       placeholder="Enter date">
                                @error('date')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <label for="to_user_id">To</label>
                            <select id="to_user_id" name="to_user_id"
                                    class="form-control select2 @error('to_user_id') is-invalid @enderror">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$data->to_user_id == $user->id ? 'selected': ''}}>
                                        {{$user->name." (".$user->designation.")"}}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_user_id')
                            <div class="text-danger font-italic">
                                <p><i class="fa fa-exclamation-circle"></i> {{$message}}</p>
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                       id="amount" value="{{$data->amount}}"
                                       placeholder="Enter amount">
                                @error('amount')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remarks">remarks</label>
                                <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror"
                                       id="remarks" value="{{$data->remarks}}"
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="0" {{$data->status==0?"selected":""}}>Pending</option>
                                    <option value="1" {{$data->status==1?"selected":""}}>Approved</option>
                                    <option value="2" {{$data->status==2?"selected":""}}>Rejected</option>
                                </select>
                                @error('details')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 float-right">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
@endsection

