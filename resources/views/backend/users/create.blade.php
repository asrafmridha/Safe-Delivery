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
                            <li class="breadcrumb-item"><a href="{{route('user')}}" class="breadcrumb-link">Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Create User</legend>
        <form action="{{route('user.create')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               id="name" value="{{old('name')}}"
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
                               id="email" value="{{old('email')}}"
                               placeholder="Enter email">
                        @error('email')
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
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" value="{{old('phone')}}"
                               placeholder="Enter phone">
                        @error('phone')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation"
                               class="form-control @error('designation') is-invalid @enderror"
                               id="designation" value="{{old('designation')}}"
                               placeholder="Enter designation">
                        @error('designation')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="phone">Select User Type</label>
                    <select id="user_role" name="user_role"
                            class="form-control @error('user_role') is-invalid @enderror">
                        <option value="stuff" {{old('user_role') == 'stuff'?'selected': ''}}>
                            Stuff
                        </option>
{{--                        <option value="user" {{old('user_role') == 'user'?'selected': ''}}>User</option>--}}
                        <option value="super_admin" {{old('user_role') == 'super_admin'?'selected': ''}}>
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
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                  id="address">{{old('address')}}</textarea>
                        @error('address')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </fieldset>
@endsection
@section('script')

@endsection
@section('style')

@endsection
