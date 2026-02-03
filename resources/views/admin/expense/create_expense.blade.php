@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Account Entry</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Account</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Create New Entry </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-offset-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('admin.expense.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="type">Type </label>
                                            <select name="type" id="type" class="form-control select2" style="width: 100%">
                                                <option value="0">Select Type</option>
                                                <option value="1">Expense</option>
                                                <option value="2">Income</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="expense_head_id"> Head </label>
                                            <select name="expense_head_id" id="expense_head_id" class="form-control select2" style="width: 100%">
                                                <option value="0">Select Head</option>
                                                @foreach ($heads as $head)
                                                    <option value="{{ $head->id }}">{{ $head->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input type="date" name="date" id="date" value="{{ old('date') }}" class="form-control" placeholder="Date" required>
                                        </div>

                                       

                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <input type="text" step="any" name="note" id="note" value="{{ old('note') }}" class="form-control" placeholder="Note" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="text" step="amount" name="amount" id="amount" value="{{ old('amount') }}" class="form-control" placeholder="Amount" required>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('style_css')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script>
    function createForm(){
        let expense_head_id = $('#expense_head_id').val();
        if(expense_head_id == '0'){
            toastr.error("Please Select Expense Head..");
            return false;
        }
        
    }

  </script>
@endpush
