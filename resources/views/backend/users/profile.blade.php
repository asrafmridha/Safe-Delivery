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
                            <li class="breadcrumb-item active" aria-current="page">Profile Update</li>
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
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Profile Update</legend>
                            <div class="row px-5">
                                <div class="col-md-6">
                                    <p><strong>Name: </strong>{{$user->name}}</p>
                                    <p><strong>Email: </strong>{{$user->email}}</p>
                                    <p><strong>Phone Number: </strong>{{$user->phone??"..."}}</p>
                                    <p><strong>Address: </strong>{{$user->address??"..."}}</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p><strong>Designation: </strong>{{$user->designation??"..."}}</p>
                                    <p><strong>User Role: </strong>{{$user->user_role}}</p>
                                    <p><strong>Status: </strong>
                                        @if($user->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Change Password</legend>
                                <form action="{{route('user.changePassword')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="old_password">Old Password</label>
                                                    <input type="password" name="old_password"
                                                           class="form-control @error('old_password') is-invalid @enderror"
                                                           id="old_password"
                                                           placeholder="Enter old password">
                                                    @error('old_password')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password">New Password</label>
                                                    <input type="password" name="password"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           id="password"
                                                           placeholder="Enter new password">
                                                    @error('password')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="password_confirmation">Confirm New Password</label>
                                                    <input type="password" name="password_confirmation"
                                                           class="form-control @error('password') is-invalid @enderror"
                                                           id="password_confirmation"
                                                           placeholder="Confirm new password">
                                                    @error('password')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </form>
                            </fieldset>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Change Pin</legend>
                                <form action="{{route('user.changePin')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="old_pin">Old Pin</label>
                                                    <input type="text" name="old_pin"
                                                           class="form-control @error('old_pin') is-invalid @enderror"
                                                           id="old_pin"
                                                           placeholder="Enter old pin">
                                                    @error('old_pin')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="pin">New Pin</label>
                                                    <input type="text" name="pin"
                                                           class="form-control @error('pin') is-invalid @enderror"
                                                           id="pin"
                                                           placeholder="Enter new pin">
                                                    @error('pin')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="pin_confirmation">Confirm New Pin</label>
                                                    <input type="text" name="pin_confirmation"
                                                           class="form-control @error('pin') is-invalid @enderror"
                                                           id="pin_confirmation"
                                                           placeholder="Confirm new pin">
                                                    @error('pin')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </form>
                            </fieldset>
                        </fieldset>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
