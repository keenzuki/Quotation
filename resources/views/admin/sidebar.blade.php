<style>
    body {
        width: 100vw;
        margin-top: 60px;
    }

    .sidebar {
        height: 100%;
        max-width: 280px;
        position: fixed;
        background-color: rgb(229, 236, 239);
        top: 15px;
        z-index: 1;
        overflow-y: hidden;
        max-height: calc(100% - 50px);
        padding-bottom: 35px;
        color: Black;
    }

    .sidebar:hover {
        overflow-y: auto;
    }

    .sidebar::-webkit-scrollbar {
        width: 11px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background-color: slategrey;
        border-radius: 10px
    }

    .sidebar::-webkit-scrollbar-track {
        background-color: rgb(46, 58, 73);
    }

    @media (max-width: 992px) {
        #sidebar {
            max-width: 100px
        }
    }

    @media (max-width: 765px) {
        #sidebar {
            max-width: 100px
        }
    }

    .navbar-nav .nav-link {
        color: rgb(204, 203, 203);
        transition: color 0.3s ease;
        font-style: inherit
    }

    .navbar-nav .nav-link:hover {
        color: rgb(7, 97, 149);
    }

    .heading {
        font-weight: 620;
        color: white
    }

    i {
        color: black;
    }

    i:hover {
        color: rgb(0, 0, 0);
    }

    #sidebar .nav-link {
        color: rgb(0, 0, 0);
        text-decoration: none;
        background-color: rgba(198, 221, 231, 0.764);
        border-radius: 9px;
    }

    #sidebar .nav-link.active {
        color: rgb(0, 0, 0);
        text-decoration: none;
        box-shadow: 0px 4px 1px green;
        background-color: rgb(255, 132, 0);
        border-radius: 9px;
    }

    .nav-name,
    .heading {
        color: black;
    }
</style>

<body>
    <nav id="sidebar" class="navbar sidebar d-md-inline navbar-expand border-end align-items-start mt-5 shadow-sm">
        <div id="main-menu" class="main-menu">
            <ul class="nav navbar-nav flex-column ms-1 mt-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
                        <i class="bi bi-laptop fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Dashboard"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                <span class="heading">Parcels</span>
                <hr class="text-black me-2">
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.parcels.index') }}"
                        class="nav-link {{ request()->routeIs('admin.parcels.index') ? ' active' : '' }}">
                        <i class="bi bi-envelope fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">All Parcels</span>
                    </a>
                </li> --}}
                <span class="heading">Payments</span>
                <hr class="text-black me-2">
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.payments') }}"
                        class="nav-link {{ request()->routeIs('admin.payments') ? ' active' : '' }}">
                        <i class="bi bi-wallet2 fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Payments"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">All Payments</span>
                    </a>
                </li> --}}
                <span class="heading">Routes</span>
                <hr class="text-black me-3">
                {{-- <li class="nav-item">
                    <a id="routes" href="{{ route('admin.routes.index') }}"
                        class="nav-link {{ request()->routeIs('admin.routes.index') ? ' active' : '' }}">
                        <i class="bi bi-sign-turn-right fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Routes"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">All Routes</span>
                    </a>
                </li> --}}
                <span class="heading">Users</span>
                <hr class="text-black me-3">
                <li class="nav-item">
                    <a id="routes" href="{{ route('admin.users') }}"
                        class="nav-link {{ request()->routeIs('admin.users') ? ' active' : '' }}">
                        <i class="bi bi-people fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Users"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">All Users</span>
                    </a>
                </li>
                <span class="heading">Vehicles</span>
                <hr class="text-black me-3">
                {{-- <li class="nav-item">
                    <a id="routes" href="{{ route('admin.vehicles.index') }}"
                        class="nav-link {{ request()->routeIs('admin.vehicles.index') ? ' active' : '' }}">
                        <i class="bi bi-truck fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Vehicles"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">All Vehicles</span>
                    </a>
                </li> --}}
                <span class="heading">Reports</span>
                <hr class="text-black me-3">
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.routeperformance') }}" class="nav-link">
                        <i class="bi bi-sign-turn-right fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Route Performance"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">Route Performance</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="{{ route('admin.stationperformance') }}" class="nav-link">
                        <i class="bi bi-house fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Station Performance"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">Station Performance</span>
                    </a>
                </li>

                <li class="nav-item mt-1">
                    <a href="" class="nav-link">
                        <i class="bi bi-activity fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Activity Logs"></i>
                        <span class="ms-4 nav-name d-none d-md-none d-lg-inline">Activity Logs</span>
                    </a>
                </li> --}}
            </ul>
        </div>
    </nav>
    {{-- <script>
        $(document).ready(function() {
            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                var targetUrl = $(this).attr('href');
                window.location.href = targetUrl;
            })
        })
    </script> --}}
</body>
