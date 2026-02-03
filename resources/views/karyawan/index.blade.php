@extends('layouts.app')

@section('title', 'Data Karyawan - HRD System')

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
                    <!--begin::Delete All-->
                    <button type="button" class="btn btn-light-danger" id="btn-delete-all">
                        <i class="ki-duotone ki-trash fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Hapus Semua
                    </button>
                    <!--end::Delete All-->

                    <!--begin::Export-->
                    <button type="button" class="btn btn-light-primary" id="btn-export">
                        <i class="ki-duotone ki-exit-up fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Export Excel
                    </button>
                    <!--end::Export-->

                    <!--begin::Import-->
                    <button type="button" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#modal-import">
                        <i class="ki-duotone ki-exit-down fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Import Excel
                    </button>
                    <!--end::Import-->

                    <!--begin::Add-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Tambah Karyawan
                    </button>
                    <!--end::Add-->
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
                    <label class="form-label">Filter Unit</label>
                    <select class="form-select form-select-solid" id="filter_unit">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Status</label>
                    <select class="form-select form-select-solid" id="filter_status">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-light-primary d-block w-100" id="btn-reset-filter">
                        <i class="ki-duotone ki-arrows-circle fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Reset Filter
                    </button>
                </div>
            </div>
            <!--end::Filter-->

            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_karyawan_table">
                 <thead class="bg-light-primary">
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-50px">No</th>
                        <th class="min-w-125px">NIK Karyawan</th>
                        <th class="min-w-200px">Nama Karyawan</th>
                        <th class="min-w-125px">NIK KTP</th>
                        <th class="min-w-150px">Unit</th>
                        <th class="min-w-100px">Golongan</th>
                        <th class="min-w-150px">Profesi</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-150px">Tempat Lahir</th>
                        <th class="min-w-125px">Tanggal Lahir</th>
                        <th class="min-w-150px">Umur</th>
                        <th class="min-w-125px">Tanggal Pensiun</th>
                        <th class="min-w-100px">Jenis Kelamin</th>
                        <th class="min-w-125px">Tgl Masuk Kerja</th>
                        <th class="min-w-125px">SK Tetap</th>
                        <th class="min-w-100px">Pendidikan</th>
                        <th class="min-w-200px">Tamatan</th>
                        <th class="min-w-125px">No HP</th>
                        <th class="min-w-150px">Email</th>
                        <th class="text-end min-w-125px">Aksi</th>
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

<!--begin::Modal Form-->
<div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modal-title">Tambah Data Karyawan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="form-karyawan" class="form">
                    <input type="hidden" id="karyawan_id" name="id">

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">NIK Karyawan</label>
                            <input type="text" class="form-control" name="nikKry" id="nikKry" placeholder="Masukkan NIK Karyawan">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" name="namaKaryawan" id="namaKaryawan" placeholder="Masukkan Nama Karyawan">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">NIK KTP</label>
                            <input type="text" class="form-control" name="nikKtp" id="nikKtp" placeholder="Masukkan NIK KTP" maxlength="16">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label">Unit</label>
                            <input type="text" class="form-control" name="unit" id="unit" placeholder="Masukkan Unit">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Golongan</label>
                            <input type="text" class="form-control" name="gol" id="gol" placeholder="Masukkan Golongan">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label">Profesi</label>
                            <input type="text" class="form-control" name="profesi" id="profesi" placeholder="Masukkan Profesi">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Status Pegawai</label>
                            <select class="form-select" name="statusPegawai" id="statusPegawai">
                                <option value="">Pilih Status</option>
                                <option value="Tetap">Tetap</option>
                                <option value="PKWT">PKWT</option>
                                <option value="Kontrak">Kontrak</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenisKelamin" id="jenisKelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempatLahir" id="tempatLahir" placeholder="Masukkan Tempat Lahir">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="required form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tglLahir" id="tglLahir">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Masuk Kerja</label>
                            <input type="date" class="form-control" name="tglMulaiKerja" id="tglMulaiKerja">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SK Tetap (No. SK)</label>
                            <input type="text" class="form-control" name="skTetap" id="skTetap" placeholder="Contoh: 82/SK/DE/YARSI/VI-2015">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan</label>
                            <input type="text" class="form-control" name="pendidikan" id="pendidikan" placeholder="Contoh: S1">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tamatan</label>
                            <input type="text" class="form-control" name="tamatan" id="tamatan" placeholder="Universitas - Jurusan">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="noHp" id="noHp" placeholder="08xxxxxxxxxx">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="email@example.com">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" placeholder="Masukkan Alamat Lengkap"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Mohon tunggu...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Form-->

