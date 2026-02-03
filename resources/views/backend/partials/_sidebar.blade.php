<div id="sidebar" class="sidebar responsive ace-save-state sidebar-fixed sidebar-scroll">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {
        }
    </script>

    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </button>

            <button class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </button>

            <button class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </button>

            <button class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </button>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div><!-- /.sidebar-shortcuts -->

    <!-- .nav-list -->
    <ul class="nav nav-list">
        <li class="{{ request()->is('/') ? ' active' : '' }}">
            <a href="{{route('dashboard')}}">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>
        @if(check_permission(['transaction list','transaction create']))
            <li class="{{request()->is('transactions/*') || request()->is('transactions') || request()->is('accounts') || request()->is('accounts/*')  ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-flag "></i>
                    <span class="menu-text">Transaction</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('transaction create'))
                        <li class="{{request()->is('transactions/create')?'active':''}}">
                            <a href="{{route('transaction.create')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Transaction Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('transaction list'))
                        <li class="{{request()->is('transactions')?'active':''}}">
                            <a href="{{route('transaction')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                All Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    {{--@if(check_permission('transaction list'))
                        <li class="{{request()->is('transactions/internal') || request()->is('transactions/internal/filter')?'active':''}}">
                            <a href="{{route('transaction.internal')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Internal Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('transaction list'))
                        <li class="{{request()->is('transactions/client')||request()->is('transactions/client/filter')?'active':''}}">
                            <a href="{{route('transaction.client')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Client Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('transaction list'))
                        <li class="{{request()->is('transactions/other')||request()->is('transactions/other/filter')?'active':''}}">
                            <a href="{{route('transaction.other')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Other Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('transaction my-list'))
                        <li class="{{request()->is('transactions/my-list')?'active':''}}">
                            <a href="{{route('transaction.myList')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                My Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('account list'))
                        <li class="{{request()->is('accounts') || request()->is('accounts/*')?'active':''}}">
                            <a href="{{route('account')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Accounts
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif--}}
                </ul>
            </li>
        @endif
        @if(check_permission(['internal transaction list','internal transaction create']))
            <li class="{{request()->is('internal_transactions/*') || request()->is('internal_transactions')  ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-flag "></i>
                    <span class="menu-text">Internal Transaction</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('internal transaction create'))
                        <li class="{{request()->is('internal_transactions/create')?'active':''}}">
                            <a href="{{route('internal.transaction.create')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('internal transaction list'))
                        <li class="{{request()->is('transactions')?'active':''}}">
                            <a href="{{route('internal.transaction')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                               List
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if(check_permission(['payment list','payment create']))
            <li class="{{request()->is('payments/*') || request()->is('payments')  ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-credit-card "></i>
                    <span class="menu-text">Payments</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('payment create'))
                        <li class="{{request()->is('payments/create')?'active':''}}">
                            <a href="{{route('payment.create')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Payment Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('transaction list'))
                        <li class="{{request()->is('payments')?'active':''}}">
                            <a href="{{route('payment')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                All Payment
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        @if(check_permission(['expense head list']))
            <li class="{{request()->is('expense-heads/*') || request()->is('expense-heads')||request()->is('expenses/*') || request()->is('expenses')  ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-credit-card "></i>
                    <span class="menu-text">Expenses</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('expense create'))
                        <li class="{{request()->is('expenses')||request()->is('expenses/*')?'active':''}}">
                            <a href="{{route('expense')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Expense
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('expense head list'))
                        <li class="{{request()->is('expense-heads')||request()->is('expense-heads/*')?'active':''}}">
                            <a href="{{route('expense.head')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Expense Head
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                </ul>
            </li>
        @endif

        @if(check_permission(['client list']))
            <li class="{{ request()->is('clients') || request()->is('clients/*') ? ' active' : '' }}">
                <a href="{{route('client')}}">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text"> Client </span>
                </a>

                <b class="arrow"></b>
            </li>
        @endif
        @if(check_permission(['transaction list','payment list','expense list']))
            <li class="{{request()->is('transaction/pending') || request()->is('payment/pending')|| request()->is('expense/pending')  ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-flag "></i>
                    <span class="menu-text">Pending <span class="badge badge-warning badge-pill mx-1"
                                                          id="finalPending" >{{$sidebarData['finalPending']}}</span></span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('transaction list'))
                        <li class="{{request()->is('transaction/pending')?'active':''}}">
                            <a href="{{route('transaction.pending')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Pending Transaction <span class="badge badge-warning badge-pill mx-1"
                                                          id="totalPending">{{$sidebarData['totalPending']}}</span>
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('payment list'))
                        <li class="{{request()->is('payment/pending')?'active':''}}">
                            <a href="{{route('payment.pending')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Pending Payment
                                <span class="badge badge-danger badge-pill mx-1" id="totalPendingPayment">{{$sidebarData['totalPendingPayment']}}</span>
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('expense list'))
                        <li class="{{request()->is('expense/pending')?'active':''}}">
                            <a href="{{route('expense.pending')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Pending Expense
                                <span class="badge badge-warning badge-pill mx-1" id="totalPendingExpense">{{$sidebarData['totalPendingExpense']}}</span>
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('internal transaction list'))
                        <li class="{{request()->is('internal_transaction/pending')?'active':''}}">
                            <a href="{{route('internal.transaction.pending')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Pending internal transaction
                                <span class="badge badge-danger badge-pill mx-1" id="totalPendingInternalTransaction">{{$sidebarData['totalPendingInternalTransaction']}}</span>
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        {{--@if(check_permission('transaction list'))
            <li class="{{ request()->is('transaction/pending') ? ' active' : '' }}">
                <a href="{{route('transaction.pending')}}">
                    <i class="menu-icon fa fa-flag"></i>
                    <span class="menu-text"> Pending <span class="badge badge-warning badge-pill mx-1"
                                                           id="totalPending">{{$sidebarData['totalPending']}}</span></span>
                </a>

                <b class="arrow"></b>
            </li>
        @endif--}}
        @if(check_permission('transaction list'))
            <li class="{{ request()->is('transaction/order') ? ' active' : '' }}">
                <a href="{{route('transaction.order')}}">
                    <i class="menu-icon fa fa-flag"></i>
                    <span class="menu-text"> Ordered <span class="badge badge-primary badge-pill mx-1"
                                                           id="totalOrder">{{$sidebarData['totalOrder']}}</span></span>
                </a>

                <b class="arrow"></b>
            </li>
        @endif
        @if(check_permission('transaction list'))
            <li class="{{ request()->is('transaction/approved') ? ' active' : '' }}">
                <a href="{{route('transaction.approved')}}">
                    <i class="menu-icon fa fa-flag"></i>
                    <span class="menu-text"> Approved <span class="badge badge-info badge-pill mx-1"
                                                            id="totalApproved">{{$sidebarData['totalApproved']}}</span></span>
                </a>

                <b class="arrow"></b>
            </li>
        @endif


        @if(check_permission(['user list','role list','stuff list']))
            <li class="{{request()->is('users')|| request()->is('users/*')||request()->is('stuffs') || request()->is('stuffs/*')|| request()->is('roles/*')||request()->is('roles') ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-user "></i>
                    <span class="menu-text">Users</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission('user list'))
                        <li class="{{request()->is('users')|| request()->is('users/*')?'active':''}}">
                            <a href="{{route('user')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                User
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('role list'))
                        <li class="{{request()->is('roles') || request()->is('roles/*')?'active':''}}">
                            <a href="{{route('role')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Role
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission('stuff list'))
                        <li class="{{request()->is('stuffs') || request()->is('stuffs/*')?'active':''}}">
                            <a href="{{route('stuff')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Manage Stuff
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        @if(check_permission(['ledger client','ledger supplier']))
            <li class="{{request()->is('ledger/*') ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-paperclip "></i>
                    <span class="menu-text">Ledger</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    @if(check_permission(['ledger client','ledger supplier']))
                        <li class="{{request()->is('ledger/client')?'active':''}}">
                            <a href="{{route('ledger.client')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Ledger
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    {{--@if(check_permission(['ledger supplier']))
                        <li class="{{request()->is('ledger/supplier')?'active':''}}">
                            <a href="{{route('ledger.supplier')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Supplier Ledger
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif--}}

                </ul>
            </li>
        @endif
        @if(check_permission(['report']))
            <li class="{{request()->is('reports/*') ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-street-view "></i>
                    <span class="menu-text">Reports</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">

                    @if(check_permission(['report']))
                        <li class="{{request()->is('reports/transaction')?'active':''}}">
                            <a href="{{route('report.transaction')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Transaction
                            </a>
                            <b class="arrow"></b>
                        </li>

                        <li class="{{request()->is('reports/payment')?'active':''}}">
                            <a href="{{route('report.payment')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Payment
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="{{request()->is('reports/profit')?'active':''}}">
                            <a href="{{route('report.profit')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Profit
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="{{request()->is('reports/currency')?'active':''}}">
                            <a href="{{route('report.currency')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Currency
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li class="{{request()->is('reports/expense')?'active':''}}">
                            <a href="{{route('report.expense')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Expense
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if(check_permission(['basic setting','country list','payment method list','currency list']))
            <li class="{{ request()->is('settings') || request()->is('users/profile') || request()->is('countries') || request()->is('countries/*') ? ' open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-gears "></i>
                    <span class="menu-text">Settings</span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="{{request()->is('users/profile')?'active':''}}">
                        <a href="{{route('user.profile')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Profile Update
                        </a>
                        <b class="arrow"></b>
                    </li>

                    @if(check_permission(['country list']))
                        <li class="{{request()->is('countries') || request()->is('countries/*')?'active':''}}">
                            <a href="{{route('country')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Country
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission(['currency list']))
                        <li class="{{request()->is('currencies') || request()->is('currencies/*')?'active':''}}">
                            <a href="{{route('currency')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Currency
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if(check_permission(['payment method list']))
                        <li class="{{request()->is('payment-methods') || request()->is('payment-methods/*')?'active':''}}">
                            <a href="{{route('payment.method')}}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Payment Method
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        <li class="">
            <a href="{{route('logout')}}">
                <i class="menu-icon fa fa-power-off"></i>
                <span class="menu-text"> Logout </span>
            </a>

            <b class="arrow"></b>
        </li>
    </ul>

    <!-- /.nav-list -->

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
           data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
