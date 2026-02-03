@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vehicle.index') }}">Item</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Item </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.item.update', $item->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                        @csrf
                                        <input type="hidden" name="_method" value="PATCH">
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="item_cat_id"> Item category </label>
                                                <select name="item_cat_id" id="item_cat_id" class="form-control select2" style="width: 100%">
                                                    <option value="0">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="item_name">Item Name</label>
                                                <input type="text" name="item_name" id="item_name" value="{{ $item->item_name ?? old('item_name') }}" class="form-control" placeholder="Item Name" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="unit_id"> Unit </label>
                                                <select name="unit_id" id="unit_id" class="form-control select2" style="width: 100%">
                                                    <option value="0">Select Unit</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="od_rate">OD Rate</label>
                                                <input type="number" step="any" name="od_rate" id="od_rate" value="{{ $item->od_rate ??  old('od_rate') }}" class="form-control" placeholder="OD Rate" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="hd_rate">HD Rate</label>
                                                <input type="number" step="any" name="hd_rate" id="hd_rate" value="{{ $item->hd_rate ?? old('hd_rate') }}" class="form-control" placeholder="HD Rate" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="transit_od">Transit OD</label>
                                                <input type="number" step="any" name="transit_od" id="transit_od" value="{{ $item->transit_od ?? old('transit_od') }}" class="form-control" placeholder="Transit OD" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="transit_hd">Transit HD</label>
                                                <input type="number" step="any" name="transit_hd" id="transit_hd" value="{{ $item->transit_hd ?? old('transit_hd') }}" class="form-control" placeholder="Transit HD" required>
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
        $("#item_cat_id").val('{{ $item->item_cat_id }}');
        $("#unit_id").val('{{ $item->unit_id }}');

        function createForm(){
            let category_id = $('#item_cat_id').val();
            if(category_id == '0'){
                toastr.error("Please Select Category..");
                return false;
            }
            let unit_id = $('#unit_id').val();
            if(unit_id == '0'){
                toastr.error("Please Select Unit..");
                return false;
            }
        }
    </script>
@endpush
