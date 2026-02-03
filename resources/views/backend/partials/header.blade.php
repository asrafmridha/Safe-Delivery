<header>
    <!-- nav wrapper -->
    <div class="nav_wrapper fixed-top">
        <!-- fixed navbar -->
        <nav class="navbar py-3">
            <!-- content area -->
            <div class="container-fluid">
                <!-- sideBar open button -->
                <button id="sideBarOpen" type="button" class="collapse_btn">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- navbar right content -->
                <div class="right_content text-right">
                    <!-- notification -->
                    <div class="btn-group" id="pending-notification">
                    </div>
                    <div class="btn-group" id="order-notification">
                    </div>
                    <div class="btn-group" id="approved-notification">
                    </div>

                    {{--<div class="btn-group">
                        <button class="dropdown-toggle notification" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-bell"></i> <sup class="bg-danger px-1  rounded-circle">9</sup>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xl">
                            <div class="dropdown_heading">
                                <p>You have <strong class="text-primary">13</strong> notifications.</p>
                                <!-- list group -->
                                <a href="#" class="list-group-item active">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="img-fluid rounded-circle" src="vendor/img/avata/team-4.jpg"
                                                 alt="">
                                        </div>
                                        <div class="col-9">
                                            <div class="clearfix">
                                                <div class="float-left">
                                                    <h6>John Doe</h6>
                                                </div>
                                                <div class="float-right">
                                                    <p class="text-muted text-sm">3 hrs ago</p>
                                                </div>
                                            </div>
                                            <p>Hello world how are you?</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- list group -->
                                <a href="#" class="list-group-item">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="img-fluid rounded-circle" src="vendor/img/avata/team-4.jpg"
                                                 alt="">
                                        </div>
                                        <div class="col-9">
                                            <div class="clearfix">
                                                <div class="float-left">
                                                    <h6>John Doe</h6>
                                                </div>
                                                <div class="float-right">
                                                    <p class="text-muted text-sm">3 hrs ago</p>
                                                </div>
                                            </div>
                                            <p>Hello world how are you?</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- list group -->
                                <a href="#" class="list-group-item">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="img-fluid rounded-circle" src="vendor/img/avata/team-4.jpg"
                                                 alt="">
                                        </div>
                                        <div class="col-9">
                                            <div class="clearfix">
                                                <div class="float-left">
                                                    <h6>John Doe</h6>
                                                </div>
                                                <div class="float-right">
                                                    <p class="text-muted text-sm">3 hrs ago</p>
                                                </div>
                                            </div>
                                            <p>Hello world how are you?</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- list group -->
                                <a href="#" class="list-group-item">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="img-fluid rounded-circle" src="vendor/img/avata/team-4.jpg"
                                                 alt="">
                                        </div>
                                        <div class="col-9">
                                            <div class="clearfix">
                                                <div class="float-left">
                                                    <h6>John Doe</h6>
                                                </div>
                                                <div class="float-right">
                                                    <p class="text-muted text-sm">3 hrs ago</p>
                                                </div>
                                            </div>
                                            <p>Hello world how are you?</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- view all -->
                                <a href="#" class="view_all">view all</a>
                            </div>
                        </div>
                    </div>--}}
                    <!-- message -->
                    @if(auth()->user())
                        @php
                            $auth_balance = Illuminate\Support\Facades\DB::table('transactions')
                                    ->join('users', 'users.id', '=', 'transactions.created_by')
                                    ->select([
                                        Illuminate\Support\Facades\DB::raw("SUM( IF(transaction_type = 'debit', bdt_amount, 0)) as debit "),
                                        Illuminate\Support\Facades\DB::raw("SUM( IF(transaction_type = 'credit', bdt_amount, 0)) as credit "),
                                    ])
                                    ->whereIn('transactions.status', [1, 4])
                                    ->where('users.id', auth()->user()->id)
                                    ->first();
                            $auth_internal_balance = Illuminate\Support\Facades\DB::table('transactions')
                                    ->join('users', 'users.id', '=', 'transactions.user_id')
                                    ->select([
                                        Illuminate\Support\Facades\DB::raw("SUM( IF(transaction_type = 'credit', bdt_amount, 0)) as debit "),
                                        Illuminate\Support\Facades\DB::raw("SUM( IF(transaction_type = 'debit', bdt_amount, 0)) as credit "),
                                    ])
                                    ->whereIn('transactions.status', [1, 4])
                                    ->where('users.id', auth()->user()->id)
                                    ->first();
                            $auth_balance_final = ($auth_balance->credit+$auth_internal_balance->credit)-($auth_balance->debit+$auth_internal_balance->debit);
                        @endphp
                        <div class="btn-group mx-5 text-light">
                            <p>Your Balance: <span class="{{$auth_balance_final<=0?"text-warning":''}}">{{number_format($auth_balance_final,2)}} BDT</span>
                            </p>
                        </div>
                    @endif
                    <!-- user profile link-->
                    <div class="btn-group">
                        <a href="{{route('logout')}}">
                            <i class="fas fa-sign-out-alt text-danger"></i> <span class="mx-2 " style="color: white">Logout</span>
                        </a>
                        {{-- <button class="dropdown-toggle user" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <img width="35px" class="img-fluid rounded-circle" src="vendor/img/avata/team-4.jpg" alt=""> <span class="mx-2">{{auth()->user()->name}}</span>
                         </button>--}}
                        {{--<div class="dropdown-menu dropdown-menu-right profile_link">
                            <div class="pro_body">
                                <p>welcome</p>
                                <ul>
                                    <li><a href="#">
                                            <i class="fas fa-user text-success"></i> <span class="mx-2">My Profile</span>
                                        </a></li>
                                    <li><a href="#">
                                            <i class="fas fa-cogs text-primary"></i> <span class="mx-2">Setting</span>
                                        </a></li>
                                    <li><a href="#">
                                            <i class="fas fa-calendar-week text-info"></i> <span class="mx-2">Activity</span>
                                        </a></li>
                                    <li><a href="#">
                                            <i class="far fa-life-ring text-warning"></i> <span class="mx-2">Support</span>
                                        </a></li>
                                    <hr>
                                    <li><a href="{{route('logout')}}">
                                            <i class="fas fa-sign-out-alt text-danger"></i> <span class="mx-2">Logout</span>
                                        </a></li>
                                </ul>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
