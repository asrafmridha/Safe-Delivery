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
                            <li class="breadcrumb-item"><a href="{{route('user')}}" class="breadcrumb-link"> Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User Edit</li>
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
                        <h5>Edit User</h5>
                        <hr>
                        <form action="{{route('user.edit',$user->id)}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" value="{{$user->name}}"
                                                   placeholder="Enter name">
                                            @error('name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" value="{{$user->email}}"
                                                   placeholder="Enter email">
                                            @error('email')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" value="{{$user->phone}}"
                                                   placeholder="Enter phone">
                                            @error('phone')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   placeholder="Enter password">
                                            @error('password')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pin">Pin</label>
                                            <input type="text" name="pin" class="form-control @error('pin') is-invalid @enderror"
                                                   id="pin" value="{{$user->pin}}"
                                                   placeholder="Enter Pin">
                                            @error('pin')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="designation">Designation</label>
                                            <input type="text" name="designation"
                                                   class="form-control @error('designation') is-invalid @enderror"
                                                   id="designation" value="{{$user->designation}}"
                                                   placeholder="Enter designation">
                                            @error('designation')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_role">Select User Type</label>
                                        <select id="user_role" name="user_role"
                                                class="form-control @error('user_role') is-invalid @enderror">
                                            <option value="stuff" {{$user->user_role == 'stuff'?'selected': ''}}>
                                                Stuff
                                            </option>
{{--                                            <option value="user" {{$user->user_role == 'user'?'selected': ''}}>User</option>--}}
                                            <option value="user" {{$user->user_role == 'super_admin'?'selected': ''}}>
                                                Super Admin
                                            </option>
                                        </select>
                                        @error('user_role')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status">Select User Type</label>
                                        <select id="status" name="status"
                                                class="form-control @error('status') is-invalid @enderror">
                                            <option value="1" {{$user->status == 1?'selected': ''}}>
                                                Active
                                            </option>
                                            <option value="0" {{$user->status == 0?'selected': ''}}>
                                                Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                        <div class="text-danger font-italic">
                                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                                      id="address">{{$user->address}}</textarea>
                                            @error('address')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
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
