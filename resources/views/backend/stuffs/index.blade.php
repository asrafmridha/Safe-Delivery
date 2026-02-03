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
                            <li class="breadcrumb-item active" aria-current="page">Manage Stuff</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- data table start -->
    <div class="data_table my-4">
        <div class="content_section table-responsive" style="white-space: nowrap">
            <table id="example" class="table table-striped table-bordered table-responsive-sm" style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key=>$user)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->user_role}}</td>
                        <td>
                            @foreach($user->roles as $role)
                                {{$role->name}},
                            @endforeach
                        </td>
                        <td>
                            @if(check_permission('stuff edit'))
                                <a href="{{route('stuff.edit',$user->id)}}"
                                   class="btn btn-primary"><i class="fa fa-edit"></i></a>
                            @endif
                           {{-- @if(check_permission('stuff delete'))
                                <a href="{{route('stuff.delete',$user->id)}}"
                                   class="btn btn-danger"
                                   onclick="return confirm('Are you sure you want to delete?');"><i
                                        class="fa fa-trash"></i></a>
                            @endif--}}
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- end -->
@endsection
@section('script')
    <!-- data table -->
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/dataTables.bootstrap4.min.js')}}"></script>
@endsection
@section('style')
    <!-- data table -->
    <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">
@endsection
