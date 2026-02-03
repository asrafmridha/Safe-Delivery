<div id="sideNavs" class="sideBar_wrapper sideBar_close">
    <!-- sideBar close -->
    <button id="sideBarClose" type="button" class="sideBar_close_btn float-right">x</button>
    <!-- sideBar nav-->
    <nav class="sideBar_nav">
        <!-- sideBar logo -->
        {{--        <a class="logo d-block text-center" href="{{route('dashboard')}}">{{$st['name']['value']}}</a>--}}
        <a class="logo d-block text-center" href="{{route('dashboard')}}">{{auth()->user()->name}}</a>
        <!-- sideBar content -->
        <ul class="navbar-nav">
            <!-- sideBar item -->
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? ' active' : '' }}" href="{{route('dashboard')}}"><i
                        class="fa fa-fw fa-user-circle text-success"></i>
                    <span class="mx-2">Dashboard</span></a>
            </li>
            {{--            @if(check_permission(['transaction']))--}}
            {{--                <li class="nav-item">--}}
            {{--                    <a class="nav-link {{ request()->is('transaction') || request()->is('clients/*') ? ' active' : '' }}"--}}
            {{--                       href="{{route('transaction.create')}}"><i--}}
            {{--                            class="fa fa-fw fa-money-bill-alt text-warning"></i>--}}
            {{--                        <span class="mx-2">Easy Transaction</span></a>--}}
            {{--                </li>--}}
            {{--            @endif--}}
            @if(check_permission(['transaction list','transaction create']))
                <li class="nav-item">
                    <a class="nav-link {{request()->is('transactions/*') || request()->is('transactions') || request()->is('accounts') || request()->is('accounts/*')  ? ' active' : '' }}"
                       data-toggle="collapse" href="#collapseTransaction" role="button" aria-expanded="false"
                       aria-controls="collapseTransaction">
                        <i class="fa fa-fw fa-users text-danger"></i>
                        <span class="mx-2">Transaction</span>
                        <i class="fas fa-angle-double-right float-right mt-1"></i>
                    </a>
                    <div
                        class="collapse {{ request()->is('transactions') || request()->is('accounts') || request()->is('accounts/*') || request()->is('transactions/*') ? ' show' : '' }}"
                        id="collapseTransaction">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                @if(check_permission('transaction create'))
                                    <li class="nav-item {{request()->is('transactions/create')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.create')}}">Transaction Create</a></li>
                                @endif
                                @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction')}}">All Transaction</a></li>
                                @endif
                                @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/internal')||request()->is('transactions/internal/filter')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.internal')}}">Internal Transaction</a></li>
                                @endif
                                @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/client')||request()->is('transactions/client/filter')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.client')}}">Client Transaction</a></li>
                                @endif
                                @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/other')||request()->is('transactions/other/filter')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.other')}}">Other Transaction</a></li>
                                @endif
                                @if(check_permission('transaction my-list'))
                                    <li class="nav-item {{request()->is('transactions/my-list')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.myList')}}">My Transaction</a></li>
                                @endif
                                @if(check_permission(['account list']))
                                    <li class="nav-item  {{request()->is('accounts') || request()->is('accounts/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('account')}}">Accounts</a></li>
                                @endif
                                {{--@if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/pending')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.pending')}}">Pending Transaction
                                            <span
                                                class="badge badge-warning badge-pill">{{$sidebarData['totalPending']}}</span>
                                        </a>
                                    </li>
                                @endif--}}
                               {{-- @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/order')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.order')}}">Ordered Transaction
                                            <span
                                                class="badge badge-primary badge-pill">{{$sidebarData['totalOrder']}}</span>
                                        </a>
                                    </li>
                                @endif--}}
                                {{--@if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/approved')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.approved')}}">Approved Transaction
                                            <span
                                                class="badge badge-success badge-pill">{{$sidebarData['totalApproved']}}</span>
                                        </a></li>
                                @endif--}}
                               {{-- @if(check_permission('transaction list'))
                                    <li class="nav-item {{request()->is('transactions/rejected')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('transaction.rejected')}}">Rejected Transaction
                                            <span
                                                class="badge badge-danger badge-pill">{{$sidebarData['totalRejected']}}</span>
                                        </a></li>
                                @endif--}}
                            </ul>
                        </div>
                    </div>
                </li>
            @endif
            @if(check_permission(['client list']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('clients') || request()->is('clients/*') ? ' active' : '' }}"
                       href="{{route('client')}}"><i
                            class="fa fa-fw fa-handshake text-info"></i>
                        <span class="mx-2">Client</span></a>
                </li>
            @endif
            @if(check_permission('transaction list'))
                <li class="nav-item {{ request()->is('transaction/pending') ? ' item-active' : '' }}" >
                    <a class="nav-link "
                       href="{{route('transaction.pending')}}">
                        <i class="fa fa-fast-backward text-warning mr-1"></i>
                        Pending<span class="badge badge-warning badge-pill mx-1" id="totalPending">{{$sidebarData['totalPending']}}</span>
                    </a>
                </li>
            @endif
            @if(check_permission('transaction list'))
                <li class="nav-item {{request()->is('transaction/order')?'item-active':''}}">
                    <a class="nav-link"
                       href="{{route('transaction.order')}}">
                        <i class="fa fa-fast-backward text-primary mr-1"></i>Ordered
                        <span class="badge badge-primary badge-pill mx-1" id="totalOrder">{{$sidebarData['totalOrder']}}</span>
                    </a>
                </li>
            @endif
            @if(check_permission('transaction list'))
                <li class="nav-item {{request()->is('transaction/approved') ?'item-active':''}}">
                    <a class="nav-link"
                       href="{{route('transaction.approved')}}">
                        <i class="fa fa-fast-backward text-info mr-1"></i>Approved
                        <span class="badge badge-info badge-pill mx-1" id="totalApproved">{{$sidebarData['totalApproved']}}</span>
                    </a>
                </li>
            @endif
            @if(check_permission(['user list','role list','stuff list']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('users')|| request()->is('users/*')||request()->is('stuffs') || request()->is('stuffs/*')|| request()->is('roles/*')||request()->is('roles') ? ' active' : '' }}"
                       data-toggle="collapse" href="#collapseUser" role="button" aria-expanded="false"
                       aria-controls="collapseUser">
                        <i class="fa fa-fw fa-users text-danger"></i>
                        <span class="mx-2">Users</span>
                        <i class="fas fa-angle-double-right float-right mt-1"></i>
                    </a>
                    <div
                        class="collapse {{ request()->is('users')|| request()->is('users/*')||request()->is('stuffs') || request()->is('stuffs/*')|| request()->is('roles/*')||request()->is('roles') ? ' show' : '' }}"
                        id="collapseUser">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                @if(check_permission('user list'))
                                    <li class="nav-item {{request()->is('users')|| request()->is('users/*')?'item-active':''}}">
                                        <a class="nav-link"
                                           href="{{route('user')}}">User</a></li>
                                @endif
                                @if(check_permission('role list'))
                                    <li class="nav-item {{request()->is('roles') || request()->is('roles/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('role')}}">Role</a></li>
                                @endif
                                @if(check_permission('stuff list'))
                                    <li class="nav-item {{request()->is('stuffs') || request()->is('stuffs/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('stuff')}}">Manage Stuff</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            @endif
            @if(check_permission(['ledger client']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ledger/*') ? ' active' : '' }}"
                       data-toggle="collapse"
                       href="#collapseLedger" role="button" aria-expanded="false"
                       aria-controls="collapseLedger">
                        <i class="fa fa-fw fa-list text-primary"></i>
                        <span class="mx-2">Ledger</span>
                        <i class="fas fa-angle-double-right float-right mt-1"></i>
                    </a>
                    <div
                        class="collapse {{  request()->is('ledger/*') ? ' show' : '' }}"
                        id="collapseLedger">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                @if(check_permission(['ledger client']))
                                    <li class="nav-item  {{request()->is('ledger/client') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('ledger.client')}}">Client Ledger</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            @endif
            @if(check_permission(['report']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reports/*') ? ' active' : '' }}"
                       data-toggle="collapse"
                       href="#collapseReport" role="button" aria-expanded="false"
                       aria-controls="collapseReport">
                        <i class="fa fa-fw fa-paper-plane text-success"></i>
                        <span class="mx-2">Reports</span>
                        <i class="fas fa-angle-double-right float-right mt-1"></i>
                    </a>
                    <div
                        class="collapse {{  request()->is('reports/*') ? ' show' : '' }}"
                        id="collapseReport">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                @if(check_permission(['report']))
                                    <li class="nav-item  {{request()->is('reports/currency') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('report.currency')}}">Currency</a></li>
                                @endif
                                @if(check_permission(['report']))
                                    <li class="nav-item  {{request()->is('reports/transaction') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('report.transaction')}}">Transaction</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            @endif
            @if(check_permission(['basic setting','country list']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings') || request()->is('users/profile') || request()->is('countries') || request()->is('countries/*')  ? ' active' : '' }}"
                       data-toggle="collapse"
                       href="#collapseSetting" role="button" aria-expanded="false"
                       aria-controls="collapseSetting">
                        <i class="fa fa-fw fa-tools text-primary"></i>
                        <span class="mx-2">Settings</span>
                        <i class="fas fa-angle-double-right float-right mt-1"></i>
                    </a>
                    {{--<div class="collapse {{ request()->is('settings') ? ' show' : '' }}" id="collapseSetting">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                @if(check_permission(['basic setting']))
                                    <li class="nav-item  {{request()->is('settings') || request()->is('settings/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('setting.basic')}}">Basic Setting</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>--}}
                    <div
                        class="collapse {{ request()->is('countries') || request()->is('countries/*') || request()->is('users/profile') || request()->is('currencies') || request()->is('currencies/*') ? ' show' : '' }}"
                        id="collapseSetting">
                        <div class="card bg-light rounded-0">
                            <ul class="navbar-nav">
                                <li class="nav-item  {{request()->is('users/profile') ?'item-active':''}}">
                                    <a class="nav-link" href="{{route('user.profile')}}">Profile Update</a></li>
                                @if(check_permission(['country list']))
                                    <li class="nav-item  {{request()->is('countries') || request()->is('countries/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('country')}}">Country</a></li>
                                @endif
                                @if(check_permission(['currency list']))
                                    <li class="nav-item  {{request()->is('currencies') || request()->is('currencies/*') ?'item-active':''}}">
                                        <a class="nav-link" href="{{route('currency')}}">Currency</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </li>
            @endif
        </ul>
    </nav>
</div>
