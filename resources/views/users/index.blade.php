@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
   

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search_user" class="form-control form-control-solid w-250px ps-13" placeholder="Cari user..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary" onclick="addUser()">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Tambah User
                        </button>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-125px">User</th>
                                <th class="min-w-125px">Email</th>
                                <th class="min-w-100px">Role</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-100px">Tanggal Dibuat</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->
</div>

<!--begin::Modal - Add/Edit User-->
<div class="modal fade" id="kt_modal_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_user_header">
                <h2 class="fw-bold" id="modal_title">Tambah User</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body px-5 my-7">
                <form id="kt_modal_user_form" class="form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">

                    <!--begin::Input group - Avatar-->
                    <div class="fv-row mb-7 text-center">
                        <label class="d-block fw-semibold fs-6 mb-5">Avatar</label>
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('assets/media/avatars/blank.png') }}');" id="avatar_preview"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" id="avatar_input" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                    </div>

                    <!--begin::Input group - Name-->
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Nama</label>
                        <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama lengkap" />
                    </div>

                    <!--begin::Input group - Email-->
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                        <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Email" />
                    </div>

                    <!--begin::Input group - Password-->
                    <div class="fv-row mb-7" id="password_group">
                        <label class="fw-semibold fs-6 mb-2" id="password_label">
                            <span class="required">Password</span>
                        </label>
                        <input type="password" name="password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Password" />
                    </div>

                    <!--begin::Input group - Password Confirmation-->
                    <div class="fv-row mb-7" id="password_confirmation_group">
                        <label class="fw-semibold fs-6 mb-2" id="password_confirmation_label">
                            <span class="required">Konfirmasi Password</span>
                        </label>
                        <input type="password" name="password_confirmation" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Konfirmasi password" />
                    </div>

                    <!--begin::Input group - Role-->
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Role</label>
                        <select name="role" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Pilih role">
                            <option value="">Pilih role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <!--begin::Input group - Status-->
                    <div class="fv-row mb-7">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked />
                            <label class="form-check-label fw-semibold fs-6" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>

                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Mohon tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Add/Edit User-->
@endsection

@push('scripts')
<script>
let table;

$(document).ready(function() {
    // Initialize DataTable
    table = $('#kt_users_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.data') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            ${row.avatar_display}
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 mb-1">${data}</span>
                            </div>
                        </div>
                    `;
                }
            },
            {data: 'email', name: 'email'},
            {data: 'role_badge', name: 'role'},
            {data: 'status', name: 'is_active'},
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    return moment(data).format('DD MMM YYYY');
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[5, 'desc']],
        pageLength: 10,
        language: {
            processing: "Memuat...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            loadingRecords: "Memuat...",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data yang tersedia",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Search functionality
    $('#search_user').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Handle form submission
    $('#kt_modal_user_form').on('submit', function(e) {
        e.preventDefault();

        const submitButton = $(this).find('button[type="submit"]');
        submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);

        const formData = new FormData(this);
        const userId = $('#user_id').val();
        const url = userId ? `/users/${userId}` : "{{ route('users.store') }}";

        if (userId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#kt_modal_user').modal('hide');
                table.ajax.reload();

                Swal.fire({
                    text: response.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    html: errorMessage,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            },
            complete: function() {
                submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
            }
        });
    });

    // Reset modal on close
    $('#kt_modal_user').on('hidden.bs.modal', function() {
        $('#kt_modal_user_form')[0].reset();
        $('#user_id').val('');
        $('#modal_title').text('Tambah User');
        $('#password_label span').addClass('required');
        $('#password_confirmation_label span').addClass('required');
        $('input[name="password"]').prop('required', true);
        $('input[name="password_confirmation"]').prop('required', true);
        $('#avatar_preview').css('background-image', 'url("{{ asset('assets/media/avatars/blank.png') }}")');
    });
});

// Add user
function addUser() {
    $('#kt_modal_user').modal('show');
}

// Edit user
function editUser(id) {
    $.ajax({
        url: `/users/${id}`,
        type: 'GET',
        success: function(user) {
            $('#user_id').val(user.id);
            $('#modal_title').text('Edit User');
            $('input[name="name"]').val(user.name);
            $('input[name="email"]').val(user.email);
            $('select[name="role"]').val(user.role).trigger('change');
            $('#is_active').prop('checked', user.is_active);

            // Make password optional when editing
            $('#password_label span').removeClass('required');
            $('#password_confirmation_label span').removeClass('required');
            $('input[name="password"]').prop('required', false);
            $('input[name="password_confirmation"]').prop('required', false);

            // Set avatar preview
            if (user.avatar) {
                $('#avatar_preview').css('background-image', 'url("' + "{{ asset('storage') }}/" + user.avatar + '")');
            }

            $('#kt_modal_user').modal('show');
        }
    });
}

// Delete user
function deleteUser(id) {
    Swal.fire({
        text: "Apakah Anda yakin ingin menghapus user ini?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary"
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/users/${id}`,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    table.ajax.reload();

                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        text: "Terjadi kesalahan saat menghapus user",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        }
    });
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endpush
