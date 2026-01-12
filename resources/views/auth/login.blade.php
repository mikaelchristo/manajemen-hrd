<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - {{ $appData->nama_app ?? 'SIM RS' }}</title>

    @if ($appData && $appData->favicon)
        <link rel="shortcut icon" href="{{ asset('storage/' . $appData->favicon) }}" />
    @else
        <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    @endif

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <style>
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-card {
            box-shadow: 0 10px 40px 0 rgba(0, 0, 0, 0.15);
            border-radius: 15px;
        }

        .logo-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>

<body id="kt_body" class="app-blank">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root login-bg" id="kt_app_root">
        <!--begin::Authentication-->
        <div class="d-flex flex-column flex-center flex-column-fluid p-10">
            <!--begin::Wrapper-->
            <div class="w-lg-600px w-md-500px w-100 p-10">
                <!--begin::Card-->
                <div class="card login-card">
                    <!--begin::Logo Section-->
                    <div class="logo-section text-center">
                        @if ($appData && $appData->logo)
                            <img alt="Logo" src="{{ asset('storage/' . $appData->logo) }}" class="h-100px mb-4" />
                        @else
                            <div class="d-inline-block mb-4">
                                <div class="symbol symbol-75px">
                                    <div class="symbol-label fs-2 fw-semibold text-black bg-primary">HRD</div>
                                </div>
                            </div>
                        @endif
                        <h2 class="text-black fw-bold mb-2">{{ $appData->nama_app ?? 'SIM RS' }}</h2>
                        <p class="text-black opacity-75 mb-0 fs-6">
                            {{ $appData->nama_instansi ?? 'Sistem Informasi Manajemen' }}</p>
                    </div>
                    <!--end::Logo Section-->

                    <!--begin::Card body-->
                    <div class="card-body p-10 p-lg-5">
                        <!--begin::Heading-->
                        <div class="text-center mb-10 ">
                            <h1 class="text-dark fw-bolder mb-3 ">Selamat Datang</h1>
                            <div class="text-gray-700 fw-semibold fs-6">
                                Silakan masuk ke akun Anda
                            </div>
                        </div>
                        <!--end::Heading-->

                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="ki-duotone ki-check-circle fs-2hx text-success me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div class="d-flex flex-column">
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!--begin::Form-->
                        <form class="form w-100" method="POST" action="{{ route('login.post') }}"
                            id="kt_sign_in_form">
                            @csrf

                            <!--begin::Input group-->
                            <div class="fv-row mb-8">
                                <label class="form-label fs-6 fw-semibold text-dark">Email</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-sms fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-500">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="email" name="email"
                                        class="form-control form-control-lg form-control-solid ps-13 @error('email') is-invalid @enderror"
                                        placeholder="nama@email.com" value="{{ old('email') }}" required autofocus />
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <label class="form-label fw-semibold text-dark fs-6 mb-0">Password</label>
                                <div class="position-relative">
                                    <i
                                        class="ki-duotone ki-lock fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-500">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="password" name="password"
                                        class="form-control form-control-lg form-control-solid ps-13 @error('password') is-invalid @enderror"
                                        placeholder="********" required />
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                                    <label class="form-check-label text-gray-700" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Submit button-->
                            <div class="d-grid mb-5">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary">
                                    <span class="indicator-label">
                                        <i class="ki-duotone ki-entrance-right fs-2 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Masuk
                                    </span>
                                    <span class="indicator-progress">Mohon tunggu...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <!--end::Submit button-->

                            <!--begin::Footer-->
                            <div class="text-center text-gray-500 fs-7">
                                <i class="ki-duotone ki-shield-tick fs-3 text-success me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Sistem aman dan terenkripsi
                            </div>
                            <!--end::Footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

                <!--begin::Footer-->
                <div class="text-center mt-10">
                    <div class="text-white opacity-75 fw-semibold fs-7">
                        <span>© {{ date('Y') }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $appData->nama_instansi ?? 'Sistem Informasi' }}</span>
                    </div>
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Authentication-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        "use strict";
        var KTSigninGeneral = function() {
            var form, submitButton;

            var handleForm = function(e) {
                var validation = FormValidation.formValidation(form, {
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'Email wajib diisi'
                                },
                                emailAddress: {
                                    message: 'Format email tidak valid'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: 'Password wajib diisi'
                                },
                                stringLength: {
                                    min: 6,
                                    message: 'Password minimal 6 karakter'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                });

                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    validation.validate().then(function(status) {
                        if (status == 'Valid') {
                            submitButton.setAttribute('data-kt-indicator', 'on');
                            submitButton.disabled = true;
                            form.submit();
                        }
                    });
                });
            };

            return {
                init: function() {
                    form = document.querySelector('#kt_sign_in_form');
                    submitButton = document.querySelector('#kt_sign_in_submit');

                    if (form && submitButton) {
                        handleForm();
                    }
                }
            };
        }();

        KTUtil.onDOMContentLoaded(function() {
            KTSigninGeneral.init();
        });
    </script>
    <!--end::Javascript-->
</body>

</html>
