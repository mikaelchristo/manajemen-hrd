<!--begin::Header-->
<div id="kt_header" style="" class="header align-items-stretch">
    <!--begin::Brand-->
    <div class="header-brand">
       <!--begin::Symbol-->
      <!--begin::Symbol-->
    @if($appData && $appData->logo)
        <div class="symbol symbol-50px me-4">
            <img src="{{ asset('storage/' . $appData->logo) }}" alt="{{ $appData->nama_app }}" class="w-100" />
        </div>
    @else
        <div class="symbol symbol-50px me-4">
            <span class="symbol-label fs-2 fw-bold text-primary">HRD</span>
        </div>
    @endif
    <!--end::Symbol-->

    <!--begin::App Name-->
    <div class="d-flex flex-column">
        <span class="text-white fw-bold fs-6 text-uppercase" style="line-height: 1.2; letter-spacing: 0.5px;">MANAJEMEN HRD</span>
        <span class="text-gray-400 fw-semibold fs-7 text-uppercase" style="line-height: 1.2; letter-spacing: 0.3px;">{{ $appData->nama_instansi }}</span>
    </div>
    <!--end::App Name-->
    <!--end::Symbol-->
        <!--begin::Aside minimize-->
        <div id="kt_aside_toggle"
            class="btn btn-icon w-auto px-0 btn-active-color-primary aside-minimize"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <i class="ki-duotone ki-entrance-right fs-1 me-n1 minimize-default"><span class="path1"></span><span class="path2"></span></i>
            <i class="ki-duotone ki-entrance-left fs-1 minimize-active"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Aside minimize-->
        <!--begin::Aside toggle-->
        <div class="d-flex align-items-center d-lg-none me-n2" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
        <!--end::Aside toggle-->
    </div>
    <!--end::Brand-->
    @include('layout/header/__toolbar')
</div>
<!--end::Header-->
