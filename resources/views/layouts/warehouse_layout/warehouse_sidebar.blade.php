<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('warehouse.home') }}" class="brand-link">
        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
            alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image"
            style="opacity: .8">
        <br>
        {{--<span class="brand-text font-weight-light">--}}
            {{--{{ session()->get('company_name') ?? config('app.name') }}--}}
        {{--</span>--}}
    </a>


    <div class="sidebar">

        <div class="user-panel mt-3 mb-3">

            <div class="d-flex">
                @if(!empty(auth()->guard('warehouse')->user()->photo))
                    <div class="image">
                        <img src="{{ asset('uploads/warehouseUser/' . auth()->guard('warehouse')->user()->image) }} " class="img-thumbnail elevation-2 bg-success" alt="Warehouse Photo">
                    </div>
                @else
                    <div class="image">
                        <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2" alt="Branch Photo">
                    </div>
                @endif
                <div class="info">
                    <a href="{{ route('warehouse.home') }}" class="d-block">
                        {{ auth()->guard('warehouse')->user()->name }}
                    </a>
                </div>
            </div>

            <div class="text-center">
                <span style="font-size: 18px; font-weight:600;">{{ auth()->guard('warehouse')->user()->warehouse->name }}</span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('branch.home') }}" class="nav-link {{ $main_menu == 'home' ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}" class="nav-link " target="_blank">
                        <i class="fas fa-globe fa-lg text-success"></i>
                        <p>
                            Website
                        </p>
                    </a>
                </li>

                 <li class="nav-item">
                    <a href="{{ route('warehouse.profile') }}" class="nav-link {{ $main_menu == 'profile' ? 'active' : '' }}" >
                        <i class="fas fa-tags fa-lg text-success"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'booking' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'booking' ? 'active' : '' }}">
                        <i class="fas fa-box-open fa-lg text-success"></i>
                        <p>
                            Traditional Parcel Booking
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('warehouse.bookingParcel.index') }}"
                               class="nav-link {{ $child_menu == 'bookingParcellist' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Booking Parcel List </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('warehouse.operationBookingParcel.bookingParcelOperation') }}"
                               class="nav-link {{ $child_menu == 'bookingParcelOperation' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Booking Parcel Operation </p>
                            </a>
                        </li>
                    </ul>
                </li>




                <li class="nav-item" style="margin-top: 20px">
                    <a href="{{ route('warehouse.logout') }}" class="nav-link ">
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
