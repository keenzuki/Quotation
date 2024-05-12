<nav class="navbar navbar-expand-sm bg-body-tertiary position-fixed" style="z-index: 1;">
    <div class="container-fluid d-flex justify-content-between">
        <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>
        <div class="d-flex gap-1">
            @auth
                <div class="btn-group">
                    <button class="btn btn-info dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->lname }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                        <div class="dropdown-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>

            @endauth
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ env('APP_NAME') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav navbar-nav flex-column ms-1 mt-1">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? ' active' : '' }}">
                    <i class="bi bi-laptop fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Dashboard"></i>
                    <span class="ms-2 ms-md-4 nav-name">Dashboard</span>
                </a>
            </li>
            <span class="heading ">Clients</span>
            <hr class="text-black me-2 my-1">
            <li class="nav-item">
                <a href="{{ route('agent.clients') }}" class="nav-link">
                    <i class="bi bi-people fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Parcels"></i>
                    <span class="ms-2 ms-md-4 nav-name">My Clients</span>
                </a>
            </li>
            <span class="heading ">Quotations</span>
            <hr class="text-black me-2 my-1">
            <li class="nav-item">
                <a href="{{ route('agent.quotations') }}" class="nav-link">
                    <i class="bi bi-chat-quote fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Parcels"></i>
                    <span class="ms-2 ms-md-4 nav-name">My Quotations</span>
                </a>
            </li>
            <span class="heading ">Invoices</span>
            <hr class="text-black me-2 my-1">
            <li class="nav-item">
                <a href="{{ route('agent.invoices') }}" class="nav-link">
                    <i class="bi bi-receipt fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Parcels"></i>
                    <span class="ms-2 ms-md-4 nav-name">My Invoices</span>
                </a>
            </li>
            <span class="heading ">Payments</span>
            <hr class="text-black me-2 my-1">
            <li class="nav-item">
                <a href="{{ route('agent.clients') }}" class="nav-link">
                    <i class="bi bi-wallet2 fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Parcels"></i>
                    <span class="ms-2 ms-md-4 nav-name">My Payments</span>
                </a>
            </li>
            <span class="heading ">Items</span>
            <hr class="text-black me-2 my-1">
            <li class="nav-item">
                <a href="{{ route('agent.items') }}" class="nav-link">
                    <i class="bi bi-diagram-3 fs-3" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Parcels"></i>
                    <span class="ms-2 ms-md-4 nav-name">All Items</span>
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
</div>
