@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rider List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Rider List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if($branchUser->branch->riders->count() > 0)
                        <fieldset>
                            <legend>Riders List</legend>
                            <button class="btn btn-primary mb-3 mr-3 float-right" type="button" id="printBtn">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <table class="table table-style table-bordered">
                                <tr>
                                    <th style="width: 10%"> #</th>
                                    <th style="width: 30%"> Name</th>
                                    <td style="width: 30%"> Address </td>
                                    <td style="width: 30%">  Contact Number</td>
                                </tr>
                                @foreach($branchUser->branch->riders as $rider)
                                <tr>
                                    <td >{{ $loop->iteration }} </th>
                                    <td >{{ $rider->name }} </th>
                                    <td >{{ $rider->address }} </td>
                                    <td >{{ $rider->contact_number }} </td>
                                </tr>
                                @endforeach

                            </table>
                        </fieldset>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection

@push('style_css')
    <style>
        .table-style td, .table-style th {
            padding: .1rem !important;
        }
    </style>
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function(){
            $(document).on('click', '#printBtn', function(){
                $.ajax({
                    type: 'GET',
                    url: '{!! route('branch.printRiderListByBranch') !!}',
                    data: {},
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href,"_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
    </script>
@endpush
