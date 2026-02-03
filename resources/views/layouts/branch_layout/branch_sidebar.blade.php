<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:#034260">
    <a href="{{ route('branch.home') }}" class="brand-link">
        <!--<img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"-->
        <!--    alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image"-->
        <!--    style="opacity: .8">-->
            
                         <!--<img src="https://safedeliverycourier.com/public/logo.png" alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image">-->

            </br>
        {{--<span style="text-align:left; font-size: 15px; color:fff;" class="">--}}
            {{--{{ session()->get('company_name') ?? config('app.name') }}--}}
        {{--</span>--}}
    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 mb-3 text-center">
            @if(!empty(auth()->guard('branch')->user()->photo))
                <div class="image">
                    <img src="{{ asset('uploads/branch/' . auth()->guard('branch')->user()->image) }} " class="img-thumbnail elevation-2" alt="Branch Photo">
                </div>
            @else
                <div class="image">
                    <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2" alt="Branch Photo">
                </div>
            @endif
            <br>
            <div class="info">
                <a href="{{ route('branch.home') }}" class="d-block">
                    {{ auth()->guard('branch')->user()->name }} <br>
                    <span>{{ auth()->guard('branch')->user()->branch->name }}</span>
                </a>
            </div>
        </div>

        <a href="{{ route('branch.parcel.add') }}"><button class="btn btn-block btn-lg btn-add-parcel">Add New Parcel</button></a>

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
                    <a href="{{ route('branch.profile') }}" class="nav-link {{ $main_menu == 'profile' ? 'active' : '' }}" >
                        <i class="fas fa-address-card fa-lg text-success"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                
                 <li class="nav-item">
                    <a href="{{ route('branch.parcel.parcelPickupRequestList') }}" class="nav-link {{ $main_menu == 'profile' ? 'active' : '' }}" >
                        <i class="fas fa-tags"></i>
                        <p>
                            Pickup Request List
                        </p>
                    </a>
                </li>
                
            

                <li class="nav-item">
                    <a href="{{ route('branch.parcel.allParcelList') }}" class="nav-link {{ $main_menu == 'allParcel' ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list fa-lg text-success"></i>
                        <p>
                            All Parcel List
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branch.parcel.allRiderParcelList') }}" class="nav-link {{ $main_menu == 'allRiderParcelList' ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list fa-lg text-success"></i>
                        <p>
                            Rider Parcel List
                        </p>
                    </a>
                </li>


                @if(auth('branch')->user()->branch->type == 1)
                <li class="nav-item has-treeview {{ $main_menu == 'pickupParcel' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'pickupParcel' ? 'active' : '' }}">
                        <i class="fas fa-box-open fa-lg text-success"></i>
                        <p>
                            Pickup Parcel
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item" title="Pickup Parcel List">
                            <a href="{{ route('branch.parcel.pickupParcelList') }}" class="nav-link {{ $child_menu == 'pickupParcelList' ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <p>Pickup Parcel List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview"  title="Generate Pickup Rider Run">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.pickupRiderRunGenerate') }}" class="nav-link {{ $child_menu == 'pickupRiderRunGenerate' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-start"></i>
                                <p>Pending </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview" title="Pickup Rider Run List">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.pickupRiderRunList') }}" class="nav-link {{ $child_menu == 'pickupRiderRunList' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p>Processing</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview" title="Delivery Branch Transfer Generate">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryBranchTransferGenerate') }}" class="nav-link {{ $child_menu == 'deliveryBranchTransferGenerate' ? 'active' : '' }}" >
                                <i class="fas fa-tags"></i>
                                <p>Generate Branch Transfer (Trip) </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryBranchTransferList') }}" class="nav-link {{ $child_menu == 'deliveryBranchTransferList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Delivery Branch Transfer List (Trip) </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'deliveryParcel' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'deliveryParcel' ? 'active' : '' }}">
                        <i class="fas fa-cart-arrow-down fa-lg text-success"></i>
                        <p>
                            Delivery Parcel
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryParcelList') }}" class="nav-link {{ $child_menu == 'deliveryParcelList' ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <p>Delivery Parcel List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.rescheduleDeliveryParcelList') }}" class="nav-link {{ $child_menu == 'rescheduleDeliveryParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Reschedule/Pending Parcel List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryRiderRunGenerate') }}" class="nav-link {{ $child_menu == 'deliveryRiderRunGenerate' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-start"></i>
                                <p>Pending</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryRiderRunList') }}" class="nav-link {{ $child_menu == 'deliveryRiderRunList' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p>Processing</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.receivedBranchTransferList') }}" class="nav-link {{ $child_menu == 'receivedBranchTransferList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Received Branch Transfer List (Trip) </p>
                            </a>
                        </li>
                    </ul>

                </li>
                
                @if(auth()->guard('branch')->user()->branch_id == 2)
                        <li class="nav-item has-treeview {{ $main_menu == 'pathaoOrder' ? 'menu-open' : '' }} ">
                            <a href="#" class="nav-link {{ $main_menu == 'pathaoOrder' ? 'active' : '' }}">
                                <i class="fas fa-box fa-lg text-success"></i>
                                <p>
                                    Pathao Order
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('branch.parcel.pathaoOrderGenerate') }}"
                                       class="nav-link {{ $child_menu == 'pathaoOrderGenerate' ? 'active' : '' }}">
                                        <i class="fas fa-tags"></i>
                                        <p>Generate Pathao Order</p>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    @endif

                <li class="nav-item has-treeview {{ $main_menu == 'returnParcel' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'returnParcel' ? 'active' : '' }}">
                        <i class="fas fa-box fa-lg text-success"></i>
                        <p>
                            Return Parcel
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview" title="Return Branch Transfer Generate">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.returnBranchTransferGenerate') }}" class="nav-link {{ $child_menu == 'returnBranchTransferGenerate' ? 'active' : '' }}" >
                                <i class="fas fa-tags"></i>
                                <p>Return Branch Transfer (Trip) </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview" title="Return Branch Transfer List">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.returnBranchTransferList') }}" class="nav-link {{ $child_menu == 'returnBranchTransferList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Return Branch Transfer List (Trip) </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.returnParcelList') }}" class="nav-link {{ $child_menu == 'returnParcelList' ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <p>Return Parcel List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.returnRiderRunGenerate') }}" class="nav-link {{ $child_menu == 'returnRiderRunGenerate' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-start"></i>
                                <p>Pending</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.returnRiderRunList') }}" class="nav-link {{ $child_menu == 'returnRiderRunList' ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                <p>Processing</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.receivedReturnBranchTransferList') }}" class="nav-link {{ $child_menu == 'receivedReturnBranchTransferList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Received Return Transfer List (Trip) </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.completeReturnParcelList') }}" class="nav-link {{ $child_menu == 'completeReturnParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Complete Return Parcel List </p>
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'completeDeliveryParcel' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'completeDeliveryParcel' ? 'active' : '' }}">
                        <i class="fas fa-indent fa-lg text-success"></i>
                        <p>
                            Complete Delivery Payment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.completeDeliveryParcelList') }}" class="nav-link {{ $child_menu == 'completeDeliveryParcelList' ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <p>Complete Delivery Payment List </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryPaymentGenerate') }}" class="nav-link {{ $child_menu == 'deliveryPaymentGenerate' ? 'active' : '' }}">
                                <i class="fas fa-indent"></i>
                                <p>Pending Payment</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('branch.parcel.deliveryPaymentList') }}" class="nav-link {{ $child_menu == 'deliveryPaymentList' ? 'active' : '' }}">
                                <i class="fas fa-hourglass-half"></i>
                                <p>Processing Payment List </p>
                            </a>
                        </li>
                    </ul>

                </li>



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
                <!--            <a href="{{ route('branch.bookingParcel.create') }}"-->
                <!--                class="nav-link {{ $child_menu == 'bookingParcel' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Add New Parcel Booking</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcel.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'bookingParcellist' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel List </p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcel.assignVehicle') }}"-->
                <!--                class="nav-link {{ $child_menu == 'assignVehicle' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel Assign Vehicle </p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcel.bookingParcelReceiveList') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcelReceiveList' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel Receive List</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcel.receiveBookingParcel') }}"-->
                <!--               class="nav-link {{ $child_menu == 'receiveBookingParcel' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Receive Booking Parcel</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->

                <!--<li class="nav-item has-treeview {{ $main_menu == 'booking_report' ? 'menu-open' : '' }} ">-->
                <!--    <a href="#" class="nav-link {{ $main_menu == 'booking_report' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-box-open fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Traditional Parcel Payment-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview">-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcelPayment.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'bookingParcelPaymentReport' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Parcel Payment List</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcelPayment.paymentForwardToAccounts') }}"-->
                <!--                class="nav-link {{ $child_menu == 'paymentForwardToAccounts' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Payment Forward to Accounts</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.bookingParcelPayment.bookingParcelPaymentReport') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcelPaymentReportList' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Parcel Payment Report</p>-->
                <!--            </a>-->
                <!--        </li>-->

                <!--    </ul>-->
                <!--</li>-->


                <!--<li class="nav-item has-treeview {{ $main_menu == 'traditionalParcelSetting' ? 'menu-open' : '' }} ">-->
                <!--    <a href="#" class="nav-link {{ $main_menu == 'traditionalParcelSetting' ? 'active' : '' }}">-->
                <!--        <i class="fas fa-cogs fa-lg text-success"></i>-->
                <!--        <p>-->
                <!--            Traditional Parcel Setting-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->

                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.vehicle.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'vehicle' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Vehicle</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->

                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.warehouse.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'warehouse' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Warehouse</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->

                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.itemCategory.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'itemCategory' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Item Category</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->

                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('branch.item.index') }}"-->
                <!--                class="nav-link {{ $child_menu == 'item' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Item</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->


                @endif


                <li class="nav-item">
                    <a href="{{ route('branch.orderTracking') }}" class="nav-link {{ $child_menu == 'orderTracking' ? 'active' : '' }}" >
                        <i class="fas fa-map fa-lg text-success"></i>
                        <p>
                            Order Tracking
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('branch.coverageArea') }}" class="nav-link {{ $child_menu == 'coverageArea' ? 'active' : '' }}" >
                        <i class="fas fa-tags fa-lg text-success"></i>
                        <p>
                            Coverage Area
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('branch.serviceCharge') }}" class="nav-link {{ $child_menu == 'serviceCharge' ? 'active' : '' }}" >
                        <i class="fas fa-tags fa-lg text-success"></i>
                        <p>
                            Service Charge
                        </p>
                    </a>
                </li> --}}


                <li class="nav-item">
                    <a href="{{ route('branch.merchantListByBranch') }}" class="nav-link {{ $main_menu == 'merchantList' ? 'active' : '' }}" >
                        <i class="fas fa-user fa-lg text-success"></i>
                        <p>
                           Marchent List
                        </p>
                    </a>
                </li>

                @if(auth('branch')->user()->branch->type == 1)

                <li class="nav-item">
                    <a href="{{ route('branch.riderListByBranch') }}" class="nav-link {{ $main_menu == 'riderList' ? 'active' : '' }}" >
                        <i class="fas fa-motorcycle fa-lg text-success"></i>
                        <p>
                            Rider List
                        </p>
                    </a>
                </li>

                @endif

                <li class="nav-item" style="margin-top: 20px">
                    <a href="{{ route('branch.logout') }}" class="nav-link ">
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
