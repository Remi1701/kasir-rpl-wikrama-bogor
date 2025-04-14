@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="">Cashier APP</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
        <a href="">Cashier APP</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            {{-- superadmin --}}
            @if (Auth::user()->role == 'superadmin')
            {{-- produk master --}}
            <li class="menu-header">Menu</li>
            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-shopping-bag"></i> <span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}"><i class="fas fa-shopping-cart"></i> <span>Penjualan</span></a>
            </li>
            {{-- user master --}}
            <li class="menu-header">User</li>
            <li class="{{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index')}}"><i class="fas fa-user-shield"></i> <span>User</span></a>
            </li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}"><i class="fas fa-user"></i> <span>Member</span></a>
            </li>

            @endif
            @if (Auth::user()->role == 'user')
            <li class="menu-header">Menu</li>
            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-shopping-bag"></i> <span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}"><i class="fas fa-shopping-cart"></i> <span>Penjualan</span></a>
            </li>

            {{-- user master --}}
            <li class="menu-header">User</li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}"><i class="fas fa-user"></i> <span>Member</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
@endauth
