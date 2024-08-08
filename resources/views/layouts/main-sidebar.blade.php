<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Menu</h5>

                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Main -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Principal</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard')?'active':'' }}">
                        <i class="ph-house"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('paradas.index') }}" class="nav-link {{ Route::is('paradas.*')?'active':'' }}">
                        <i class="ph ph-map-pin"></i>
                        <span>Paradas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('rutas.index') }}" class="nav-link {{ Route::is('rutas.*')?'active':'' }}">
                        <i class="ph ph-trend-up"></i>
                        <span>Rutas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vehiculos.index') }}" class="nav-link {{ Route::is('vehiculos.*')?'active':'' }}">
                        <i class="ph ph-bus"></i>
                        <span>Vehículos</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>