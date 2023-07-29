@extends('layouts.admin.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran Vendor') }}</h1>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Tabel Tagihan Vendor') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-tagihan-vendor">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Task Name</th>
                                                <th>Progress</th>
                                                <th>Members</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td>Create a mobile app</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Wildan Ahdian">
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2
                                                </td>
                                                <td>Redesign homepage</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="0%">
                                                        <div class="progress-bar" data-width="0"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Nur Alpiana">
                                                    <img alt="image" src="assets/img/avatar/avatar-3.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hariono Yusup">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Bagus Dwi Cahya">
                                                </td>
                                                <td>2018-04-10</td>
                                                <td>
                                                    <div class="badge badge-info">Todo</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    3
                                                </td>
                                                <td>Backup database</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="70%">
                                                        <div class="progress-bar bg-warning" data-width="70%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hasan Basri">
                                                </td>
                                                <td>2018-01-29</td>
                                                <td>
                                                    <div class="badge badge-warning">In Progress</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    4
                                                </td>
                                                <td>Input data</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Isnap Kiswandi">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Yudi Nawawi">
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Khaerul Anwar">
                                                </td>
                                                <td>2018-01-16</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Tabel Pembayaran Vendor') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-pembayaran-vendor">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Task Name</th>
                                                <th>Progress</th>
                                                <th>Members</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td>Create a mobile app</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Wildan Ahdian">
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2
                                                </td>
                                                <td>Redesign homepage</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="0%">
                                                        <div class="progress-bar" data-width="0"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Nur Alpiana">
                                                    <img alt="image" src="assets/img/avatar/avatar-3.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hariono Yusup">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Bagus Dwi Cahya">
                                                </td>
                                                <td>2018-04-10</td>
                                                <td>
                                                    <div class="badge badge-info">Todo</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    3
                                                </td>
                                                <td>Backup database</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="70%">
                                                        <div class="progress-bar bg-warning" data-width="70%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hasan Basri">
                                                </td>
                                                <td>2018-01-29</td>
                                                <td>
                                                    <div class="badge badge-warning">In Progress</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    4
                                                </td>
                                                <td>Input data</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Isnap Kiswandi">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Yudi Nawawi">
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Khaerul Anwar">
                                                </td>
                                                <td>2018-01-16</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Tabel Vendor') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-vendor">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    #
                                                </th>
                                                <th>Task Name</th>
                                                <th>Progress</th>
                                                <th>Members</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    1
                                                </td>
                                                <td>Create a mobile app</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Wildan Ahdian">
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    2
                                                </td>
                                                <td>Redesign homepage</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="0%">
                                                        <div class="progress-bar" data-width="0"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Nur Alpiana">
                                                    <img alt="image" src="assets/img/avatar/avatar-3.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hariono Yusup">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Bagus Dwi Cahya">
                                                </td>
                                                <td>2018-04-10</td>
                                                <td>
                                                    <div class="badge badge-info">Todo</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    3
                                                </td>
                                                <td>Backup database</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="70%">
                                                        <div class="progress-bar bg-warning" data-width="70%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Hasan Basri">
                                                </td>
                                                <td>2018-01-29</td>
                                                <td>
                                                    <div class="badge badge-warning">In Progress</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    4
                                                </td>
                                                <td>Input data</td>
                                                <td class="align-middle">
                                                    <div class="progress" data-height="4" data-toggle="tooltip"
                                                        title="100%">
                                                        <div class="progress-bar bg-success" data-width="100%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img alt="image" src="assets/img/avatar/avatar-2.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Rizal Fakhri">
                                                    <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Isnap Kiswandi">
                                                    <img alt="image" src="assets/img/avatar/avatar-4.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Yudi Nawawi">
                                                    <img alt="image" src="assets/img/avatar/avatar-1.png"
                                                        class="rounded-circle" width="35" data-toggle="tooltip"
                                                        title="Khaerul Anwar">
                                                </td>
                                                <td>2018-01-16</td>
                                                <td>
                                                    <div class="badge badge-success">Completed</div>
                                                </td>
                                                <td><a href="#" class="btn btn-secondary">Detail</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
            <div class="footer-right">

            </div>
        </footer>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
@endsection
