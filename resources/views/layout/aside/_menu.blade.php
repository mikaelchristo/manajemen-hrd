<!--begin::Aside Menu-->
<div
    class="hover-scroll-overlay-y mx-3 my-5 my-lg-5"
    id="kt_aside_menu_wrapper"
    data-kt-scroll="true"
    data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
    data-kt-scroll-wrappers="#kt_aside_menu"
    data-kt-scroll-offset="5px"
>
    <!--begin::Menu-->
    <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">

        <!--begin::Menu Item Dashboard-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-element-11 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Item Karyawan-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('karyawan*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-people fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                </span>
                <span class="menu-title">Data Karyawan</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Section Absensi-->
        <div class="menu-item">
            <div class="menu-content pt-8 pb-2">
                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Monitoring Absensi</span>
            </div>
        </div>
        <!--end::Menu Section-->

        <!--begin::Menu Item Absensi Harian-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('absensi') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-calendar-tick fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                    </i>
                </span>
                <span class="menu-title">Absensi Harian</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Item Lembur-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('absensi/lembur*') ? 'active' : '' }}" href="{{ route('absensi.lembur') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-time fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Data Lembur</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Item Rekapitulasi-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('absensi/rekapitulasi*') ? 'active' : '' }}" href="{{ route('absensi.rekapitulasi') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-chart-simple-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
                <span class="menu-title">Rekapitulasi</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Section-->
        <div class="menu-item">
            <div class="menu-content pt-8 pb-2">
                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Manajemen Sistem</span>
            </div>
        </div>
        <!--end::Menu Section-->

        <!--begin::Menu Item Users-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-profile-user fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
                <span class="menu-title">Manajemen User</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Item Settings-->
        <div class="menu-item">
            <a class="menu-link {{ Request::is('settings*') ? 'active' : '' }}" href="{{ route('settings.data-app.index') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-setting-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Pengaturan Aplikasi</span>
            </a>
        </div>
        <!--end::Menu Item-->

        <!--begin::Menu Section-->
        <div class="menu-item">
            <div class="menu-content pt-8 pb-2">
                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Akun</span>
            </div>
        </div>
        <!--end::Menu Section-->

        <!--begin::Menu Item Logout-->
        <div class="menu-item">
            <a class="menu-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-icon">
                    <i class="ki-duotone ki-exit-left fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </span>
                <span class="menu-title">Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <!--end::Menu Item-->

    </div>
    <!--end::Menu-->
</div>
<!--end::Aside Menu-->
