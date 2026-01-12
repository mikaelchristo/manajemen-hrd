@extends('layouts.app')

@section('title', 'SPK - Pemilihan Topik Skripsi')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            @include('layout._page-title')
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <!--begin::Col-->
                <div class="col-md-4">
                    <div class="card card-flush h-md-100">
                        <div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
                            <div class="mb-10">
                                <div class="fs-2hx fw-bold text-gray-800 text-center mb-13">
                                    <span class="me-2">{{ $totalKriteria }}</span>
                                    <span class="position-relative d-inline-block">
                                        <span class="text-gray-700 opacity-75-hover">Kriteria</span>
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="badge badge-light-primary fs-7">C1 - C{{ $totalKriteria }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Col-->
                <div class="col-md-4">
                    <div class="card card-flush h-md-100">
                        <div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
                            <div class="mb-10">
                                <div class="fs-2hx fw-bold text-gray-800 text-center mb-13">
                                    <span class="me-2">{{ $totalTopik }}</span>
                                    <span class="position-relative d-inline-block">
                                        <span class="text-gray-700 opacity-75-hover">Total Topik</span>
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="badge badge-light-info fs-7">Alternatif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Col-->
                <div class="col-md-4">
                    <div class="card card-flush h-md-100">
                        <div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
                            <div class="mb-10">
                                <div class="fs-2hx fw-bold text-gray-800 text-center mb-13">
                                    <span class="me-2">{{ $topikTersedia }}</span>
                                    <span class="position-relative d-inline-block">
                                        <span class="text-gray-700 opacity-75-hover">Tersedia</span>
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="badge badge-light-success fs-7">Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Card Menu-->
            <div class="row g-5 g-xl-10">
                <div class="col-xl-4">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-7">
                                    <div class="d-flex flex-stack mb-6">
                                        <div class="symbol symbol-60px symbol-2by3 flex-shrink-0 me-4">
                                            <i class="ki-duotone ki-chart-simple fs-3x text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                            </i>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2">
                                                <a href="{{ route('spk.alternatif.index') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Kelola Topik</a>
                                                <span class="text-muted fw-semibold d-block fs-7">Tambah & edit topik skripsi</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-7">
                                    <div class="d-flex flex-stack mb-6">
                                        <div class="symbol symbol-60px symbol-2by3 flex-shrink-0 me-4">
                                            <i class="ki-duotone ki-calculator fs-3x text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2">
                                                <a href="{{ route('spk.perhitungan') }}" class="text-gray-800 text-hover-primary fs-6 fw-bold">Perhitungan SAW</a>
                                                <span class="text-muted fw-semibold d-block fs-7">Lihat hasil & ranking topik</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card card-flush h-lg-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-7">
                                    <div class="d-flex flex-stack mb-6">
                                        <div class="symbol symbol-60px symbol-2by3 flex-shrink-0 me-4">
                                            <i class="ki-duotone ki-chart-line-up fs-3x text-warning">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2">
                                                <span class="text-gray-800 fs-6 fw-bold">Kriteria & Bobot</span>
                                                <span class="text-muted fw-semibold d-block fs-7">6 kriteria aktif</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card Menu-->

        </div>
    </div>
</div>
@endsection
