<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Toko Kinza</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Tkz</a>
        </div>
        <ul class="sidebar-menu">
            <li class="">
                <a class="nav-link"
                    href="/"><i class="fa fa-tachometer"></i><span>Dashboard</span></a>
            </li>
            <li class="">
                <a class="nav-link"
                    href="/"><i class="fa fa-shopping-bag"></i> <span>Kasir</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#"
                    class="nav-link has-dropdown"
                    data-toggle="dropdown"><i class="fa fa-inbox"></i> <span>Barang</span></a>
                <ul class="dropdown-menu">
                    <li class="">
                        <a class="nav-link"
                            href="{{ route('item.index') }}">Index</a>
                    </li>
                    <li class="">
                        <a class="nav-link"
                            href="{{ route('item.create') }}">Tambah Barang</a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a class="nav-link"
                    href="{{ route('unit.index') }}"><i class="far fa-clone"></i> <span>Satuan</span></a>
            </li>
            <li class="">
                <a class="nav-link"
                    href="/"><i class="far fa-usd"></i> <span>Transaksi</span></a>
            </li>
            <li class="">
                <a class="nav-link"
                    href="/"><i class="far fa-user"></i> <span>Users</span></a>
            </li>
        </ul>
    </aside>
</div>
