<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}" class="brand-link">
        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
            alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <br>
        <span class="brand-text font-weight-light">
            {{ session()->get('company_name') ?? config('app.name') }}
        </span>
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @if(!empty(auth()->guard('admin')->user()->photo))
                <div class="image">
                    <img src="{{ asset('uploads/admin/' . auth()->guard('admin')->user()->photo) }} " class="img-circle elevation-2 bg-success" alt="Admin Photo">
                </div>
            @endif
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
                
                
                <li class="nav-item has-treeview {{ $main_menu == 'team' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'team' ? 'active' : '' }}">
                        <i class="fas fa-user-friends text-success" style="color:#034260"></i>
                        <p>
                            Team
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                  
                  

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.merchant.index') }}"
                               class="nav-link {{ $child_menu == 'merchant' ? 'active' : '' }}">
                                <i class="fa fa-user-circle"></i>
                                <p>Merchant</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.rider.index') }}"
                               class="nav-link {{ $child_menu == 'rider' ? 'active' : '' }}">
                                <i class="fas fa-motorcycle"></i>
                                <p>Rider</p>
                            </a>
                        </li>
                    </ul>
                    
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
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.list') }}" class="nav-link {{ $child_menu == 'parcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Parcel List </p>
                            </a>
                        </li>
                        
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.orderTracking') }}"
                               class="nav-link {{ $child_menu == 'orderTracking' ? 'active' : '' }}">
                                <i class="fas fa-map-marker"></i>
                                <p>Order Tracking </p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                
                
                

                <!--<li class="nav-item has-treeview {{ $main_menu == 'account' ? 'menu-open' : '' }} ">-->
                <!--    <a href="#" class="nav-link {{ $main_menu == 'account' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-box-open fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Account-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.account.transportIncomeExpenseGenerate') }}" class="nav-link {{ $child_menu == 'transportIncomeExpenseGenerate' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Transport Income Expense Generate </p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.account.transportIncomeExpenseList') }}" class="nav-link {{ $child_menu == 'transportIncomeExpenseList' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Transport Income Expense List </p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->

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

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.operationBookingParcel.assignVehicleToWarehouse') }}"-->
                <!--               class="nav-link {{ $child_menu == 'assignVehicleToWearhouse' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Assign Vehicle To Wear House </p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.operationBookingParcel.bookingParcelOperation') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcelOperation' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel Operation </p>-->
                <!--            </a>-->
                <!--        </li>-->
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
