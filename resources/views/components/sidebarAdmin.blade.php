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

            {{-- <li class="menu-header">Transaksi</li>
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
            @endcan --}}
            <li class="menu-header">Transaksi</li>

            @can('access-cartStudent')
                <li class="{{ Request::is('cart') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/cart') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Loket Pemesanan</span>
                    </a>
                </li>
            @endcan

            @can('access-paymentStudent')
                <li class="{{ Request::is('payment') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/payment') }}">
                        <i class="fas fa-dollar-sign"></i>
                        <span>Pembayaran Tagihan</span>
                    </a>
                </li>
            @endcan

            @can('access-doneStudentPayment')
                <li class="{{ Request::is('payment-done*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/payment-done') }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Pembayaran Berhasil</span>
                    </a>
                </li>
            @endcan

            @can('access-outcome')
                <li class="dropdown {{ Request::is('spending*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-bill-wave-alt"></i>
                        <span>Pengeluaran</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::segment(2) === 'attribute' ? 'active' : '' }}"><a class="nav-link"
                                href="{{ url('spending/attribute') }}">Realisasi Atribut Siswa</a></li>
                        <li class="{{ Request::segment(2) === 'bahan' ? 'active' : '' }}"><a class="nav-link"
                                href="{{ url('spending/bahan') }}">Belanja Bahan dan Alat</a></li>
                    </ul>
                </li>
            @endcan

            @can('access-income')
                <li class="dropdown {{ Request::is('income*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-dollar-sign"></i>
                        <span>Pemasukan</span></a>
                    <ul class="dropdown-menu">
                        {{-- <li class="{{ Request::segment(2) === 'enrollment' ? 'active' : '' }}"><a
                                href="{{ url('income/enrollment') }}">Manajemen Daftar Ulang</a></li>
                        <li class="{{ Request::segment(2) === 'credit' ? 'active' : '' }}"><a
                                href="{{ url('income/credit') }}">Manajemen SPP</a></li> --}}
                        <li class="{{ Request::segment(2) === 'external' ? 'active' : '' }}"><a
                                href="{{ url('income/external') }}">Dana Eksternal</a></li>
                        <li class="{{ Request::segment(2) === 'payment' ? 'active' : '' }}"><a
                                href="{{ url('income/payment/all') }}">Tagihan Siswa</a></li>
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

            <li class="{{ Request::is('enrollment') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/enrollment') }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Blangko Tagihan Siswa</span>
                </a>
            </li>

            @can('access-confirmCredit')
                <li class="{{ Request::is('credit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/credit') }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Verifikasi Tagihan Siswa</span>
                    </a>
                </li>
            @endcan

            @can('access-recentTransaction')
                <li class="{{ Request::is('transaction*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/transaction/recent') }}">
                        <i class="fas fa-clock"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
            @endcan

            @can('access-dataMaster')
                <li class="menu-header">Master Data</li>
                <li class="dropdown {{ Request::is('master*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-archive"></i>
                        <span>Master Transaksi</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::segment(2) === 'pos' ? 'active' : '' }}"><a
                                href="{{ url('/master/pos') }}">Point Of Sales</a></li>
                        <li class="{{ Request::segment(2) === 'vendor' ? 'active' : '' }}"><a
                                href="{{ url('/master/vendor') }}">Master Vendor</a></li>
                    </ul>
                </li>
                @can('access-studentList')
                    <li class="dropdown {{ Request::is('student*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-user"></i>
                            <span>Data Siswa</span></a>
                        <ul class="dropdown-menu">
                            @foreach ($students as $item)
                                <li class="{{ Request::segment(3) === $item->uuid ? 'active' : '' }}"><a
                                        href="{{ url('student/detail/' . $item->uuid) }}">{{ $item->class_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endcan
                @can('access-classList')
                    <li class="{{ Request::is('class') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/class') }}">
                            <i class="fas fa-home"></i>
                            <span>Master Kelas</span>
                        </a>
                    </li>
                @endcan

                <li class="{{ Request::is('petugas') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/petugas') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Master Petugas</span>
                    </a>
                </li>
            @endcan
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
                                href="{{ url('/account/security/role') }}">Manajemen Roles</a></li>
                    @endcan
                    @can('access-permissionList')
                        <li class="{{ Request::segment(3) === 'permission' ? 'active' : '' }}"><a
                                href="{{ url('/account/security/permission') }}">Manajemen Permissions</a></li>
                    @endcan
                </ul>
            </li>
            @can('access-generalSetting')
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
                </li>
            @endcan
        </ul>
    </aside>
    <div class="my-5"></div>
</div>
