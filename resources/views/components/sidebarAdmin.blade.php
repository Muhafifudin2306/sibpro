<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">SIBPRO</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">SIB</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('/') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Transaksi</li>
            <li class="dropdown {{ Request::is('pengeluaran*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-bill-wave-alt"></i>
                    <span>Pengeluaran</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'vendor' ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('pengeluaran/vendor') }}">Pembayaran Vendor</a></li>
                    <li><a class="nav-link" href="components-avatar.html">Penggajian Staff</a>
                    </li>
                    <li><a class="nav-link" href="components-chat-box.html">Pembelian Aset</a></li>
                    <li><a class="nav-link" href="components-empty-state.html">Biaya
                            Operasional</a></li>
                </ul>
            </li>
            <li class="dropdown {{ Request::is('income*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-dollar-sign"></i>
                    <span>Pemasukan</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="components-article.html">Daftar Ulang</a></li>
                    <li class="{{ Request::segment(2) === 'credit' ? 'active' : '' }}"><a
                            href="{{ url('income/credit') }}">SPP</a></li>
                </ul>
            </li>
            {{-- 
            <li class="menu-header">Anggaran</li>
            <li><a class="nav-link" href="blank.html"><i class="fas fa-chart-pie"></i><span>Laporan
                        Anggaran</span></a>
            </li>
            <li><a class="nav-link" href="blank.html"><i class="far fa-file-alt"></i><span>Manajemen
                        Anggaran</span></a>
            </li>
            <li class="menu-header">Keuangan</li>
            <li><a class="nav-link" href="blank.html"><i class="fas fa-file-archive"></i><span>Laporan
                        Raba Rugi</span></a>
            </li>
            <li><a class="nav-link" href="blank.html"><i class="fas fa-file-invoice"></i><span>Laporan
                        Arus Kas</span></a>
            </li>
            <li><a class="nav-link" href="blank.html"><i class="fas fa-file-invoice-dollar"></i><span>Laporan
                        Konsolidasi</span></a>
            </li> --}}

            <li class="menu-header">Utilitas</li>
            <li class="dropdown {{ Request::is('account*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i>
                    <span>Account</span></a>
                <ul class="dropdown-menu">
                    @can('access-userProfile')
                        <li class="{{ Request::segment(2) === 'profile' ? 'active' : '' }}"><a
                                href="{{ url('/account/profile') }}">Profile</a></li>
                    @endcan
                    @can('access-userList')
                        <li class="{{ Request::segment(2) === 'users' ? 'active' : '' }}"><a
                                href="{{ url('/account/users') }}">Users</a></li>
                    @endcan
                    @can('access-roleList')
                        <li class="{{ Request::segment(3) === 'role' ? 'active' : '' }}"><a
                                href="{{ url('/account/security/role') }}">Roles</a></li>
                    @endcan
                    @can('access-permissionList')
                        <li class="{{ Request::segment(3) === 'permission' ? 'active' : '' }}"><a
                                href="{{ url('/account/security/permission') }}">Permissions</a></li>
                    @endcan
                </ul>
            </li>
            @can('access-userList')
                <li class="dropdown {{ Request::is('setting*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i>
                        <span>General Setting</span></a>
                    <ul class="dropdown-menu">
                        @can('access-yearList')
                            <li class="{{ Request::segment(2) === 'year' ? 'active' : '' }}"><a
                                    href="{{ url('/setting/year') }}">Tahun Aktif</a></li>
                        @endcan
                    </ul>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::segment(2) === 'attribute' ? 'active' : '' }}"><a
                                href="{{ url('/setting/attribute') }}">Atribut</a></li>
                    </ul>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::segment(2) === 'class' ? 'active' : '' }}"><a
                                href="{{ url('/setting/class') }}">Kelas</a></li>
                    </ul>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::segment(2) === 'student' ? 'active' : '' }}"><a
                                href="{{ url('/setting/student') }}">Siswa</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </aside>
</div>
