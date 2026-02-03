
@extends('layouts.branch_layout.branch_layout')

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Merchant Bulk Parcel Import</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Bulk Parcel Import</li>
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
                        <h3 class="card-title">Merchant Bulk Parcel Import</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="offset-md-1 col-md-10 ">
                            <div class="card card-primary">
                                <form role="form" action="{{ route('branch.parcel.merchantBulkParcelImport') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                  @csrf
                                    <div class="card-body row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="file">Import File <code>(.csv)</code></label>
                                                <input type="file" name="file" id="file"  class="form-control" required >
                                            </div>
                                            <div class="form-group">
                                                <label for="rider_id"> Rider </label>
                                                <select name="rider_id" id="rider_id" class="form-control select2" style="width: 100%" >
                                                    <option value="0" >Select Rider </option>
                                                    @foreach ($riders as $rider)
                                                        @if($rider->rider_runs->count() == 0)
                                                        <option
                                                            value="{{ $rider->id }}"
                                                            riderContactNumber="{{ $rider->contact_number }}"
                                                            riderAddress="{{ $rider->address }}"
                                                            > {{ $rider->name }} </option>
                                                        @elseif($rider->rider_runs->count() > 0)
                                                            @foreach ($rider->rider_runs as $rider_run)
                                                                @if($rider_run->status == 3 ||  $rider_run->status == 4)
                                                                <option
                                                                value="{{ $rider->id }}"
                                                                riderContactNumber="{{ $rider->contact_number }}"
                                                                riderAddress="{{ $rider->address }}"
                                                                > {{ $rider->name }} </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"  class="form-control" required >
                                            </div>
                                            <div class="form-group">
                                                <label for="note">Note </label>
                                                <textarea name="note" id="note" class="form-control" placeholder="Pickup Rider Run Note "></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table ">
                                                <tr>
                                                    <td colspan="3">
                                                        <a href="{{ asset('format/Branch-Merchant-Bulk-Parcel-Format.csv') }}" class="btn btn-success" download="">
                                                            <i class="fas fa-file-excel"></i> Merchant Bulk Import Format
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Rider Name</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_name">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Rider Contact Number</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_contact_number">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Rider Address</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_address">Not Confirm</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Import Submit</button>
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
    window.onload = function(){
        $('#rider_id').on('change', function(){
            var rider   = $("#rider_id option:selected");
            var rider_id   = rider.val();

            if(rider_id == 0 ){
                $("#view_rider_name").html('Not Confirm');
                $("#view_rider_contact_number").html('Not Confirm');
                $("#view_rider_address").html('Not Confirm');
            } else{
                $("#view_rider_name").html(rider.text());
                $("#view_rider_contact_number").html(rider.attr('riderContactNumber'));
                $("#view_rider_address").html(rider.attr('riderAddress'));
            }
        });
    }

    function createForm(){
        let rider_id = $('#rider_id').val();
        if(rider_id == '0'){
            toastr.error("Please Select Rider ..");
            return false;
        }
    }

  </script>
@endpush