<!--begin::Modal Detail-->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Data Karyawan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Card-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Informasi Pribadi</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">NIK Karyawan</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-nikKry"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Nama Karyawan</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-namaKaryawan"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">NIK KTP</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-nikKtp"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Tempat, Tanggal Lahir</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-tempatTglLahir"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Umur</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-umur"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Pensiun (56 Tahun)</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-tglPensiun"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Jenis Kelamin</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-jenisKelamin"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">No HP</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-noHp"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Email</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-email"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Alamat</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-alamat"></span>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

                <!--begin::Card-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Informasi Pekerjaan</h3>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Unit</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-unit"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Golongan</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-gol"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Profesi</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-profesi"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Status Pegawai</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-statusPegawai"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Masuk Kerja</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-tglMulaiKerja"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">SK Tetap</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-skTetap"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Pendidikan</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-pendidikan"></span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">Tamatan</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800" id="detail-tamatan"></span>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
</div>
<!--end::Modal Detail-->

<!--begin::Modal Import-->
<div class="modal fade" id="modal-import" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Import Data Karyawan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="form-import" class="form" enctype="multipart/form-data">
                    <div class="mb-5">
                        <label class="form-label">File Excel</label>
                        <input type="file" class="form-control" name="file" id="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">Format: .xlsx, .xls, .csv (Max: 2MB)</div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-information fs-2hx text-info me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-column">
                            <h5 class="mb-1">Download Template</h5>
                            <span>Pastikan format file sesuai dengan template yang disediakan.</span>
                            <a href="{{ route('karyawan.download-template') }}" class="btn btn-sm btn-light-primary mt-3">
                                <i class="ki-duotone ki-file-down fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Download Template
                            </a>
                        </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-import">
                            <span class="indicator-label">Import</span>
                            <span class="indicator-progress">Mohon tunggu...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Modal Import-->

@endsection

@push('styles')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
"use strict";

