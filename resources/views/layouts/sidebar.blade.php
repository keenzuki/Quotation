<style>
    .sidebar {
        height: 100%;
        max-width: 280px;
        position: fixed;
        background-color: rgb(229, 236, 239);
        top: 8px;
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
            max-width: 200px
        }
    }

    @media (max-width: 768px) {
        #sidebar {
            max-width: 200px
        }
    }

    @media (max-width: 576px) {
        #sidebar {
            max-width: 200px
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
        color: white;
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
    <nav id="sidebar"
        class="navbar sidebar collapse d-sm-inline navbar-expand-sm border-end align-items-start mt-5 shadow-sm">
        <div id="main-menu" class="main-menu">
            <ul class="nav navbar-nav flex-column ms-1 mt-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? ' active' : '' }}">
                        <i class="bi bi-laptop fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Dashboard"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>
                <span class="heading ">Clients</span>
                <hr class="text-black me-2 my-1">
                <li class="nav-item">
                    <a href="{{ route('agent.clients') }}" class="nav-link">
                        <i class="bi bi-people fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">My Clients</span>
                    </a>
                </li>
                <span class="heading ">Quotations</span>
                <hr class="text-black me-2 my-1">
                <li class="nav-item">
                    <a href="{{ route('agent.quotations') }}" class="nav-link">
                        <i class="bi bi-chat-quote fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">My Quotations</span>
                    </a>
                </li>
                <span class="heading ">Invoices</span>
                <hr class="text-black me-2 my-1">
                <li class="nav-item">
                    <a href="{{ route('agent.clients') }}" class="nav-link">
                        <i class="bi bi-receipt fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">My Invoices</span>
                    </a>
                </li>
                <span class="heading ">Payments</span>
                <hr class="text-black me-2 my-1">
                <li class="nav-item">
                    <a href="{{ route('agent.clients') }}" class="nav-link">
                        <i class="bi bi-wallet2 fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">My Payments</span>
                    </a>
                </li>
                <span class="heading ">Items</span>
                <hr class="text-black me-2 my-1">
                <li class="nav-item">
                    <a href="{{ route('agent.clients') }}" class="nav-link">
                        <i class="bi bi-diagram-3 fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Parcels"></i>
                        <span class="ms-2 ms-md-4 nav-name d-none d-sm-inline">All Items</span>
                    </a>
                </li>
                <span class="heading">Reports</span>
                <hr class="text-black me-2 my-1">
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
