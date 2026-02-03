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
                            <li class="breadcrumb-item"><a href="{{route('stuff')}}" class="breadcrumb-link">Manage
                                    Stuff</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Assign Role</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- form start -->
    <div class="form_section">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 mx-auto">
                <div class="inline_val">
                    <div class="content_section my-4">
                        <form action="{{route('stuff.edit',$user->id)}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    {{--<div class="col-xl-3 col-lg-3 col-md-3 text-lg-right text-md-right">
                                        <label for="name"><span class="font-weight-bold">Name</span><sup
                                                class="text-danger">*</sup></label>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9">
                                        <input type="text" id="name" name="name" value="{{$user->name}}"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>--}}
                                    {{--<div class="col-xl-3 col-lg-3 col-md-3 text-lg-right text-md-right mt-2">
                                        <label for="user_role"><span class="font-weight-bold">User Type</span><sup
                                                class="text-danger">*</sup></label>
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9 mt-2">
                                        <select id="user_role" name="user_role"
                                                class="form-control @error('user_role') is-invalid @enderror">
                                            <option value="stuff" {{$user->user_role == 'stuff'?'selected': ''}}>
                                                Stuff
                                            </option>
                                            <option value="user" {{$user->user_role == 'user'?'selected': ''}}>
                                                User
                                            </option>
                                            <option
                                                value="user" {{$user->user_role == 'super_admin'?'selected': ''}}>
                                                Super Admin
                                            </option>
                                        </select>
                                        @error('user_role')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>--}}
                                </div>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Assign Role To "{{$user->name}}"</legend>
{{--                                    <h4>{{$user->name}}</h4>--}}
                                    <div class="row">
                                        <div class="col-xl-9 col-lg-9 col-md-9 mt-2">
                                            <div class="row">
                                                @foreach($roles as $key=>$role)
                                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                                        <div class="row my-3">
                                                            <div class="col-1 text-right mt-2">
                                                                <label for="role[{{$role->id}}]"><span
                                                                        class="font-weight-bold">{{$key+1 ."."}}</span></label>
                                                            </div>
                                                            <div class="col-3">
                                                                <label class="switch">
                                                                    <input type="checkbox" id="role[{{$role->id}}]"
                                                                           {{$user->hasRole($role->id)?'checked':''}}  name="role[{{$role->id}}]">
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-7 mt-2">
                                                                <label for="role[{{$role->id}}]"><span
                                                                        class="font-weight-bold">{{$role->name}}</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @error('role')
                                                <div class="text-danger font-italic">
                                                    <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 text-lg-right text-md-right">
                                    </div>
                                    <div class="col-xl-9 col-lg-9 col-md-9">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
@section("style")
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
