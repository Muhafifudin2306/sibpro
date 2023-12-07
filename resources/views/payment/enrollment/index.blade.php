@extends('layouts.admin.app')

@section('title_page', 'Credit Payment')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    @endpush

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Pembayaran Daftar Ulang') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">{{ __('Dashboard') }}</div>
                        <div class="breadcrumb-item">{{ __('Pembayaran') }}</div>
                        <div class="breadcrumb-item active">{{ __('Daftar Ulang') }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="title-content">
                        <h2 class="section-title">{{ __('Pembayaran Daftar Ulang') }}</h2>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#">All <span
                                                    class="badge badge-white">5</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Draft <span
                                                    class="badge badge-primary">1</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Pending <span
                                                    class="badge badge-primary">1</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#">Trash <span
                                                    class="badge badge-primary">0</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Posts</h4>
                                </div>
                                <div class="card-body">
                                    <div class="float-left">
                                        <select class="form-control selectric">
                                            <option>Action For Selected</option>
                                            <option>Move to Draft</option>
                                            <option>Move to Pending</option>
                                            <option>Delete Pemanently</option>
                                        </select>
                                    </div>
                                    <div class="float-right">
                                        <form>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="clearfix mb-3"></div>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Author</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                            </tr>
                                            <tr>
                                                <td>Laravel 5 Tutorial: Introduction
                                                    <div class="table-links">
                                                        <a href="#">View</a>
                                                        <div class="bullet"></div>
                                                        <a href="#">Edit</a>
                                                        <div class="bullet"></div>
                                                        <a href="#" class="text-danger">Trash</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#">Web Developer</a>,
                                                    <a href="#">Tutorial</a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                            class="rounded-circle" width="35" data-toggle="title"
                                                            title="">
                                                        <div class="d-inline-block ml-1">Rizal Fakhri</div>
                                                    </a>
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-primary">Published</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Laravel 5 Tutorial: Installing
                                                    <div class="table-links">
                                                        <a href="#">View</a>
                                                        <div class="bullet"></div>
                                                        <a href="#">Edit</a>
                                                        <div class="bullet"></div>
                                                        <a href="#" class="text-danger">Trash</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#">Web Developer</a>,
                                                    <a href="#">Tutorial</a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                            class="rounded-circle" width="35" data-toggle="title"
                                                            title="">
                                                        <div class="d-inline-block ml-1">Rizal Fakhri</div>
                                                    </a>
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-primary">Published</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Laravel 5 Tutorial: MVC
                                                    <div class="table-links">
                                                        <a href="#">View</a>
                                                        <div class="bullet"></div>
                                                        <a href="#">Edit</a>
                                                        <div class="bullet"></div>
                                                        <a href="#" class="text-danger">Trash</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#">Web Developer</a>,
                                                    <a href="#">Tutorial</a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                            class="rounded-circle" width="35" data-toggle="title"
                                                            title="">
                                                        <div class="d-inline-block ml-1">Rizal Fakhri</div>
                                                    </a>
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-primary">Published</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Laravel 5 Tutorial: CRUD
                                                    <div class="table-links">
                                                        <a href="#">View</a>
                                                        <div class="bullet"></div>
                                                        <a href="#">Edit</a>
                                                        <div class="bullet"></div>
                                                        <a href="#" class="text-danger">Trash</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#">Web Developer</a>,
                                                    <a href="#">Tutorial</a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                            class="rounded-circle" width="35" data-toggle="title"
                                                            title="">
                                                        <div class="d-inline-block ml-1">Rizal Fakhri</div>
                                                    </a>
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-danger">Draft</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Laravel 5 Tutorial: Deployment
                                                    <div class="table-links">
                                                        <a href="#">View</a>
                                                        <div class="bullet"></div>
                                                        <a href="#">Edit</a>
                                                        <div class="bullet"></div>
                                                        <a href="#" class="text-danger">Trash</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#">Web Developer</a>,
                                                    <a href="#">Tutorial</a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <img alt="image" src="assets/img/avatar/avatar-5.png"
                                                            class="rounded-circle" width="35" data-toggle="title"
                                                            title="">
                                                        <div class="d-inline-block ml-1">Rizal Fakhri</div>
                                                    </a>
                                                </td>
                                                <td>2018-01-20</td>
                                                <td>
                                                    <div class="badge badge-warning">Pending</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="float-right">
                                        <nav>
                                            <ul class="pagination">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                </li>
                                                <li class="page-item active">
                                                    <a class="page-link" href="#">1</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                {{ __('Development by Muhammad Afifudin') }}</a>
            </div>
        </footer>
    </div>
    @push('scripts')
    @endpush
@endsection
