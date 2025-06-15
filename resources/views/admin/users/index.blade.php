@extends('layouts.admin')

@section('title', 'Data User - BANSOS KMEANS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar User</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari username/nama/email..." id="search" value="{{ request('search') }}" oninput="searchUsers(this.value)">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="role_filter" onchange="filterUsers(this.value)">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="status_filter" onchange="filterUsers(this.value)">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
            </div>
                        <div class="col-md-2">
                            <div class="d-flex justify-content-end">
                                <select class="form-control" id="entries" onchange="this.form.submit()">
                <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
            </select>
                            </div>
                        </div>
        </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 20%">Username</th>
                                    <th style="width: 20%">Nama</th>
                                    <th style="width: 25%">Email</th>
                                    <th style="width: 15%">Role</th>
                                    <th style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                                @forelse($users as $index => $user)
                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge badge-primary">Admin</span>
                                            @else
                                                <span class="badge badge-info">Kepala Desa</span>
                                            @endif
                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                                        <td colspan="7" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="text-muted">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                            </p>
            </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                {{ $users->links('pagination::simple-tailwind') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="resetPasswordForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Petunjuk Reset Password:
                        <ul class="mb-0 mt-2">
                            <li>Password baru harus minimal 8 karakter</li>
                            <li>Konfirmasi password harus sama dengan password baru</li>
                            <li>Password akan langsung aktif setelah direset</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="password" required minlength="8" placeholder="Masukkan password baru (min. 8 karakter)">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="generatePassword">
                                    <i class="fas fa-random"></i> Generate
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Password minimal 8 karakter</small>
                        <div class="invalid-feedback">Password minimal 8 karakter</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8" placeholder="Masukkan ulang password baru">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Password harus sama dengan password baru</small>
                        <div class="invalid-feedback">Password tidak cocok</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitResetPassword">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        function searchUsers(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('search', value);
            window.location.href = url.toString();
        }

    function filterUsers(value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set('role', value);
        } else {
            url.searchParams.delete('role');
        }
        window.location.href = url.toString();
    }

    function resetPassword(userId) {
        $('#resetPasswordForm').attr('action', `/admin/users/${userId}/reset-password`);
        $('#resetPasswordModal').modal('show');
    }

    $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const password = $('#new_password').val();
        const confirmation = $('#password_confirmation').val();

        // Validasi client-side
        if (password.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Password Terlalu Pendek',
                text: 'Password harus minimal 8 karakter',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (password !== confirmation) {
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Cocok',
                text: 'Password dan konfirmasi password harus sama',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Konfirmasi sebelum submit
        Swal.fire({
            title: 'Konfirmasi Reset Password',
            text: 'Apakah Anda yakin ingin mereset password user ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset Password',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim request
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#resetPasswordModal').modal('hide');
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Password berhasil direset',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mereset password';
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        
                        // Tampilkan notifikasi error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Password validation
    function validatePassword() {
        const password = $('#new_password').val();
        const confirmation = $('#password_confirmation').val();
        const submitButton = $('#submitResetPassword');
        
        if (password.length >= 8 && confirmation.length >= 8) {
            if (password === confirmation) {
                submitButton.prop('disabled', false);
                $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
                $('#new_password').removeClass('is-invalid').addClass('is-valid');
            } else {
                submitButton.prop('disabled', true);
                $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
                $('#new_password').removeClass('is-valid').addClass('is-invalid');
            }
        } else {
            submitButton.prop('disabled', true);
            if (password.length < 8) {
                $('#new_password').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#new_password').removeClass('is-invalid').addClass('is-valid');
            }
            if (confirmation.length < 8) {
                $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
            } else {
                $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
            }
        }
    }

    $('#new_password, #password_confirmation').on('input', validatePassword);

    // Generate password
    $('#generatePassword').on('click', function() {
        try {
            // Fungsi untuk mendapatkan karakter acak dari string
            function getRandomChar(str) {
                return str.charAt(Math.floor(Math.random() * str.length));
            }

            // Karakter yang akan digunakan
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const numbers = '0123456789';
            const special = '!@#$%^&*';

            // Pastikan password memiliki minimal 1 karakter dari setiap jenis
            let password = '';
            password += getRandomChar(lowercase); // 1 huruf kecil
            password += getRandomChar(uppercase); // 1 huruf besar
            password += getRandomChar(numbers);   // 1 angka
            password += getRandomChar(special);   // 1 karakter khusus

            // Tambahkan karakter acak hingga panjang 12
            const allChars = lowercase + uppercase + numbers + special;
            for (let i = password.length; i < 12; i++) {
                password += getRandomChar(allChars);
            }

            // Acak urutan karakter
            password = password.split('').sort(() => Math.random() - 0.5).join('');

            // Set nilai ke input
            $('#new_password').val(password);
            $('#password_confirmation').val(password);
            
            // Trigger validasi
            validatePassword();
            
            // Tampilkan password
            $('#new_password').attr('type', 'text');
            $('#password_confirmation').attr('type', 'text');
            $('#togglePassword, #toggleConfirmPassword').find('i').removeClass('fa-eye').addClass('fa-eye-slash');

            // Tampilkan notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'Password Berhasil Digenerate',
                text: 'Password baru telah dibuat dan siap digunakan',
                timer: 2000,
                showConfirmButton: false
            });
        } catch (error) {
            // Tampilkan notifikasi error
            Swal.fire({
                icon: 'error',
                title: 'Gagal Generate Password',
                text: 'Terjadi kesalahan saat membuat password baru',
                confirmButtonText: 'Coba Lagi'
            });
        }
    });

    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#new_password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#toggleConfirmPassword').on('click', function() {
        const passwordInput = $('#password_confirmation');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Reset form when modal is closed
    $('#resetPasswordModal').on('hidden.bs.modal', function() {
        $('#resetPasswordForm')[0].reset();
        $('#new_password, #password_confirmation').removeClass('is-valid is-invalid');
        $('#submitResetPassword').prop('disabled', true);
        $('#new_password, #password_confirmation').attr('type', 'password');
        $('#togglePassword, #toggleConfirmPassword').find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    });
    </script>
@endsection