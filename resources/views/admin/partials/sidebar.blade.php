<div class="leftside-menu">

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-light">
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo.png')}}" alt="" height="16">
                    </span>
        <span class="logo-sm">
                        <img src="{{asset('assets/images/logo_sm.png')}}" alt="" height="16">
                    </span>
    </a>

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-dark">
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="16">
                    </span>
        <span class="logo-sm">
                        <img src="{{asset('assets/images/logo_sm_dark.png')}}" alt="" height="16">
                    </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">{{trans("traduction.navigation")}}</li>

            <li class="side-nav-item">
                <a href="{{route('admin.dashboard')}}" class="side-nav-link text-white">
                    <i class="mdi mdi-monitor-dashboard"></i>
                    <span>{{trans("traduction.dashboard")}}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('admin.drivers')}}" class="side-nav-link text-white">
                    <i class="mdi mdi-account-multiple"></i>
                    <span>{{trans("traduction.drivers")}}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('admin.courses')}}" class="side-nav-link text-white">
                    <i class="mdi mdi-car"></i>
                    <span>{{trans("traduction.courses")}}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('clients.index')}}" class="side-nav-link text-white">
                    <i class="mdi mdi-account-multiple"></i>
                    <span>{{trans("traduction.clients")}}</span>
                </a>
            </li>
        </ul>

        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
