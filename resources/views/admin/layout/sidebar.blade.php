<aside class="app-sidebar">
    <div class="sidebar-content p-3">
        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </li>

            <!-- Category Management -->
            <li class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}"><i class="bi bi-folder me-2"></i>Categories</a>
            </li>

            <!-- Brand Management -->
            <li class="{{ request()->routeIs('brands.index') ? 'active' : '' }}">
                <a href="{{ route('brands.index') }}"><i class="bi bi-tags me-2"></i>Brands</a>
            </li>
            <!-- Product Management -->
            <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}"><i class="bi bi-box-seam me-2"></i>Products</a>
            </li>


           
          
            <!-- Logout -->
            <li>
                <a href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </li>

        </ul>
    </div>
</aside>