var KTKaryawanList = function() {
    var table;
    var dt;

    console.log('=== INITIALIZING KARYAWAN LIST ===');

    var initDatatable = function() {
        console.log('Initializing DataTable...');
        console.log('Route URL:', "{{ route('karyawan.getData') }}");

        dt = $("#kt_karyawan_table").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('karyawan.getData') }}",
                type: 'GET',
                data: function(d) {
                    d.unit = $('#filter_unit').val();
                    d.status = $('#filter_status').val();
                    console.log('Request data:', d);
                },
                dataSrc: function(json) {
                    console.log('Response received:', json);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('Status:', xhr.status);

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data karyawan. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nikKry', name: 'nikKry'},
                {data: 'namaKaryawan', name: 'namaKaryawan'},
                {data: 'nikKtp', name: 'nikKtp'},
                {data: 'unit', name: 'unit'},
                {data: 'gol', name: 'gol'},
                {data: 'profesi', name: 'profesi'},
                {data: 'statusPegawai', name: 'statusPegawai'},
                {data: 'tempatLahir', name: 'tempatLahir'},
                {data: 'tglLahir', name: 'tglLahir'},
                {data: 'umur', name: 'umur', orderable: false, searchable: false},
                {data: 'tanggal_pensiun', name: 'tanggal_pensiun', orderable: false, searchable: false},
                {data: 'jenisKelamin', name: 'jenisKelamin'},
                {data: 'tglMulaiKerja', name: 'tglMulaiKerja', render: function(data) { return data ? data : '-'; }},
                {data: 'skTetap', name: 'skTetap', render: function(data) { return data ? data : '-'; }},
                {data: 'pendidikan', name: 'pendidikan', render: function(data) { return data ? data : '-'; }},
                {data: 'tamatan', name: 'tamatan', render: function(data) { return data ? data : '-'; }},
                {data: 'noHp', name: 'noHp', render: function(data) { return data ? data : '-'; }},
                {data: 'email', name: 'email', render: function(data) { return data ? data : '-'; }},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'}
            ],
            order: [[1, 'asc']],
            columnDefs: [
                {
                    targets: 0,
                    className: 'text-center'
                }
            ],
            language: {
                processing: "Memuat data...",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        table = dt.$;

        // Re-init functions on every table re-draw - REMOVED (using event delegation instead)
    };

    // Search Datatable
    var handleSearchDatatable = function() {
        const filterSearch = document.querySelector('#kt_search');
        filterSearch.addEventListener('keyup', function(e) {
            dt.search(e.target.value).draw();
        });
    };

    // Handle Filter
    var handleFilter = function() {
        $('#filter_unit, #filter_status').on('change', function() {
            dt.draw();
        });

        $('#btn-reset-filter').on('click', function() {
            $('#filter_unit').val('').trigger('change');
            $('#filter_status').val('').trigger('change');
            dt.draw();
        });
    };

    // Handle Add
    var handleAdd = function() {
        // Handle click on Add button
        $('[data-bs-target="#modal-form"]').on('click', function() {
            $('#modal-title').text('Tambah Data Karyawan');
            $('#form-karyawan')[0].reset();
            $('#karyawan_id').val('');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });

        // Handle modal hide
        $('#modal-form').on('hidden.bs.modal', function() {
            $('#form-karyawan')[0].reset();
            $('#karyawan_id').val('');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });
    };

    // Handle Edit
    var handleEditRows = function() {
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/karyawan/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data.karyawan;
                        $('#modal-title').text('Edit Data Karyawan');
                        $('#karyawan_id').val(data.id);
                        $('#nikKry').val(data.nikKry);
                        $('#namaKaryawan').val(data.namaKaryawan);
                        $('#nikKtp').val(data.nikKtp);
                        $('#unit').val(data.unit);
                        $('#gol').val(data.gol);
                        $('#profesi').val(data.profesi);
                        $('#statusPegawai').val(data.statusPegawai);
                        $('#tempatLahir').val(data.tempatLahir);
                        $('#tglLahir').val(data.tglLahir);
                        $('#tglMulaiKerja').val(data.tglMulaiKerja);
                        $('#jenisKelamin').val(data.jenisKelamin);
                        $('#skTetap').val(data.skTetap);
                        $('#pendidikan').val(data.pendidikan);
                        $('#tamatan').val(data.tamatan);
                        $('#noHp').val(data.noHp);
                        $('#email').val(data.email);
                        $('#alamat').val(data.alamat);

                        // Clear validation errors
                        $('.form-control, .form-select').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

                        $('#modal-form').modal('show');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        text: "Gagal mengambil data karyawan",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    };

    // Handle Detail
    var handleDetailRows = function() {
        $(document).on('click', '.btn-detail', function() {
            const id = $(this).data('id');

            $.ajax({
                url: `/karyawan/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data.karyawan;
                        const umurTahun = response.data.umur_tahun || 0;
                        const umurBulan = response.data.umur_bulan || 0;
                        const umurBulanSisa = umurBulan % 12;
                        const tglLahirFormatted = response.data.tgl_lahir_formatted || '-';
                        const tglMulaiKerjaFormatted = response.data.tgl_masuk_kerja_formatted || '-';
                        const tglPensiunFormatted = response.data.tgl_pensiun_formatted || '-';

                        // Fill detail modal
                        $('#detail-nikKry').text(data.nikKry || '-');
                        $('#detail-namaKaryawan').text(data.namaKaryawan || '-');
                        $('#detail-nikKtp').text(data.nikKtp || '-');
                        $('#detail-tempatTglLahir').text((data.tempatLahir || '-') + ', ' + tglLahirFormatted);
                        $('#detail-umur').text(umurTahun + ' tahun ' + umurBulanSisa + ' bulan');
                        $('#detail-tglPensiun').text(tglPensiunFormatted);
                        $('#detail-jenisKelamin').text(data.jenisKelamin || '-');
                        $('#detail-noHp').text(data.noHp || '-');
                        $('#detail-email').text(data.email || '-');
                        $('#detail-alamat').text(data.alamat || '-');
                        $('#detail-unit').text(data.unit || '-');
                        $('#detail-gol').text(data.gol || '-');
                        $('#detail-profesi').text(data.profesi || '-');
                        $('#detail-statusPegawai').text(data.statusPegawai || '-');
                        $('#detail-tglMulaiKerja').text(tglMulaiKerjaFormatted);
                        $('#detail-skTetap').text(data.skTetap || '-');
                        $('#detail-pendidikan').text(data.pendidikan || '-');
                        $('#detail-tamatan').text(data.tamatan || '-');

                        $('#modal-detail').modal('show');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        text: "Gagal mengambil data karyawan. " + (xhr.responseJSON?.message || 'Terjadi kesalahan'),
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    };

    // Handle Delete
    var handleDeleteRows = function() {
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            Swal.fire({
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-light"
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: `/karyawan/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    text: response.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function() {
                                    dt.draw();
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Gagal menghapus data karyawan';
                            let errorDetails = '';

                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                if (xhr.responseJSON.error) {
                                    errorDetails = '<div class="mt-3 alert alert-danger text-start">' +
                                                 '<strong>Detail Error:</strong><br>' +
                                                 xhr.responseJSON.error +
                                                 '</div>';
                                }
                            }

                            Swal.fire({
                                title: 'Error!',
                                html: errorMessage + errorDetails,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });
        });
    };

    // Handle Submit Form
    var handleSubmitForm = function() {
        $('#form-karyawan').on('submit', function(e) {
            e.preventDefault();

            const submitButton = document.querySelector('#btn-submit');
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            const id = $('#karyawan_id').val();
            const url = id ? `/karyawan/${id}` : '{{ route("karyawan.store") }}';
            const method = id ? 'PUT' : 'POST';

            let formData = {
                _token: '{{ csrf_token() }}',
                nikKry: $('#nikKry').val(),
                namaKaryawan: $('#namaKaryawan').val(),
                nikKtp: $('#nikKtp').val(),
                unit: $('#unit').val(),
                gol: $('#gol').val(),
                profesi: $('#profesi').val(),
                statusPegawai: $('#statusPegawai').val(),
                tempatLahir: $('#tempatLahir').val(),
                tglLahir: $('#tglLahir').val(),
                tglMulaiKerja: $('#tglMulaiKerja').val(),
                jenisKelamin: $('#jenisKelamin').val(),
                skTetap: $('#skTetap').val(),
                pendidikan: $('#pendidikan').val(),
                tamatan: $('#tamatan').val(),
                noHp: $('#noHp').val(),
                email: $('#email').val(),
                alamat: $('#alamat').val()
            };

            if (method === 'PUT') {
                formData._method = 'PUT';
            }

            // Clear previous errors
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (response.success) {
                        $('#modal-form').modal('hide');
                        $('#form-karyawan')[0].reset();

                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            dt.draw();
                        });
                    }
                },
                error: function(xhr) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorList = '<ul class="text-start">';

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}`).next('.invalid-feedback').text(value[0]);
                            errorList += '<li>' + value[0] + '</li>';
                        });

                        errorList += '</ul>';

                        Swal.fire({
                            title: 'Validasi Error',
                            html: '<p>Mohon periksa kembali data yang Anda masukkan:</p>' + errorList,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    } else {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        let errorDetails = '';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON.error) {
                                errorDetails = '<div class="mt-3 alert alert-danger text-start">' +
                                             '<strong>Detail Error:</strong><br>' +
                                             xhr.responseJSON.error +
                                             '</div>';
                            }
                        }

                        Swal.fire({
                            title: 'Error!',
                            html: errorMessage + errorDetails,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                }
            });
        });
    };

    // Handle Export
    var handleExport = function() {
        $('#btn-export').on('click', function() {
            window.location.href = '{{ route("karyawan.export") }}';
        });
    };

    // Handle Import
    var handleImport = function() {
        $('#form-import').on('submit', function(e) {
            e.preventDefault();

            const submitButton = document.querySelector('#btn-import');
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("karyawan.import") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (response.success || response.partial) {
                        $('#modal-import').modal('hide');
                        $('#form-import')[0].reset();

                        let icon = response.success ? "success" : "warning";
                        let message = response.message;

                        // Show errors if any
                        if (response.errors && response.errors.length > 0) {
                            let errorList = '<ul class="text-start">';
                            response.errors.slice(0, 5).forEach(function(error) {
                                errorList += '<li>' + error + '</li>';
                            });
                            if (response.errors.length > 5) {
                                errorList += '<li>... dan ' + (response.errors.length - 5) + ' error lainnya</li>';
                            }
                            errorList += '</ul>';

                            message += errorList;
                        }

                        Swal.fire({
                            title: response.success ? 'Berhasil!' : 'Import Selesai dengan Warning',
                            html: message,
                            icon: icon,
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            dt.draw();
                        });
                    }
                },
                error: function(xhr) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    let errorMessage = "Gagal mengimport data";
                    let errorDetails = '';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors && Array.isArray(xhr.responseJSON.errors)) {
                            errorDetails = '<ul class="text-start mt-3">';
                            xhr.responseJSON.errors.slice(0, 10).forEach(function(error) {
                                errorDetails += '<li class="mb-2">' + error + '</li>';
                            });
                            if (xhr.responseJSON.errors.length > 10) {
                                errorDetails += '<li>... dan ' + (xhr.responseJSON.errors.length - 10) + ' error lainnya</li>';
                            }
                            errorDetails += '</ul>';
                        } else if (xhr.responseJSON.error) {
                            errorDetails = '<div class="mt-3 text-start"><small>' + xhr.responseJSON.error + '</small></div>';
                        }
                    }

                    Swal.fire({
                        title: 'Error Import',
                        html: errorMessage + errorDetails,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        width: '600px',
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        });
    };

    // Handle Delete All
    var handleDeleteAll = function() {
        $('#btn-delete-all').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi Hapus Semua Data',
                html: '<p class="mb-3">Apakah Anda yakin ingin menghapus <strong>SEMUA DATA KARYAWAN</strong>?</p>' +
                      '<p class="text-danger fw-bold">⚠️ Tindakan ini tidak dapat dibatalkan!</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Konfirmasi kedua
                    Swal.fire({
                        title: 'Konfirmasi Terakhir',
                        html: '<p>Ketik <strong>HAPUS SEMUA</strong> untuk melanjutkan:</p>' +
                              '<input type="text" id="confirm-text" class="form-control mt-3" placeholder="Ketik: HAPUS SEMUA">',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        },
                        preConfirm: () => {
                            const confirmText = document.getElementById('confirm-text').value;
                            if (confirmText !== 'HAPUS SEMUA') {
                                Swal.showValidationMessage('Teks konfirmasi tidak sesuai!');
                                return false;
                            }
                            return true;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus data...',
                                html: 'Mohon tunggu, proses sedang berlangsung',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{ route("karyawan.delete-all") }}',
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        buttonsStyling: false,
                                        confirmButtonText: 'Ok',
                                        customClass: {
                                            confirmButton: 'btn btn-primary'
                                        }
                                    }).then(() => {
                                        dt.draw();
                                    });
                                },
                                error: function(xhr) {
                                    let errorMessage = 'Gagal menghapus semua data';
                                    let errorDetails = '';

                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        }
                                        if (xhr.responseJSON.error) {
                                            errorDetails = '<div class="mt-3 alert alert-danger text-start">' +
                                                         '<strong>Detail Error:</strong><br>' +
                                                         xhr.responseJSON.error +
                                                         '</div>';
                                        }
                                    }

                                    Swal.fire({
                                        title: 'Error!',
                                        html: errorMessage + errorDetails,
                                        icon: 'error',
                                        buttonsStyling: false,
                                        confirmButtonText: 'Ok',
                                        customClass: {
                                            confirmButton: 'btn btn-primary'
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
    };

    return {
        init: function() {
            initDatatable();
            handleSearchDatatable();
            handleFilter();
            handleAdd();
            handleSubmitForm();
            handleEditRows();
            handleDetailRows();
            handleDeleteRows();
            handleExport();
            handleImport();
            handleDeleteAll();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTKaryawanList.init();
});
</script>
@endpush
