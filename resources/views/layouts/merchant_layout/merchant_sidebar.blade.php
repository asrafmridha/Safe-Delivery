<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:black">
    <a href="{{ route('merchant.home') }}" class="brand-link">
        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
            alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image"
            style="opacity: .8">
        <br>
        {{--<span class="brand-text font-weight-light">--}}
            {{--{{ session()->get('company_name') ?? config('app.name') }}--}}
        {{--</span>--}}
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 mb-3 text-center">
            @if (!empty(auth()->guard('merchant')->user()->image))
                <div class="image">
                    <img src="{{ asset('uploads/merchant/' . auth()->guard('merchant')->user()->image) }} " class="img-thumbnail elevation-2" alt="Merchant Photo">
                </div>

            @else
                <div class="image">
                    <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2" alt="Merchant Photo">
                </div>
            @endif
            <br>
            <div class="info">
                <a href="{{ route('merchant.home') }}" class="d-block">
                    {{ auth()->guard('merchant')->user()->company_name }} <br>
                   <span> {{ auth()->guard('merchant')->user()->name }}</span>
                </a>
            </div>

        </div>

        <a href="{{ route('merchant.parcel.add') }}"><button class="btn btn-block btn-lg btn-add-parcel">Add New Parcel</button></a>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('merchant.home') }}" class="nav-link {{ $main_menu == 'home' ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                



                <!--<li class="nav-item">-->
                <!--    <a href="{{ route('merchant.shop.index') }}" class="nav-link {{ $child_menu == 'shop-list' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-store fa-lg text-success"></i>-->
                <!--        <p>Shop List </p>-->
                <!--    </a>-->
                <!--</li>-->

                @if (auth()->guard('merchant')->user()->branch_id)

                    <!--<li class="nav-item">-->
                    <!--    <a href="{{ route('merchant.parcel.add') }}" class="nav-link {{ $child_menu == 'addParcel' ? 'active' : '' }}">-->
                    <!--        <i class="fa fa-plus-square fa-lg text-success"></i>-->
                    <!--        <p>Add Parcel </p>-->
                    <!--    </a>-->
                    <!--</li>-->
                    
                    
                    
                    
                     <li class="nav-item">
                        <a href="{{ route('merchant.parcel.merchantBulkParcelImport') }}" class="nav-link {{ $child_menu == 'bulkaddParcel' ? 'active' : '' }}">
                            <i class="fas fa-cart-plus fa-lg text-success"></i>
                            <p>Bulk Entry </p>
                        </a>
                    </li>
                    
                    
                    <li class="nav-item has-treeview {{ $main_menu == 'request' ? 'menu-open' : '' }} ">
                        <a href="#" class="nav-link {{ $main_menu == 'request' ? 'active' : '' }}">
                            <i class="fas fa-box-open fa-lg text-success"></i>
                            <p>
                               Pickup Request
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('merchant.parcel.parcelPickupRequest') }}" class="nav-link {{ $child_menu == 'parcelPickupRequest' ? 'active' : '' }}">
                                    <i class="fas fa-tags"></i>
                                    <p>Pickup Request </p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('merchant.parcel.parcelPickupRequestList') }}" class="nav-link {{ $child_menu == 'parcelPickupRequestList' ? 'active' : '' }}">
                                    <i class="fas fa-tags"></i>
                                    <p> Pickup Request List </p>
                                </a>
                            </li>
                        </ul>
                      
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('merchant.parcel.list') }}" class="nav-link {{ $child_menu == 'parcelList' ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list fa-lg text-success"></i>
                            <p>All Parcel </p>
                        </a>
                    </li>

                    {{--<li class="nav-item has-treeview {{ $main_menu == 'parcel' ? 'menu-open' : '' }} ">--}}
                        {{--<a href="#" class="nav-link {{ $main_menu == 'parcel' ? 'active' : '' }}">--}}
                            {{--<i class="fas fa-box-open fa-lg text-success"></i>--}}
                            {{--<p>--}}
                                {{--Parcel--}}
                                {{--<i class="right fas fa-angle-left"></i>--}}
                            {{--</p>--}}
                        {{--</a>--}}

                        {{--<ul class="nav nav-treeview">--}}
                            {{--<li class="nav-item">--}}
                                {{--<a href="{{ route('merchant.parcel.add') }}" class="nav-link {{ $child_menu == 'addParcel' ? 'active' : '' }}">--}}
                                    {{--<i class="fas fa-tags"></i>--}}
                                    {{--<p>Add Parcel </p>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                        {{--<ul class="nav nav-treeview">--}}
                            {{--<li class="nav-item">--}}
                                {{--<a href="{{ route('merchant.parcel.list') }}" class="nav-link {{ $child_menu == 'parcelList' ? 'active' : '' }}">--}}
                                    {{--<i class="fas fa-tags"></i>--}}
                                    {{--<p>Parcel List </p>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    <!--<li class="nav-item has-treeview {{ $main_menu == 'account' ? 'menu-open' : '' }} ">-->
                    <!--    <a href="#" class="nav-link {{ $main_menu == 'account' ? 'active' : '' }}">-->
                    <!--        <i class="fas fa-file-invoice-dollar fa-lg text-success"></i>-->
                    <!--        <p>-->
                    <!--            Account-->
                    <!--            <i class="right fas fa-angle-left"></i>-->
                    <!--        </p>-->
                    <!--    </a>-->
                    <!--    <ul class="nav nav-treeview">-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="{{ route('merchant.parcel.parcelPaymentRequestList') }}" class="nav-link {{ $child_menu == 'parcelPaymentRequestList' ? 'active' : '' }}">-->
                    <!--                <i class="fas fa-tags"></i>-->
                    <!--                <p> Payment Request List </p>-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--    <ul class="nav nav-treeview">-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="{{ route('merchant.account.deliveryPaymentList') }}" class="nav-link {{ $child_menu == 'deliveryPaymentList' ? 'active' : '' }}">-->
                    <!--                <i class="fas fa-clipboard-list"></i>-->
                    <!--                <p>Delivery Payment List </p>-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--    <ul class="nav nav-treeview">-->
                    <!--        <li class="nav-item">-->
                    <!--            <a href="{{ route('merchant.account.parcelPaymentList') }}" class="nav-link {{ $child_menu == 'parcelPaymentList' ? 'active' : '' }}">-->
                    <!--                <i class="fas fa-clipboard-list"></i>-->
                    <!--                <p> Delivery Parcel List </p>-->
                    <!--            </a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</li>-->
               


                <li class="nav-item">
                    <a href="{{ route('merchant.account.deliveryPaymentList') }}" class="nav-link {{ $child_menu == 'deliveryPaymentList' ? 'active' : '' }}" >
                        <i class="fas fa-file-invoice-dollar fa-lg text-success"></i>
                        <p>
                            Payment List
                        </p>
                    </a>
                </li>
                
                 @endif
                
                <li class="nav-item">
                    <a href="{{ route('merchant.orderTracking') }}" class="nav-link {{ $child_menu == 'orderTracking' ? 'active' : '' }}" >
                        <i class="fas fa-search-location fa-lg text-success"></i>
                        <p>
                            Order Tracking
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.coverageArea') }}" class="nav-link {{ $child_menu == 'coverageArea' ? 'active' : '' }}" >
                        <i class="fas fa-map-marked-alt fa-lg text-success"></i>
                        <p>
                            Coverage Area
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('merchant.serviceCharge') }}" class="nav-link {{ $child_menu == 'serviceCharge' ? 'active' : '' }}" >
                        <i class="fas fa-hand-holding-usd fa-lg text-success"></i>
                        <p>
                            Weight Charge
                        </p>
                    </a>
                </li>
                
                
                <li class="nav-item">
                    <a href="{{ route('merchant.profile') }}" class="nav-link {{ $main_menu == 'profile' ? 'active' : '' }}" >
                        <i class="far fa-address-card fa-lg text-success"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <!--<li class="nav-item">-->
                <!--    <a href="{{ route('frontend.home') }}" class="nav-link " target="_blank">-->
                <!--        <i class="fas fa-globe fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Website-->
                <!--        </p>-->
                <!--    </a>-->
                <!--</li>-->


                <li class="nav-item" style="margin-top: 20px">
                    <a href="{{ route('merchant.logout') }}" class="nav-link ">
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
