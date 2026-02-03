@extends('layouts.admin_layout.admin_layout')

@section('content')
  <style>
        legend.scheduler-border {
            width:inherit; /* Or auto */
            padding:0 10px; /* To give a bit of padding on the left and right */
            border-bottom:none;
        }
  </style>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Income Statement</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Income Statement</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <fieldset class="m-b-10">
                    <legend class="scheduler-border">Find the report by month</legend>
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="month">Month</label>
                            <input type="month" value="<?= date('Y-m'); ?>" name="month" id="month" class="form-control"/>
                        </div>
                        <div class="col-sm-3">
                            <label for="month">&nbsp;</label> <br/>
                            <button class="btn btn-success btn-lg" onclick="search_income_statement()"> <i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </fieldset>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Income Statement Details</h3>
                    </div>
                    <div class="card-body text-center">
                        <b>......</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>


@endsection

@push('script_js')
    <script type="text/javascript">
        function search_income_statement(){
            var img = '<img src="https://icon-library.com/images/loading-icon-animated-gif/loading-icon-animated-gif-19.jpg" class="image-fluid" />';
            $('.card-body').html(img);
            //return 0;
            var month          = $('#month').val();
            var url             = "{{ route('admin.select-income-statement') }}";
            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    month: month,
                    _token : "{{ csrf_token() }}"
                },
                url       : url,
                success   : function(response){
                    $('.card-body').html(response);
                }
            })
        }
        
        
    </script>
@endpush

