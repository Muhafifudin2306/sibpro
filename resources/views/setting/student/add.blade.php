@extends('layouts.admin.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <x-navbarAdmin :notifications="$notifications"></x-navbarAdmin>
        <x-sidebarAdmin></x-sidebarAdmin>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>{{ __('Data Atribut') }}</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active">Dashboard</div>
                        <div class="breadcrumb-item active">General Setting</div>
                        <div class="breadcrumb-item active">Siswa</div>
                        <div class="breadcrumb-item">Tambah</div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Tambah Siswa</h4>
                            <a href="{{ url('/setting/student') }}"> Close </a>
                        </div>
                        <div class="card-body pb-5">
                            <div class="form-group">
                                <div class="section-title">Data Kelas</div>
                                <label>Kelas</label>
                                <select class="form-control select2" name="class_id">
                                    <option>-- Pilih Kelas --</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"> {{ $item->class_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="section-title">Data Siswa</div>
                                <div id="studentFields">
                                    <div class="row student-row">
                                        <div class="col-5 col-md-4">
                                            <div class="form-group">
                                                <label for="nis-input">NIS</label>
                                                <input type="text" class="form-control" name="nis"
                                                    placeholder="NIS Siswa ke-i">
                                            </div>
                                        </div>
                                        <div class="col-5 col-md-6">
                                            <div class="form-group">
                                                <label for="student-input">Nama Siswa</label>
                                                <input type="text" class="form-control" name="student_name"
                                                    placeholder="Nama Siswa ke-i">
                                            </div>
                                        </div>
                                        <div class="col-2 col-md-2">
                                            <div class="form-group">
                                                <label>Add</label>
                                                <button id="addButton" class="btn btn-success w-100"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button id="saveButton" type="button" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Development by Muhammad Afifudin</a>
            </div>
        </footer>
    </div>
    <div class="col-2 col-md-2">
        <div class="form-group">
            <label>Tambah Field</label>
            <button id="addButton" class="btn btn-success w-100"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addButton = document.getElementById("addButton");
            const studentFields = document.getElementById("studentFields");

            addButton.addEventListener("click", function() {
                const newStudentRow = document.createElement("div");
                newStudentRow.className = "row student-row";
                newStudentRow.innerHTML = `
                <div class="col-5 col-md-4">
                    <div class="form-group">
                        <label for="nis-input">NIS</label>
                        <input type="text" class="form-control" name="nis" placeholder="NIS Siswa ke-i">
                    </div>
                </div>
                <div class="col-5 col-md-6">
                    <div class="form-group">
                        <label for="student-input">Nama Siswa</label>
                        <input type="text" class="form-control" name="student_name" placeholder="Nama Siswa ke-i">
                    </div>
                </div>
                <div class="col-2 col-md-2">
                    <div class="form-group">
                        <label>Remove</label>
                        <button id="addButton" class="btn btn-danger w-100 remove-button"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                `;
                studentFields.appendChild(newStudentRow);

                const removeButtons = document.querySelectorAll(".remove-button");
                removeButtons.forEach(function(button) {
                    button.addEventListener("click", function() {
                        studentFields.removeChild(button.closest(".student-row"));
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add button functionality (as before)

            const saveButton = document.getElementById("saveButton");

            saveButton.addEventListener("click", function() {
                const class_id = document.querySelector('select[name="class_id"]').value;
                const studentRows = document.querySelectorAll('.student-row');

                const students = Array.from(studentRows).map(row => ({
                    nis: row.querySelector('input[name="nis"]').value,
                    student_name: row.querySelector('input[name="student_name"]').value
                }));

                const data = {
                    class_id: class_id,
                    students: students
                };

                axios.post('/setting/student/store', data)
                    .then(response => {
                        Notiflix.Notify.success("Data Siswa berhasil ditambah.", {
                            timeout: 3000 // Waktu dalam milidetik (3 detik dalam contoh ini)
                        });
                        window.location.href = "{{ url('/setting/student') }}";
                    })
                    .catch(error => {
                        // Handle error if needed
                    });
            });
        });
    </script>
@endsection

@section('script')
    <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
