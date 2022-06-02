<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                {{-- <div class="sb-sidenav-menu-heading">Core</div> --}}
                <a class="nav-link mt-3" href="{{ url('/') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <hr class="mx-3 mb-0">
                @if(Auth::user()->designation == 'manager')
                    <div class="sb-sidenav-menu-heading">Inventory</div>
                    <a class="nav-link" href="{{ url('/suppliers/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Suppliers
                    </a>
                    <a class="nav-link" href="{{ url('/products/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Products
                    </a>
                    <a class="nav-link" href="{{ url('/inventory/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Inventory
                    </a>
                    <a class="nav-link" href="{{ url('/orders/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Consign Orders
                    </a>
                    <a class="nav-link" href="{{ url('/units/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Units
                    </a>
                    <hr class="mx-3 mb-0">
                    <div class="sb-sidenav-menu-heading">Administration</div>
                    <a class="nav-link" href="{{ url('/reports/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Reports
                    </a>
                    <a class="nav-link" href="{{ url('/personnel/') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Personnel
                    </a>
                    
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->name }}
        </div>
    </nav>
</div>