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
            </li>

            <li class="menu-header">Transaksi</li>
            @can('access-paymentStudent')
            <li class="dropdown {{ Request::is('payment*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-dollar-sign"></i>
                    <span>Pembayaran</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'enrollment' ? 'active' : '' }}"><a
                            href="{{ url('payment/enrollment') }}">Daftar Ulang</a></li>
                    <li class="{{ Request::segment(2) === 'credit' ? 'active' : '' }}"><a
                            href="{{ url('payment/credit') }}">SPP</a></li>
                </ul>
            </li>
            @endcan

            @can('access-outcome')
            <li class="dropdown {{ Request::is('spending*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-bill-wave-alt"></i>
                    <span>Pengeluaran</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'attribute' ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('spending/attribute') }}">Realisasi Atribut Siswa</a></li>
                </ul>
            </li>
            @endcan

            @can('access-income')
            <li class="dropdown {{ Request::is('income*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-dollar-sign"></i>
                    <span>Pemasukan</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'enrollment' ? 'active' : '' }}"><a
                            href="{{ url('income/enrollment') }}">Manajemen Daftar Ulang</a></li>
                    <li class="{{ Request::segment(2) === 'credit' ? 'active' : '' }}"><a
                            href="{{ url('income/credit') }}">Manajemen SPP</a></li>
                    <li class="{{ Request::segment(2) === 'external' ? 'active' : '' }}"><a
                            href="{{ url('income/external') }}">Dana Eksternal</a></li>
                    <li class="{{ Request::segment(2) === 'payment' ? 'active' : '' }}"><a
                            href="{{ url('income/payment/all') }}">Riwayat Transaksi</a></li>
                </ul>
            </li>
            @endcan

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
            <li class="menu-header">Master Data</li>
            <li class="dropdown {{ Request::is('master*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-archive"></i>
                    <span>Master Data</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'pos' ? 'active' : '' }}"><a
                            href="{{ url('/master/pos') }}">Point Of Sales</a></li>
                    <li class="{{ Request::segment(2) === 'vendor' ? 'active' : '' }}"><a
                            href="{{ url('/master/vendor') }}">Master Vendor</a></li>
                </ul>
            </li>
            <li class="menu-header">Utilitas</li>
            <li class="dropdown {{ Request::is('account*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i>
                    <span>Pengaturan Akun</span></a>
                <ul class="dropdown-menu">
                    @can('access-userProfile')
                    <li class="{{ Request::segment(2) === 'profile' ? 'active' : '' }}"><a
                            href="{{ url('/account/profile') }}">Profil Saya</a></li>
                    @endcan
                    @can('access-userList')
                    <li class="{{ Request::segment(2) === 'users' ? 'active' : '' }}"><a
                            href="{{ url('/account/users') }}">Manajemen Pengguna</a></li>
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
            <li class="dropdown {{ Request::is('setting*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i>
                    <span>General Setting</span></a>
                <ul class="dropdown-menu">
                    @can('access-yearList')
                    <li class="{{ Request::segment(2) === 'year' ? 'active' : '' }}"><a
                            href="{{ url('/setting/year') }}">Tahun Aktif</a></li>
                    @endcan
                </ul>
                @can('access-packageList')
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'packages' ? 'active' : '' }}"><a
                            href="{{ url('/setting/packages') }}">Paket</a></li>
                </ul>
                @endcan
                @can('access-classList')
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'class' ? 'active' : '' }}"><a
                            href="{{ url('/setting/class') }}">Kelas</a></li>
                </ul>
                @endcan
                @can('access-studentList')
                <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2) === 'student' ? 'active' : '' }}"><a
                            href="{{ url('/setting/student') }}">Siswa</a></li>
                </ul>
                @endcan
            </li>
        </ul>
    </aside>
</div>