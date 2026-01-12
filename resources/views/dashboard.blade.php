@extends('layouts.app')

@section('title', 'Dashboard - HRD System')

@section('content')
<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::Page title-->
 
    <!--end::Page title-->

    <!--begin::Row - Total Karyawan (Large Card)-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-12">
            <!--begin::Card-->
            <div class="card card-flush h-xl-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <!--begin::Card body-->
                <div class="card-body py-9">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="text-white">
                                <h1 class="fw-bold text-white mb-3 display-4">{{ $totalKaryawan }}</h1>
                                <div class="fs-2 fw-bold mb-3">Total Karyawan</div>
                                <div class="fs-6 opacity-75">Jumlah keseluruhan data karyawan yang terdaftar dalam sistem</div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <i class="ki-duotone ki-people text-white opacity-50" style="font-size: 12rem;">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--end::Row-->

    <!--begin::Row - Statistics Cards-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col - Jenis Kelamin-->
        <div class="col-md-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Data Berdasarkan Jenis Kelamin</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Distribusi karyawan</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="row g-5">
                        <!--begin::Laki-laki-->
                        <div class="col-6">
                            <div class="border border-dashed border-gray-300 rounded p-6 text-center hover-elevate-up" style="cursor: pointer;">
                                <i class="ki-duotone ki-user-square fs-3x text-primary mb-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="fs-2x fw-bold text-gray-900 mb-2">{{ $totalLakiLaki }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">Laki-laki</div>
                            </div>
                        </div>
                        <!--end::Laki-laki-->
                        <!--begin::Perempuan-->
                        <div class="col-6">
                            <div class="border border-dashed border-gray-300 rounded p-6 text-center hover-elevate-up" style="cursor: pointer;">
                                <i class="ki-duotone ki-badge fs-3x text-danger mb-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                <div class="fs-2x fw-bold text-gray-900 mb-2">{{ $totalPerempuan }}</div>
                                <div class="fs-6 fw-semibold text-gray-500">Perempuan</div>
                            </div>
                        </div>
                        <!--end::Perempuan-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col - Status Pegawai-->
        <div class="col-md-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Data Berdasarkan Status</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Status kepegawaian</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <!--begin::Tetap-->
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-shield-tick fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pegawai Tetap</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Karyawan dengan status tetap</span>
                        </div>
                        <span class="badge badge-light-success fs-2 fw-bold">{{ $pegawaiTetap }}</span>
                    </div>
                    <!--end::Tetap-->
                    <!--begin::PKWT-->
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-timer fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pegawai PKWT</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Perjanjian Kerja Waktu Tertentu</span>
                        </div>
                        <span class="badge badge-light-warning fs-2 fw-bold">{{ $pegawaiPKWT }}</span>
                    </div>
                    <!--end::PKWT-->
                    <!--begin::Kontrak-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-document fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pegawai Kontrak</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Karyawan dengan status kontrak</span>
                        </div>
                        <span class="badge badge-light-info fs-2 fw-bold">{{ $pegawaiKontrak }}</span>
                    </div>
                    <!--end::Kontrak-->
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row - Quick Actions & Info-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col - Quick Actions-->
        <div class="col-md-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Aksi Cepat</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Menu yang sering digunakan</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-user-tick fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('karyawan.index') }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">Data Karyawan</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Kelola informasi lengkap karyawan</span>
                        </div>
                        <i class="ki-duotone ki-arrow-right fs-1 text-gray-400">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-add-files fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('karyawan.index') }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">Tambah Karyawan</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Input data karyawan baru</span>
                        </div>
                        <i class="ki-duotone ki-arrow-right fs-1 text-gray-400">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-file-up fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('karyawan.index') }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">Import Data</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Upload data dari file Excel</span>
                        </div>
                        <i class="ki-duotone ki-arrow-right fs-1 text-gray-400">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-printer fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('karyawan.index') }}" class="text-gray-800 text-hover-primary fs-5 fw-bold">Export Laporan</a>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Download data ke Excel</span>
                        </div>
                        <i class="ki-duotone ki-arrow-right fs-1 text-gray-400">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Item-->
                </div>
            </div>
        </div>
        <!--end::Col-->

        <!--begin::Col - System Info-->
        <div class="col-md-6">
            <div class="card card-flush h-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Informasi Sistem</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Status dan detail aplikasi</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <!--begin::Item-->
                    <div class="d-flex flex-stack mb-5">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-code fs-2 text-primary me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            <span class="text-gray-700 fw-bold fs-6">Versi Aplikasi</span>
                        </div>
                        <span class="badge badge-light-primary fs-7 fw-bold">1.0.0</span>
                    </div>
                    <!--end::Item-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Item-->
                    <div class="d-flex flex-stack mb-5">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-technology-2 fs-2 text-success me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-700 fw-bold fs-6">Framework</span>
                        </div>
                        <span class="badge badge-light-success fs-7 fw-bold">Laravel 11</span>
                    </div>
                    <!--end::Item-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Item-->
                    <div class="d-flex flex-stack mb-5">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-data fs-2 text-info me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span class="text-gray-700 fw-bold fs-6">Database</span>
                        </div>
                        <span class="badge badge-light-info fs-7 fw-bold">MySQL</span>
                    </div>
                    <!--end::Item-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Item-->
                    <div class="d-flex flex-stack mb-5">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-cloud fs-2 text-warning me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-700 fw-bold fs-6">Server</span>
                        </div>
                        <span class="badge badge-light-warning fs-7 fw-bold">Laragon</span>
                    </div>
                    <!--end::Item-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Item-->
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-check-circle fs-2 text-success me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span class="text-gray-700 fw-bold fs-6">Status</span>
                        </div>
                        <span class="badge badge-light-success fs-7 fw-bold">
                            <i class="ki-duotone ki-check fs-3 text-success">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Aktif
                        </span>
                    </div>
                    <!--end::Item-->
                </div>
            </div>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Container-->
@endsection
