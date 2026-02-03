<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('rider.home') }}" class="brand-link">
        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
            alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image"
            style="opacity: .8">
        <br>
        {{--<span class="brand-text font-weight-light">--}}
            {{--{{ session()->get('company_name') ?? config('app.name') }}--}}
        {{--</span>--}}
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 text-center">
            @if (!empty(auth()->guard('rider')->user()->image))
                <div class="image">
                    <img src="{{ asset('uploads/rider/' . auth()->guard('rider')->user()->image) }} " class="img-thumbnail elevation-2 bg-success" alt="rider Photo">
                </div>
            @else
                <div class="image">
                    <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2" alt="Merchant Photo">
                </div>
            @endif
            <br>
            <div class="info">
                <a href="{{ route('rider.home') }}" class="d-block">
                    {{ auth()->guard('rider')->user()->name }}
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('rider.home') }}" class="nav-link {{ $main_menu == 'home' ? 'active' : '' }}">
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
                    <a href="{{ route('rider.profile') }}" class="nav-link " >
                        <i class="fas fa-user fa-lg text-success"></i>
                        <p>
                            Profile
                        </p>
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
                        <li class="nav-item">
                            <a href="{{ route('rider.parcel.pickupParcelList') }}" class="nav-link {{ $child_menu == 'pickupParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Pickup Parcel List </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rider.parcel.deliveryParcelList') }}" class="nav-link {{ $child_menu == 'deliveryParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Delivery Parcel List </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rider.parcel.deliveryCompleteParcelList') }}" class="nav-link {{ $child_menu == 'deliveryCompleteParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Delivery Complete Parcel List </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rider.parcel.returnParcelList') }}" class="nav-link {{ $child_menu == 'returnParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Return Parcel List </p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item has-treeview {{ $main_menu == 'payment' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'payment' ? 'active' : '' }}">
                        <i class="fas fa-credit-card fa-lg text-success"></i>
                        <p>
                            Payment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rider.payment.collectionParcelList') }}" class="nav-link {{ $child_menu == 'collectionParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Collection Parcel List </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rider.payment.paidAmountParcelList') }}" class="nav-link {{ $child_menu == 'paidAmountParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Paid Amount Parcel List </p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item" style="margin-top: 20px">
                    <a href="{{ route('rider.logout') }}" class="nav-link ">
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
