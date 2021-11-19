<!--Main Navigation-->
<header>

    <!-- Sidebar navigation -->
    <div id="slide-out" class="side-nav sn-bg-4 fixed">
        <ul class="custom-scrollbar">
            <!-- Logo -->
            <li class="logo-sn waves-effect py-3">
                <div class="text-center">
                    <a href="{!! route('admin.dashboard') !!}" class="pl-0"><img src="@yield('asideLogoPath','http://nerdpanda.ir/static/media/Logo.8d37bb47.svg')" style="width: 100%; height: 100%"></a>
                </div>
            </li>
            <!--/. Logo -->

            <!--Search Form-->
            <li>
                <form class="search-form" role="search">
                    <div class="md-form mt-0 waves-light">
                        <input type="text" class="form-control py-2" placeholder="Search">
                    </div>
                </form>
            </li>
            <!--/.Search Form-->
            <!-- Side navigation links -->
            <li>
                <ul class="collapsible collapsible-accordion">
                    @section('asideLinks')
                        <x-admin.dashboard.aside.item title="admin" icon="fa-light fa-user-crown">
                            <x-slot name="body">
                                <x-admin.dashboard.aside.item title=" show all " icon="fa-light fa-layer-group" :url="route('admin.management.index')"/>
                                <x-admin.dashboard.aside.item title=" create  " icon="fa-light fa-layer-plus" :url="route('admin.management.create')"/>
                                <x-admin.dashboard.aside.item title=" trashes  " icon="fa-light fa-trash" :url="route('admin.management.trashes')"/>
                            </x-slot>
                        </x-admin.dashboard.aside.item>



                        <x-admin.dashboard.aside.item title="slider"  icon="fa-thin fa-presentation-screen">
                            <x-slot name="body" >
                                <x-admin.dashboard.aside.item title=" show all " icon="fa-light fa-layer-group" :url="route('admin.slider.index')"/>
                                <x-admin.dashboard.aside.item title="create Slider" icon="fa-light fa-layer-plus" :url="route('admin.slider.create')" />
                                <x-admin.dashboard.aside.item title="show trashes " icon="fa-light fa-trash" :url="route('admin.slider.trashes')"/>
                            </x-slot>
                        </x-admin.dashboard.aside.item>
                    @show
                </ul>
            </li>
            <!--/. Side navigation links -->
        </ul>
        <div class="sidenav-bg mask-strong"></div>
    </div>
    <!--/. Sidebar navigation -->

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg scrolling-navbar double-nav">
        <!-- SideNav slide-out button -->
        <div class="float-left">
            <a href="#" data-activates="slide-out" class="button-collapse black-text"><i class="fas fa-bars"></i></a>
        </div>
        <!-- Breadcrumb-->
        <div class="breadcrumb-dn mr-auto">
            @section('navTitle')
                <p>Nerd Panda Admin Dashboard</p>
            @show
        </div>
        <ul class="nav navbar-nav nav-flex-icons ml-auto">
        @section('navLinks')
                <!-- Dropdown -->
                <li class="nav-item">
                    <a class="nav-link waves-effect" href="{!! url('') !!}"><i class="fas fa-home-heart"></i> <span class="clearfix d-none d-sm-inline-block">HOME</span></a>
                </li>
                    <li class="nav-item avatar dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                         <img src="{{asset(session()->get('admin.data.thumbnail')?? 'media/default.png')}}" class="rounded-circle z-depth-0"
                                 alt="avatar image">
                        {{session()->get('admin.data.nick')}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"
                             aria-labelledby="navbarDropdownMenuLink-55">
                            <a class="dropdown-item" href="{{route('admin.logout')}}">LogOut</a>
                        </div>
                    </li>
        @show
        </ul>

    </nav>
    <!-- /.Navbar -->

</header>
<!--Main Navigation-->
