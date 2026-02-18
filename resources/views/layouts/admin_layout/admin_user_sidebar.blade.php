<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}" class="brand-link">
        <!--<img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"-->
        <!--     alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image"-->
        <!--     style="opacity: .8">-->

             <img src="https://safedeliverycourier.com/public/logo.png" alt="{{ session()->get('company_name') ?? config('app.name') }}" class="brand-image">
        <br>

    </a>


    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 text-center">
            @if(!empty(auth()->guard('admin')->user()->photo))
                <div class="image">
                    <img src="{{ asset('uploads/admin/' . auth()->guard('admin')->user()->photo) }} "
                         class="img-thumbnail elevation-2" alt="Admin Photo">
                </div>
            @else
                <div class="image">
                    <img src="{{ asset('image/admin_layout/avatar5.png') }} " class="img-thumbnail elevation-2"
                         alt="Admin Photo">
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

                <li class="nav-item has-treeview {{ $main_menu == 'website' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'website' ? 'active' : '' }}">
                        <i class="fas fa-globe-americas fa-lg text-success"></i>
                        <p>
                            Website
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.slider.index') }}"
                               class="nav-link {{ $child_menu == 'slider' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Slider</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.designation.index') }}"
                               class="nav-link {{ $child_menu == 'designation' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Designation</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.teamMember.index') }}"
                               class="nav-link {{ $child_menu == 'teamMember' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Team Member</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.partner.index') }}"
                               class="nav-link {{ $child_menu == 'partner' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Partner</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.customerFeedback.index') }}"
                               class="nav-link {{ $child_menu == 'customerFeedback' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Customer Feedback</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.frequentlyAskQuestion.index') }}"
                               class="nav-link {{ $child_menu == 'frequentlyAskQuestion' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Frequently Ask Question</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.objective.index') }}"
                               class="nav-link {{ $child_menu == 'objective' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Objective</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.service.index') }}"
                               class="nav-link {{ $child_menu == 'service' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Service</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.deliveryService.index') }}"
                               class="nav-link {{ $child_menu == 'deliveryService' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Delivery Service</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.feature.index') }}"
                               class="nav-link {{ $child_menu == 'feature' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Feature</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.index') }}"
                               class="nav-link {{ $child_menu == 'blog' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Blog</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.socialLink.index') }}"
                               class="nav-link {{ $child_menu == 'socialLink' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Social Link</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.parcelStep.index') }}"
                               class="nav-link {{ $child_menu == 'parcelStep' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Parcel Step</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.aboutPoint.index') }}"
                               class="nav-link {{ $child_menu == 'aboutPoint' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>About Point</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.pageContent.index') }}"
                               class="nav-link {{ $child_menu == 'pageContent' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Page Content</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.visitorMessage.index') }}"
                               class="nav-link {{ $child_menu == 'visitorMessage' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Visitor Message</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.newsLetter.index') }}"
                               class="nav-link {{ $child_menu == 'newsLetter' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>News Letter</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.office.index') }}"
                               class="nav-link {{ $child_menu == 'office' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Office</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.becomeMerchant') }}"
                               class="nav-link {{ $child_menu == 'becomeMerchant' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Become Merchant</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.becomeFranchisee') }}"
                               class="nav-link {{ $child_menu == 'becomeFranchisee' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Become Franchisee</p>
                            </a>
                        </li>
                    </ul>

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
                            <a href="{{ route('admin.branch.index') }}"
                               class="nav-link {{ $child_menu == 'branch' ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                <p>Branch</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.branchUser.index') }}"
                               class="nav-link {{ $child_menu == 'branchUser' ? 'active' : '' }}">
                                <i class="fas fa-user-plus"></i>
                                <p>Branch User</p>
                            </a>
                        </li>
                    </ul>

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

                    <!--<ul class="nav nav-treeview">-->
                    <!--    <li class="nav-item">-->
                    <!--        <a href="{{ route('admin.warehouse.index') }}"-->
                    <!--           class="nav-link {{ $child_menu == 'warehouse' ? 'active' : '' }}">-->
                    <!--            <i class="fas fa-tags"></i>-->
                    <!--            <p>Warehouse</p>-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--</ul>-->
                    <!--<ul class="nav nav-treeview">-->
                    <!--    <li class="nav-item">-->
                    <!--        <a href="{{ route('admin.warehouseUser.index') }}"-->
                    <!--           class="nav-link {{ $child_menu == 'warehouseUser' ? 'active' : '' }}">-->
                    <!--            <i class="fas fa-tags"></i>-->
                    <!--            <p>Warehouse User</p>-->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--</ul>-->


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
                            <a href="{{ route('admin.parcel.allParcelList') }}"
                               class="nav-link {{ $child_menu == 'allParcelList' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>All Parcel List </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.branch_wise_parcel_report') }}"
                               class="nav-link {{ $child_menu == 'branch_wise_parcel_report' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Branch Wise Parcel Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.today_parcel_for_delivery') }}"
                               class="nav-link {{ $child_menu == 'today_parcel_for_delivery' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Today Parcel For Delivery </p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('admin.parcel.merchant_today_pickup') }}"
                               class="nav-link {{ $child_menu == 'merchant_today_pickup' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Merchant Today Pickup </p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.parcel.orderTracking') }}"
                               class="nav-link {{ $child_menu == 'orderTracking' ? 'active' : '' }}">
                                <i class="fas fa-map-marker"></i>
                                <p>Order Tracking </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.report') }}"
                       class="nav-link {{ $child_menu == 'report' ? 'active' : '' }}">
                        <i class="fas fa-tags text-success"></i>
                        <p>Parcel Report</p>
                    </a>
                </li>
                <li class="nav-item">
                            <a href="{{ route('admin.merchant.pickup.parcelReport') }}"
                                class="nav-link {{ $child_menu == 'merchant-pickup-parcel-report' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Merchant Pickup Parcel Report</p>
                            </a>
                        </li>
                <li class="nav-item has-treeview {{ $main_menu == 'report' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'report' ? 'active' : '' }}">
                        <i class="fas fa-book fa-lg text-success"></i>
                        <p>
                            Report
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
                            <a href="{{ route('admin.merchant.parcelReport') }}"
                               class="nav-link {{ $child_menu == 'merchant-parcel-report' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Merchant Parcel Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.rider.deliveryParcelReport') }}"
                               class="nav-link {{ $child_menu == 'rider-delivery-parcel-report' ? 'active' : '' }}">
                                <i class="fas fa-motorcycle"></i>
                                <p>Rider Delivery Parcel Report</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item has-treeview {{ $main_menu == 'applicationSetting' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'applicationSetting' ? 'active' : '' }}">
                        <i class="fas fa-truck fa-lg text-success"></i>
                        <p>
                            Application Setting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.serviceArea.index') }}"
                               class="nav-link {{ $child_menu == 'serviceArea' ? 'active' : '' }}">
                                <i class="fas fa-street-view"></i>
                                <p>Service Area</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.weightPackage.index') }}"
                               class="nav-link {{ $child_menu == 'weightPackage' ? 'active' : '' }}">
                                <i class="fas fa-cube"></i>
                                <p>Weight Package</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.serviceAreaSetting.index') }}"
                               class="nav-link {{ $child_menu == 'serviceAreaSetting' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p> Weight Custom Charge</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.service.type') }}"
                               class="nav-link {{ $child_menu == 'service-type-list' ? 'active' : '' }}">
                                <i class="fas fa-sitemap"></i>
                                <p>Service Type</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.item.type') }}"
                               class="nav-link {{ $child_menu == 'item-type-list' ? 'active' : '' }}">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Item Type</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.district.index') }}"
                               class="nav-link {{ $child_menu == 'district' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>District</p>
                            </a>
                        </li>
                    </ul>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.upazila.index') }}"
                                class="nav-link {{ $child_menu == 'upazila' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Thana/Upazila</p>
                            </a>
                        </li>
                    </ul> --}}
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.area.index') }}"
                               class="nav-link {{ $child_menu == 'area' ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <p>Area</p>
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
                <!--            <a href="{{ route('admin.bookingParcel.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'bookingParcellist' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Booking Parcel List </p>-->
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
                <!--            <a href="{{ route('admin.vehicle.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'vehicle' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Vehicle</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->

                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.itemCategory.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'itemCategory' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Item Category</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.unit.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'unit_list' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Unit</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('admin.item.index') }}"-->
                <!--               class="nav-link {{ $child_menu == 'item-list' ? 'active' : '' }}">-->
                <!--                <i class="fas fa-tags"></i>-->
                <!--                <p>Item</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->


                <!--</li>-->



                <li class="nav-item">
                    <a href="{{ route('admin.notice.index') }}" class="nav-link {{ $child_menu == 'noticeList' ? 'active' : '' }}" >
                        <i class="fas fa-bullhorn fa-lg text-success"></i>
                        <p>
                            Notice OR News
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ $main_menu == 'setting' ? 'menu-open' : '' }} ">
                    <a href="#" class="nav-link {{ $main_menu == 'setting' ? 'active' : '' }}">
                        <i class="fas fa-cogs fa-lg text-success"></i>
                        <p>
                            Setting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.admin.index') }}"
                               class="nav-link {{ $child_menu == 'admin' ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <p>Admin User</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.application.index') }}"
                               class="nav-link {{ $child_menu == 'application' ? 'active' : '' }}">
                                <i class="fas fa-anchor"></i>
                                <p>Application</p>
                            </a>
                        </li>
                    </ul>
                </li>


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
