@extends('layouts.app')

@section('title', 'Monitoring Absensi - HRD System')

@section('content')
<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" id="kt_search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari karyawan...">
                </div>
                <!--end::Search-->
            </div>
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                    <!--begin::Export-->
                    <button type="button" class="btn btn-light-primary" id="btn-export">
                        <i class="ki-duotone ki-exit-up fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Export Data
                    </button>
                    <!--end::Export-->

                    <!--begin::Add Absen-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-absen">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Input Absen Manual
                    </button>
                    <!--end::Add Absen-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Filter-->
            <div class="row mb-5">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control form-control-solid" id="filter_tanggal" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Unit</label>
                    <select class="form-select form-select-solid" id="filter_unit">
                        <option value="0">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-primary d-block w-100" id="btn-filter">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Terapkan Filter
                    </button>
                </div>
            </div>
            <!--end::Filter-->

            <!--begin::Stats (Di bawah filter)-->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch" style="background: linear-gradient(112.14deg, #00D2FF 0%, #3A7BD5 100%);">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-white bg-opacity-20">
                                        <i class="ki-duotone ki-check-circle fs-1 text-white">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-white fs-7 fw-semibold d-block">Total Hadir</span>
                                    <span class="text-white fs-2x fw-bold" id="stat-hadir">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch" style="background: linear-gradient(112.14deg, #FF5858 0%, #F857A6 100%);">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-white bg-opacity-20">
                                        <i class="ki-duotone ki-thermometer fs-1 text-white">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-white fs-7 fw-semibold d-block">Total Sakit</span>
                                    <span class="text-white fs-2x fw-bold" id="stat-sakit">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch" style="background: linear-gradient(112.14deg, #F4D03F 0%, #16A085 100%);">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-white bg-opacity-20">
                                        <i class="ki-duotone ki-document fs-1 text-white">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-white fs-7 fw-semibold d-block">Total Izin</span>
                                    <span class="text-white fs-2x fw-bold" id="stat-izin">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch" style="background: linear-gradient(112.14deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-white bg-opacity-20">
                                        <i class="ki-duotone ki-calendar fs-1 text-white">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-white fs-7 fw-semibold d-block">Total Cuti</span>
                                    <span class="text-white fs-2x fw-bold" id="stat-cuti">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Stats-->
            <!--end::Filter-->

            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_absensi_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-50px">No</th>
                        <th class="min-w-125px">Nama</th>
                        <th class="min-w-80px">NIK</th>
                        <th class="min-w-100px">Unit</th>
                        <th class="min-w-80px">Shift</th>
                        <th class="min-w-80px">Jam Masuk</th>
                        <th class="min-w-80px">Jam Pulang</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Keterangan</th>
                        <th class="text-end min-w-70px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Container-->

<!--begin::Modal Input Absen-->
<div class="modal fade" id="modal-absen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modal-absen-title">Input Absen Manual</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <form id="form-absen">
                <input type="hidden" id="absen_id" name="absenid">
                <input type="hidden" id="user_id" name="userid">
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Karyawan</label>
                            <select class="form-select form-select-solid" id="select_karyawan" name="userid" data-dropdown-parent="#modal-absen">
                                <option value="">Pilih Karyawan</option>
                            </select>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Tanggal</label>
                            <input type="date" class="form-control form-control-solid" id="absen_tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Status</label>
                            <select class="form-select form-select-solid" id="absen_status" name="status">
                                <option value="">Pilih Status</option>
                                <option value="A">ABSEN</option>
                                <option value="S">SAKIT</option>
                                <option value="I">IZIN</option>
                                <option value="IPC">IZIN POTONG CUTI</option>
                                <option value="IPG">IZIN POTONG GAJI</option>
                                <option value="C">CUTI LAINNYA</option>
                                <option value="L">LIBUR</option>
                            </select>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">Keterangan</label>
                            <textarea class="form-control form-control-solid" id="absen_keterangan" name="keterangan" rows="3" placeholder="Keterangan tambahan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-absen">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal Input Absen-->

<!--begin::Modal Detail-->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Absensi</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div id="detail-content">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Detail-->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        console.log('=== SISTEM 2 LAYER DIMULAI ===');

        // Initialize DataTable
        var table = $('#kt_absensi_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('absensi.getData') }}",
                type: "POST",
                data: function(d) {
                    d.tanggal = $('#filter_tanggal').val();
                    d.unit = $('#filter_unit').val();
                },
                dataSrc: function(json) {
                    // LAYER 2: Terima dan tampilkan statistik dari backend
                    console.log('Response dari backend:', json);

                    if (json && json.statistics) {
                        console.log('Statistik diterima:', json.statistics);

                        // Update tampilan statistik
                        $('#stat-hadir').text(json.statistics.hadir || 0);
                        $('#stat-sakit').text(json.statistics.sakit || 0);
                        $('#stat-izin').text(json.statistics.izin || 0);
                        $('#stat-cuti').text(json.statistics.cuti || 0);

                        console.log('Statistik berhasil ditampilkan');
                    } else {
                        console.warn('Statistik tidak ditemukan dalam response');
                        // Set ke 0 jika tidak ada data
                        $('#stat-hadir').text('0');
                        $('#stat-sakit').text('0');
                        $('#stat-izin').text('0');
                        $('#stat-cuti').text('0');
                    }

                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('Ajax error:', xhr.responseText);
                    Swal.fire('Error', 'Gagal memuat data: ' + (xhr.responseJSON?.message || error), 'error');
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama', name: 'nama' },
                { data: 'nik', name: 'nik', defaultContent: '-' },
                { data: 'unit', name: 'unit', defaultContent: '-' },
                {
                    data: 'shift',
                    name: 'shift',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-') {
                            return '<span class="badge badge-light-primary">' + data + '</span>';
                        }
                        return '-';
                    }
                },
                {
                    data: 'jammasuk',
                    name: 'jammasuk',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-' && data !== null) {
                            return '<span class="badge badge-light-success">' + data + '</span>';
                        }
                        return '<span class="badge badge-light-secondary">-</span>';
                    }
                },
                {
                    data: 'jampulang',
                    name: 'jampulang',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-' && data !== null) {
                            return '<span class="badge badge-light-info">' + data + '</span>';
                        }
                        return '<span class="badge badge-light-secondary">-</span>';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    defaultContent: '-',
                    render: function(data) {
                        var badgeClass = 'light-secondary';
                        if (data) {
                            var statusUpper = data.toUpperCase();
                            if (statusUpper.includes('HADIR') || statusUpper === 'P' || statusUpper === 'P6') {
                                badgeClass = 'success';
                            } else if (statusUpper.includes('SAKIT') || statusUpper === 'S') {
                                badgeClass = 'danger';
                            } else if (statusUpper.includes('IZIN') || statusUpper === 'I' || statusUpper === 'IPC' || statusUpper === 'IPG') {
                                badgeClass = 'warning';
                            } else if (statusUpper.includes('CUTI') || statusUpper === 'C') {
                                badgeClass = 'info';
                            } else if (statusUpper.includes('ALPHA') || statusUpper === 'A' || statusUpper.includes('ABSEN')) {
                                badgeClass = 'dark';
                            } else if (statusUpper.includes('LIBUR') || statusUpper === 'L') {
                                badgeClass = 'primary';
                            } else if (statusUpper.includes('TELAT')) {
                                badgeClass = 'light-warning';
                            }
                            return '<span class="badge badge-' + badgeClass + '">' + data + '</span>';
                        }
                        return '<span class="badge badge-light-secondary">-</span>';
                    }
                },
                { data: 'keterangan', name: 'keterangan', defaultContent: '-' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-icon btn-light-info me-2 btn-detail"
                                    data-id="${row.id || ''}"
                                    data-nama="${row.nama || ''}"
                                    data-unit="${row.unit || ''}"
                                    data-jammasuk="${row.jammasuk || ''}"
                                    data-jampulang="${row.jampulang || ''}"
                                    data-status="${row.status || ''}"
                                    data-keterangan="${row.keterangan || ''}"
                                    title="Detail">
                                    <i class="ki-duotone ki-eye fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-light-primary me-2 btn-edit"
                                    data-id="${row.id || ''}"
                                    data-userid="${row.userid || ''}"
                                    data-status="${row.status || ''}"
                                    data-keterangan="${row.keterangan || ''}"
                                    title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete"
                                    data-id="${row.id || ''}"
                                    title="Hapus">
                                    <i class="ki-duotone ki-trash fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[1, 'asc']],
            language: {
                processing: "Memproses...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                loadingRecords: "Memuat...",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Tidak ada data tersedia",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Terakhir"
                }
            }
        });

        // Inisialisasi tampilan statistik
        console.log('Tabel DataTable initialized');

        // Search
        $('#kt_search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter
        $('#btn-filter').on('click', function() {
            console.log('Filter button clicked');
            table.ajax.reload(function(json) {
                console.log('Data reloaded after filter:', json);
            });
        });

        // Detail button
        $(document).on('click', '.btn-detail', function() {
            var row = table.row($(this).closest('tr')).data();
            var html = `
                <table class="table table-row-dashed">
                    <tr><th class="w-150px">Nama</th><td>${row.nama || '-'}</td></tr>
                    <tr><th>NIK</th><td>${row.nik || '-'}</td></tr>
                    <tr><th>Unit</th><td>${row.unit || '-'}</td></tr>
                    <tr><th>Shift</th><td>${row.shift || '-'}</td></tr>
                    <tr><th>Jam Masuk</th><td>${row.jammasuk || '-'}</td></tr>
                    <tr><th>Jam Pulang</th><td>${row.jampulang || '-'}</td></tr>
                    <tr><th>Status</th><td>${row.status || '-'}</td></tr>
                    <tr><th>Keterangan</th><td>${row.keterangan || '-'}</td></tr>
                </table>
            `;
            $('#detail-content').html(html);
            $('#modal-detail').modal('show');
        });

        // Edit button
        $(document).on('click', '.btn-edit', function() {
            var row = table.row($(this).closest('tr')).data();
            $('#modal-absen-title').text('Edit Absen');
            $('#absen_id').val(row.id);
            $('#user_id').val(row.userid);
            $('#absen_tanggal').val($('#filter_tanggal').val());
            $('#absen_status').val(row.status_code || row.status);
            $('#absen_keterangan').val(row.keterangan);
            $('#select_karyawan').prop('disabled', true);
            $('#modal-absen').modal('show');
        });

        // Delete button
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data absen ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('absensi.delete') }}",
                        type: 'POST',
                        data: { id: id },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                // Reload tabel dan statistik
                                table.ajax.reload(function() {
                                    console.log('Data dan statistik di-reload setelah delete');
                                });
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        }
                    });
                }
            });
        });

        // Reset form when modal opens for new entry
        $('#modal-absen').on('show.bs.modal', function(e) {
            if (!$(e.relatedTarget).hasClass('btn-edit')) {
                $('#form-absen')[0].reset();
                $('#modal-absen-title').text('Input Absen Manual');
                $('#absen_id').val('');
                $('#user_id').val('');
                $('#absen_tanggal').val($('#filter_tanggal').val());
                $('#select_karyawan').prop('disabled', false);
            }
        });

        // Form submit
        $('#form-absen').on('submit', function(e) {
            e.preventDefault();
            var $btn = $('#btn-save-absen');
            $btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            var absenId = $('#absen_id').val();
            var url = absenId ? "{{ route('absensi.update') }}" : "{{ route('absensi.input') }}";
            var data = {
                userid: $('#select_karyawan').val() || $('#user_id').val(),
                tanggal: $('#absen_tanggal').val(),
                status: $('#absen_status').val(),
                keterangan: $('#absen_keterangan').val()
            };

            if (absenId) {
                data.absenid = absenId;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    if (response.success) {
                        $('#modal-absen').modal('hide');
                        Swal.fire('Berhasil!', response.message, 'success');
                        // Reload tabel dan statistik
                        table.ajax.reload(function() {
                            console.log('Data dan statistik di-reload setelah save');
                        });
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', msg, 'error');
                }
            });
        });

        // Export button
        $('#btn-export').on('click', function() {
            var tanggal = $('#filter_tanggal').val();
            var unit = $('#filter_unit').val();
            window.open("{{ route('absensi.export') }}?tanggal=" + tanggal + "&unit=" + unit, '_blank');
        });

        // Load daftar karyawan untuk select
        loadKaryawanOptions();
    });

    function loadKaryawanOptions() {
        // Load from local karyawan data
        $.get("{{ route('karyawan.getData') }}", { length: -1 }, function(response) {
            var options = '<option value="">Pilih Karyawan</option>';
            if (response.data && response.data.length > 0) {
                response.data.forEach(function(item) {
                    options += '<option value="' + item.id + '">' + item.nikKaryawan + ' - ' + item.namaKaryawan + '</option>';
                });
            }
            $('#select_karyawan').html(options);
        });
    }
</script>
@endpush
