<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top" style="background:#52ade1 !important;">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="{{route('dashboard')}}" class="navbar-brand">
                <small>
                    {{--                    <img src="https://hrms.fmc.ltd/upload/icon.png " alt="The Franchise Management Company Ltd" height="30px" width="30px">--}}
                  Accounts
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                @if(auth()->user())
                    @php
                      /*  $auth_balance = Illuminate\Support\Facades\DB::table('transactions')
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
                    */

                    @endphp
                    <li class="mx-5 " style="color: #ffffff;margin: 0 10px">
{{--                        <p>Your Balance: <span style="color:{{$auth_balance_final<=0?"red":'#ffffff'}}">{{number_format($auth_balance_final,2)}} BDT</span></p>--}}
                    </li>
                @endif
                <li class="">
                    <p style="margin: 0 5px"><strong>My Balance : </strong>{{auth()->user()->balance}} BDT</p>
                </li>
                <li class="red dropdown-modal" id="pending-notification">
                </li>
                <li class="purple dropdown-modal" id="order-notification">
                </li>
                <li class="green dropdown-modal" id="approved-notification">
                </li>


                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        {{--                        <img class="nav-user-photo" src="https://hrms.fmc.ltd/upload/admin/190910258101757769498.jpg" alt="STITBD"/>--}}
                        <span class="user-info"><small>Welcome,</small>{{auth()->user()->name}}</span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        {{--<li>
                            <a href="#">
                                <i class="ace-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>--}}


                        {{--                        <li class="divider"></li>--}}

                        <li>
                            <a href="{{route('logout')}}">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>
