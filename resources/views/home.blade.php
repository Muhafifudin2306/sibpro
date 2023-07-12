@extends('layouts.admin.app')

@section('content')
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">

            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown"><a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                        <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }} </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" id="logOutFunction" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-sidebar sidebar-style-2">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="index.html">SIBPRO</a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="index.html">St</a>
                </div>
                <ul class="sidebar-menu">
                    <li class="menu-header">Dashboard</li>
                    <li class="active">
                        <a class="nav-link" href="blank.html">
                            <i class="fas fa-fire"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-header">Transaksi</li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-bill-wave-alt"></i>
                            <span>Pengeluaran</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="components-article.html">Pembayaran Vendor</a></li>
                            <li><a class="nav-link" href="components-avatar.html">Penggajian Staff</a>
                            </li>
                            <li><a class="nav-link" href="components-chat-box.html">Pembelian Aset</a></li>
                            <li><a class="nav-link" href="components-empty-state.html">Biaya
                                    Operasional</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-dollar-sign"></i>
                            <span>Pemasukan</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="components-article.html">Pembayaran Siswa</a></li>
                            <li><a class="nav-link" href="components-avatar.html">Sponsor dan Donasi</a>
                            </li>
                            <li><a class="nav-link" href="components-chat-box.html">Pendapatan Lainnya</a></li>
                        </ul>
                    </li>
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
                    </li>
                    <li class="menu-header">Akun dan Akses</li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i>
                            <span>Authenticate</span></a>
                        <ul class="dropdown-menu">
                            <li><a href="auth-forgot-password.html">Users</a></li>
                            <li><a href="auth-login.html">Roles</a></li>
                        </ul>
                    </li>
                </ul>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Dashboard') }}</h1>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Admin</h4>
                                </div>
                                <div class="card-body">
                                    10
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>News</h4>
                                </div>
                                <div class="card-body">
                                    42
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-file"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Reports</h4>
                                </div>
                                <div class="card-body">
                                    1,201
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Online Users</h4>
                                </div>
                                <div class="card-body">
                                    47
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad
                    Nauval Azhar</a>
            </div>
            <div class="footer-right">

            </div>
        </footer>
    </div>
@endsection
