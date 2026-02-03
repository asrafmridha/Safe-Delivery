<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:#024180">
    <a href="{{ route('admin.home') }}" class="brand-link">
        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
            alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <br>
        {{--<span class="brand-text font-weight-light">--}}
            {{--{{ session()->get('company_name') ?? config('app.name') }}--}}
        {{--</span>--}}
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 text-center">
            @if(!empty(auth()->guard('admin')->user()->photo))
                <div class="image">
                    <img src="{{ asset('uploads/admin/' . auth()->guard('admin')->user()->photo) }} " class="img-thumbnail elevation-2" alt="Admin Photo">
                </div>
            @else
                <div class="image">
                    <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2" alt="Admin Photo">
                </div>
            @endif
            <br>
            <div class="info">
                <a href="{{ route('admin.home') }}" class="d-block">
                    {{ auth()->guard('admin')->user()->name }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ $main_menu == 'home' ? 'active' : '' }}">
                        <i class="fas fa-home fa-lg "></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('admin.profile') }}"
                       class="nav-link {{ $main_menu == 'profile' ? 'active' : '' }}">
                        <i class="fas fa-tags text-success"></i>
                        <p>Profile </p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'parcel' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'parcel' ? 'active' : '' }}">
                        <i class="fas fa-box-open fa-lg text-success"></i>
                        <p>
                            Parcel
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{--<li class="nav-item">--}}
                        {{--<a href="{{ route('admin.parcel.list') }}" class="nav-link {{ $child_menu == 'parcelList' ? 'active' : '' }}">--}}
                        {{--<i class="fas fa-tags"></i>--}}
                        {{--<p>Parcel List </p>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.allParcelList') }}" class="nav-link {{ $child_menu == 'allParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>All Parcel List </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.report') }}"
                       class="nav-link {{ $child_menu == 'report' ? 'active' : '' }}">
                        <i class="fas fa-tags text-success"></i>
                        <p>Report</p>
                    </a>
                </li>
                
                <li class="nav-item has-treeview {{ $main_menu == 'request' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'request' ? 'active' : '' }}">
                        <i class="fa fa-sign-language fa-lg text-success"></i>
                        <p>
                            Request
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.parcelPaymentRequestList') }}" class="nav-link {{ $child_menu == 'parcelPaymentRequestList' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p> Payment Request List </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.merchantPaymentDeliveryList') }}" class="nav-link {{ $child_menu == 'parcelPaymentGenerateList' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <p> Payment Generate List </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'branch-payment' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'branch-payment' ? 'active' : '' }}">
                        <i class="fa fa-university fa-lg text-success"></i>
                        <p>
                            Branch Payment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.branchDeliveryPaymentList') }}" class="nav-link {{ $child_menu == 'branchDeliveryPaymentList' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p>Pending Delivery Payment</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.branchDeliveryReceivePaymentList') }}" class="nav-link {{ $child_menu == 'receivePaymentList' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <p>Received Payment List</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.branchDeliveryPaymentStatement') }}" class="nav-link {{ $child_menu == 'branchPaymentStatement' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Statement</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item has-treeview {{ $main_menu == 'merchant-payment' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'merchant-payment' ? 'active' : '' }}">
                        <i class="fa fa-users fa-lg text-success"></i>
                        <p>
                            Merchant Payment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.merchantPaymentDeliveryGenerate') }}" class="nav-link {{ $child_menu == 'merchantPaymentDeliveryGenerate' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p>Pending Delivery Payment </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.merchantPaymentDeliveryList') }}" class="nav-link {{ $child_menu == 'merchantPaymentDeliveryList' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <p>Delivery Payment List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.merchantPaymentDeliveryStatement') }}" class="nav-link {{ $child_menu == 'merchantPaymentStatement' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Statement</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ $main_menu == 'salary' || $main_menu == 'team'  ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'salary' || $main_menu == 'team' ? 'active' : '' }}">
                        <i class="fa fa-database fa-lg text-success"></i>
                        <p>
                            Salary
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.staff.index') }}" class="nav-link {{ $child_menu == 'staff_list' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Staff List </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.staffPaymentList') }}" class="nav-link {{ $child_menu == 'staff_payment_list' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Staff Payment </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.rider.index') }}"
                               class="nav-link {{ $child_menu == 'rider' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Rider</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.rider.payment') }}"
                               class="nav-link {{ $child_menu == 'rider-payment' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Rider Payment</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.account.staffPaymentStatement') }}" class="nav-link {{ $child_menu == 'staff_payment_statement' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Statement</p>
                            </a>
                        </li>
                    </ul>

                </li>

                {{--<li class="nav-item">--}}
                    {{--<a href="{{ '#' }}"--}}
                       {{--class="nav-link {{ $main_menu == 'totalStatementList' ? 'active' : '' }}">--}}
                        {{--<i class="fas fa-tags text-success"></i>--}}
                        {{--<p>Total Statement List </p>--}}
                    {{--</a>--}}
                {{--</li>--}}
                
                
                
                                <li class="nav-item has-treeview {{ $main_menu == 'expenses' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'expenses' ? 'active' : '' }}">
                        <i class="fa fa-credit-card fa-lg text-danger"></i>
                        <p>
                            Income/Expenses
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.expenses') }}" class="nav-link {{ $child_menu == 'expenses' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Income/Expense List </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.expense-head') }}" class="nav-link {{ $child_menu == 'expenseHead' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Income/Expense Head </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.receipt-payment') }}" class="nav-link {{ $child_menu == 'receipt_payment' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Receipt & Payments </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.income-statement') }}" class="nav-link {{ $child_menu == 'income_statement' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Income Statement </p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    
                    
                    <a href="{{ route('admin.merchant.index') }}"
                       class="nav-link {{ $child_menu == 'merchant' ? 'active' : '' }}">
                        <i class="fas fa-tags text-success"></i>
                        <p>Merchant List </p>
                    </a>
                </li>





                

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.branch.index') }}"
                       class="nav-link {{ $child_menu == 'branch' ? 'active' : '' }}">
                        <i class="fas fa-tags text-success"></i>
                        <p>Branch List </p>
                    </a>
                </li> --}}

                <!--<li class="nav-item has-treeview {{ $main_menu == 'booking' ? 'menu-open' : '' }} ">-->
                <!--    <a href="#" class="nav-link {{ $main_menu == 'booking' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-box-open fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Traditional Parcel Booking-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.bookingParcel.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcellist' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel List </p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->

                <!--<li class="nav-item has-treeview {{ $main_menu == 'traditional_parcel' ? 'menu-open' : '' }} ">-->
                <!--    <a href="#" class="nav-link {{ $main_menu == 'traditional_parcel' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-box-open fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Traditional Parcel Payment-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview">-->

                        <!--<li class="nav-item">-->
                        <!--    <a href="{{ route('admin.account.traditional.branchParcelPaymentList') }}"-->
                        <!--       class="nav-link {{ $child_menu == 'bookingParcelPaymentList' ? 'active' : '' }}">-->
                        <!--        <i class="fas fa-tags"></i>-->
                        <!--        <p>Parcel Payment List</p>-->
                        <!--    </a>-->
                        <!--</li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.account.traditional.branchBookingParcelPaymentReport') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcelPaymentReport' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Parcel Payment Report</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        {{--<li class="nav-item">--}}-->
                <!--            {{--<a href="{{ route('branch.bookingParcelPayment.paymentForwardToAccounts') }}"--}}-->
                <!--               {{--class="nav-link {{ $child_menu == 'paymentForwardToAccounts' ? 'active' : '' }}">--}}-->
                <!--                {{--<i class="fas fa-tags"></i>--}}-->
                <!--                {{--<p>Payment Forward to Accounts</p>--}}-->
                <!--            {{--</a>--}}-->
                <!--        {{--</li>--}}-->
                <!--    </ul>-->
                <!--</li>-->

                <li class="nav-item" style="margin-top: 20px">
                    <a href="{{ route('admin.logout') }}" class="nav-link ">
                        <i class="fas fa-power-off text-danger fa-lg"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
