@extends('layouts.app')

@section('title', 'Data Lembur - HRD System')

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
                    <!--begin::Add Lembur-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-lembur">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Input Lembur
                    </button>
                    <!--end::Add Lembur-->
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
                    <label class="form-label">Bulan</label>
                    <select class="form-select form-select-solid" id="filter_bulan">
                        <option value="01" {{ date('m') == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ date('m') == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ date('m') == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ date('m') == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ date('m') == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ date('m') == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ date('m') == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ date('m') == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ date('m') == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ date('m') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ date('m') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ date('m') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select class="form-select form-select-solid" id="filter_tahun">
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
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

            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_lembur_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-50px">No</th>
                        <th class="min-w-125px">Nama</th>
                        <th class="min-w-100px">Unit</th>
                        <th class="min-w-100px">Tanggal</th>
                        <th class="min-w-80px">Jam Masuk</th>
                        <th class="min-w-80px">Jam Pulang</th>
                        <th class="min-w-80px">Durasi</th>
                        <th class="min-w-150px">Keterangan</th>
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

<!--begin::Modal Input Lembur-->
<div class="modal fade" id="modal-lembur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modal-lembur-title">Input Lembur</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <form id="form-lembur">
                <input type="hidden" id="lembur_absenid" name="absenid">
                <input type="hidden" id="lembur_userid_hidden" name="userid_hidden">
                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Karyawan</label>
                            <select class="form-select form-select-solid" id="lembur_karyawan" name="userid" data-dropdown-parent="#modal-lembur">
                                <option value="">Pilih Karyawan</option>
                            </select>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Tanggal</label>
                            <input type="date" class="form-control form-control-solid" id="lembur_tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Jam Masuk</label>
                                <input type="time" class="form-control form-control-solid" id="lembur_jammasuk" name="jammasuk">
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Jam Pulang</label>
                                <input type="time" class="form-control form-control-solid" id="lembur_jampulang" name="jampulang">
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">Keterangan</label>
                            <textarea class="form-control form-control-solid" id="lembur_keterangan" name="keterangan" rows="3" placeholder="Keterangan lembur..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-lembur">
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
<!--end::Modal Input Lembur-->
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

        // Initialize DataTable
        var table = $('#kt_lembur_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('absensi.getDataLembur') }}",
                type: "POST",
                data: function(d) {
                    d.bulan = $('#filter_bulan').val();
                    d.tahun = $('#filter_tahun').val();
                },
                error: function(xhr, error, thrown) {
                    console.log('Ajax error:', xhr.responseText);
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
                { data: 'unit', name: 'unit', defaultContent: '-' },
                {
                    data: 'tanggal',
                    name: 'tanggal',
                    render: function(data) {
                        if (data) {
                            var date = new Date(data);
                            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                        }
                        return '-';
                    }
                },
                {
                    data: 'jammasuk',
                    name: 'jammasuk',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-') {
                            return '<span class="badge badge-light-primary">' + data + '</span>';
                        }
                        return '<span class="badge badge-light-secondary">-</span>';
                    }
                },
                {
                    data: 'jampulang',
                    name: 'jampulang',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-') {
                            return '<span class="badge badge-light-info">' + data + '</span>';
                        }
                        return '<span class="badge badge-light-secondary">-</span>';
                    }
                },
                {
                    data: 'durasi',
                    name: 'durasi',
                    defaultContent: '-',
                    render: function(data) {
                        if (data && data !== '-') {
                            return '<span class="badge badge-light-success">' + data + '</span>';
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
                                <button type="button" class="btn btn-sm btn-icon btn-light-primary me-2 btn-edit"
                                    data-id="${row.id || ''}"
                                    data-userid="${row.userid || ''}"
                                    data-tanggal="${row.tanggal || ''}"
                                    data-jammasuk="${row.jammasuk || ''}"
                                    data-jampulang="${row.jampulang || ''}"
                                    data-keterangan="${row.keterangan || ''}"
                                    title="Edit">
                                    <i class="ki-duotone ki-pencil fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[2, 'desc']],
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

        // Search
        $('#kt_search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter
        $('#btn-filter').on('click', function() {
            table.ajax.reload();
        });

        // Edit button
        $(document).on('click', '.btn-edit', function() {
            var data = $(this).data();
            $('#modal-lembur-title').text('Edit Lembur');
            $('#lembur_absenid').val(data.id);
            $('#lembur_userid_hidden').val(data.userid);
            $('#lembur_tanggal').val(data.tanggal);
            $('#lembur_jammasuk').val(data.jammasuk);
            $('#lembur_jampulang').val(data.jampulang);
            $('#lembur_keterangan').val(data.keterangan);
            // Set selected value dan disable saat edit
            $('#lembur_karyawan').val(data.userid);
            $('#lembur_karyawan').prop('disabled', true);
            $('#modal-lembur').modal('show');
        });

        // Reset form when modal opens for new entry
        $('#modal-lembur').on('show.bs.modal', function(e) {
            if (!$(e.relatedTarget).hasClass('btn-edit')) {
                $('#form-lembur')[0].reset();
                $('#modal-lembur-title').text('Input Lembur');
                $('#lembur_absenid').val('');
                $('#lembur_userid_hidden').val('');
                $('#lembur_tanggal').val('{{ date("Y-m-d") }}');
                $('#lembur_karyawan').prop('disabled', false);
            }
        });

        // Form submit
        $('#form-lembur').on('submit', function(e) {
            e.preventDefault();
            var $btn = $('#btn-save-lembur');
            $btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            var absenId = $('#lembur_absenid').val();
            var url = absenId ? "{{ route('absensi.updateLembur') }}" : "{{ route('absensi.inputLembur') }}";
            var data = {
                userid: $('#lembur_karyawan').val() || $('#lembur_userid_hidden').val(),
                tanggal: $('#lembur_tanggal').val(),
                jammasuk: $('#lembur_jammasuk').val(),
                jampulang: $('#lembur_jampulang').val(),
                keterangan: $('#lembur_keterangan').val()
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
                        $('#modal-lembur').modal('hide');
                        Swal.fire('Berhasil!', response.message, 'success');
                        table.ajax.reload();
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

        // Load daftar karyawan untuk select
        loadKaryawanOptions();
    });

    function loadKaryawanOptions() {
        // Load from API absensi
        $.ajax({
            url: "{{ route('absensi.user-list') }}",
            type: 'GET',
            success: function(response) {
                var options = '<option value="">Pilih Karyawan</option>';
                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        options += '<option value="' + item.id + '">' + (item.nik || '') + ' - ' + (item.nama || item.name || '') + '</option>';
                    });
                }
                $('#lembur_karyawan').html(options);
            },
            error: function() {
                console.error('Gagal load daftar karyawan dari API');
                $('#lembur_karyawan').html('<option value="">Gagal memuat data</option>');
            }
        });
    }
</script>
@endpush
