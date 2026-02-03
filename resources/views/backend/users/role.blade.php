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
                            <li class="breadcrumb-item active" aria-current="page">Role List</li>
                            <!-- Button trigger modal -->
                            @if(check_permission('role create'))
                                <button type="button" class="btn btn-primary ml-auto" data-toggle="modal"
                                        data-target="#roleCreate">
                                    Create Role
                                </button>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Roles</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive" style="white-space: nowrap">
                <table id="example" class="table table-striped table-bordered table-responsive-sm" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $key=>$role)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$role->name}}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    {{$permission->name}},
                                @endforeach
                            </td>
                            <td>
                                @if(check_permission('role edit'))
                                    <a href="{{route('role.edit',$role->id)}}"
                                       class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(check_permission('role delete'))
                                    <a href="{{route('role.delete',$role->id)}}"
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to delete?');"><i
                                            class="fa fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>




    <!-- Modal -->
    <div class="modal fade" id="roleCreate" tabindex="-1" role="dialog" aria-labelledby="roleCreateLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Create Role</legend>
                    {{--   <div class="modal-header">
                           --}}{{--                    <h5 class="modal-title" id="roleCreateLabel">Create Role</h5>--}}{{--
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>--}}
                    <div class="modal-body">
                        <form action="{{route('role.create')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                       placeholder="Enter role name">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
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
