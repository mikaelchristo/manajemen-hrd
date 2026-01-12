@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="d-flex flex-column flex-column-fluid">


    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Informasi Aplikasi</h3>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Form-->
                <form action="{{ route('settings.data-app.update') }}" method="POST" enctype="multipart/form-data" id="kt_data_app_form">
                    @csrf
                    @method('PUT')

                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-check-circle fs-2hx text-success me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">Berhasil</h4>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                            <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-danger">Terjadi Kesalahan</h4>
                                @foreach($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!--begin::Input group - Logo-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Logo Aplikasi</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    @if($dataApp->logo)
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('storage/' . $dataApp->logo) }}');"></div>
                                    @else
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('assets/media/svg/files/blank-image.svg') }}');"></div>
                                    @endif

                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah logo">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="logo_remove" />
                                    </label>

                                    @if($dataApp->logo)
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus logo" onclick="removeLogo()">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg. Max size: 2MB</div>
                            </div>
                        </div>

                        <!--begin::Input group - Favicon-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Favicon</label>
                            <div class="col-lg-8">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    @if($dataApp->favicon)
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('storage/' . $dataApp->favicon) }}');"></div>
                                    @else
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('assets/media/svg/files/blank-image.svg') }}');"></div>
                                    @endif

                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah favicon">
                                        <i class="ki-duotone ki-pencil fs-7">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <input type="file" name="favicon" accept=".png, .jpg, .jpeg, .ico" />
                                        <input type="hidden" name="favicon_remove" />
                                    </label>

                                    @if($dataApp->favicon)
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus favicon" onclick="removeFavicon()">
                                        <i class="ki-duotone ki-cross fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg, ico. Max size: 1MB</div>
                            </div>
                        </div>

                        <!--begin::Input group - Nama App-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Aplikasi</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="nama_app" class="form-control form-control-lg form-control-solid @error('nama_app') is-invalid @enderror" placeholder="Nama aplikasi" value="{{ old('nama_app', $dataApp->nama_app) }}" required />
                                @error('nama_app')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!--begin::Input group - Nama Instansi-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Nama Instansi</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="nama_instansi" class="form-control form-control-lg form-control-solid" placeholder="Nama instansi" value="{{ old('nama_instansi', $dataApp->nama_instansi) }}" />
                            </div>
                        </div>

                        <!--begin::Input group - Alamat-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Alamat</label>
                            <div class="col-lg-8 fv-row">
                                <textarea name="alamat" class="form-control form-control-lg form-control-solid" rows="3" placeholder="Alamat lengkap">{{ old('alamat', $dataApp->alamat) }}</textarea>
                            </div>
                        </div>

                        <!--begin::Input group - No Telp-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">No. Telepon</label>
                            <div class="col-lg-8 fv-row">
                                <input type="text" name="no_telp" class="form-control form-control-lg form-control-solid" placeholder="No. telepon" value="{{ old('no_telp', $dataApp->no_telp) }}" />
                            </div>
                        </div>

                        <!--begin::Input group - Copyright-->
                        <div class="row mb-6">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Teks Copyright</label>
                            <div class="col-lg-8 fv-row">
                                <textarea name="copyright_text" class="form-control form-control-lg form-control-solid" rows="2" placeholder="Contoh: © 2026 SIM RS. All Rights Reserved.">{{ old('copyright_text', $dataApp->copyright_text) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="kt_data_app_submit">
                            <span class="indicator-label">Simpan Perubahan</span>
                            <span class="indicator-progress">Mohon tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Card footer-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->
</div>
@endsection

@push('scripts')
<script>
// Remove logo
function removeLogo() {
    Swal.fire({
        text: "Apakah Anda yakin ingin menghapus logo?",
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
                url: "{{ route('settings.data-app.remove-logo') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

// Remove favicon
function removeFavicon() {
    Swal.fire({
        text: "Apakah Anda yakin ingin menghapus favicon?",
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
                url: "{{ route('settings.data-app.remove-favicon') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        text: response.message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }
    });
}

// Handle form submission
$('#kt_data_app_form').on('submit', function() {
    const submitButton = $('#kt_data_app_submit');
    submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);
});
</script>
@endpush
