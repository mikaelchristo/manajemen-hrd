@extends('layouts.app')

@section('title', 'Rekapitulasi Absensi - HRD System')

@section('content')
<!--begin::Container-->
<div class="container-xxl" id="kt_content_container">
    <!--begin::Card Filter-->
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Filter Rekapitulasi</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Pilih karyawan dan rentang tanggal</span>
                </h3>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Karyawan</label>
                    <select class="form-select form-select-solid" id="filter_karyawan">
                        <option value="">Pilih Karyawan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control form-control-solid" id="filter_awal" value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control form-control-solid" id="filter_akhir" value="{{ date('Y-m-t') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-primary d-block w-100" id="btn-load-rekap">
                        <i class="ki-duotone ki-magnifier fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Tampilkan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card Filter-->

    <!--begin::Stats-->
    <div class="row g-5 g-xl-8 mb-5" id="stats-container" style="display: none;">
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8 bg-success">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Hadir</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-hadir">0</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8 bg-danger">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Sakit</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-sakit">0</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8 bg-warning">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Izin</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-izin">0</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8 bg-info">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Cuti</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-cuti">0</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8 bg-dark">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Alpha</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-alpha">0</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card card-xl-stretch mb-xl-8" style="background: #f39c12;">
                <div class="card-body">
                    <span class="text-white fs-7 fw-semibold">Total Telat</span>
                    <div class="text-white fs-2x fw-bold" id="rekap-telat">0</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats-->

    <!--begin::Card History-->
    <div class="card" id="history-container" style="display: none;">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Riwayat Absensi Bulanan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7" id="history-subtitle"></span>
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex gap-2">
                    <select class="form-select form-select-solid w-150px" id="history_bulan">
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
                    <select class="form-select form-select-solid w-100px" id="history_tahun">
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <button type="button" class="btn btn-sm btn-primary" id="btn-load-history">
                        <i class="ki-duotone ki-arrows-circle fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Muat
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_history_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-100px">Tanggal</th>
                            <th class="min-w-100px">Jam Masuk</th>
                            <th class="min-w-100px">Jam Pulang</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-150px">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600" id="history-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Card History-->

    <!--begin::Placeholder-->
    <div class="card" id="placeholder-card">
        <div class="card-body text-center py-20">
            <i class="ki-duotone ki-calendar-search fs-5tx text-gray-300">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
            <p class="text-gray-500 fs-4 fw-semibold mt-5">Pilih karyawan dan rentang tanggal untuk melihat rekapitulasi absensi</p>
        </div>
    </div>
    <!--end::Placeholder-->
</div>
<!--end::Container-->
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

        var selectedUserId = null;
        var currentHistory = [];

        // Load daftar karyawan saat halaman dimuat
        loadKaryawanOptions();

        // Event handler untuk tombol Tampilkan
        $('#btn-load-rekap').on('click', function() {
            loadRekapitulasi();
        });

        // Event handler untuk tombol Muat History
        $('#btn-load-history').on('click', function() {
            loadHistoryByMonth();
        });

        // Event handler saat karyawan berubah - reset tampilan
        $('#filter_karyawan').on('change', function() {
            // Reset stats dan history jika user berubah
            if (selectedUserId !== $(this).val()) {
                $('#stats-container').hide();
                $('#history-container').hide();
                $('#placeholder-card').show();
                $('#rekap-hadir, #rekap-sakit, #rekap-izin, #rekap-cuti, #rekap-alpha, #rekap-telat').text('0');
                $('#history-body').html('');
                currentHistory = [];
            }
        });

        function loadRekapitulasi() {
            var userid = $('#filter_karyawan').val();
            var awal = $('#filter_awal').val();
            var akhir = $('#filter_akhir').val();

            if (!userid) {
                Swal.fire('Peringatan', 'Pilih karyawan terlebih dahulu', 'warning');
                return;
            }

            selectedUserId = userid;
            var $btn = $('#btn-load-rekap');
            $btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            // Show loading
            Swal.fire({
                title: 'Memuat Data',
                html: 'Sedang mengambil data rekapitulasi...<br><small>Proses ini mungkin memerlukan waktu beberapa saat</small>',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('absensi.rekapitulasi.data') }}",
                type: 'GET',
                data: {
                    userid: userid,
                    awal: awal,
                    akhir: akhir
                },
                timeout: 120000, // 2 menit timeout
                success: function(response) {
                    Swal.close();
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);

                    console.log('Rekapitulasi response:', response);

                    if (response.success && response.data) {
                        var data = response.data;

                        // Update stats
                        $('#rekap-hadir').text(data.hadir || 0);
                        $('#rekap-sakit').text(data.sakit || 0);
                        $('#rekap-izin').text(data.izin || 0);
                        $('#rekap-cuti').text(data.cuti || 0);
                        $('#rekap-alpha').text(data.alpha || 0);
                        $('#rekap-telat').text(data.telat || 0);

                        // Simpan history data
                        currentHistory = response.history || [];

                        // Show stats and history container
                        $('#stats-container').show();
                        $('#history-container').show();
                        $('#placeholder-card').hide();

                        // Load history untuk bulan/tahun yang dipilih
                        displayHistoryForMonth();

                        // Update subtitle
                        var namaKaryawan = $('#filter_karyawan option:selected').text();
                        $('#history-subtitle').text(namaKaryawan);
                    } else {
                        Swal.fire('Info', response.message || 'Data rekapitulasi tidak ditemukan', 'info');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    console.error('Error:', xhr.responseText);
                    Swal.fire('Error', 'Gagal memuat data rekapitulasi: ' + error, 'error');
                }
            });
        }

        function loadHistoryByMonth() {
            if (!selectedUserId) {
                Swal.fire('Peringatan', 'Pilih karyawan terlebih dahulu', 'warning');
                return;
            }

            var bulan = $('#history_bulan').val();
            var tahun = $('#history_tahun').val();

            // Hitung tanggal awal dan akhir bulan
            var awal = tahun + '-' + bulan + '-01';
            var lastDay = new Date(tahun, parseInt(bulan), 0).getDate();
            var akhir = tahun + '-' + bulan + '-' + (lastDay < 10 ? '0' : '') + lastDay;

            var $btn = $('#btn-load-history');
            $btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            // Show loading
            Swal.fire({
                title: 'Memuat Data',
                html: 'Sedang mengambil riwayat absensi bulanan...',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('absensi.rekapitulasi.data') }}",
                type: 'GET',
                data: {
                    userid: selectedUserId,
                    awal: awal,
                    akhir: akhir
                },
                timeout: 120000,
                success: function(response) {
                    Swal.close();
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);

                    if (response.success && response.history) {
                        currentHistory = response.history;
                        displayHistoryForMonth();

                        var namaKaryawan = $('#filter_karyawan option:selected').text();
                        $('#history-subtitle').text(namaKaryawan + ' - ' + getMonthName(bulan) + ' ' + tahun);
                    } else {
                        $('#history-body').html('<tr><td colspan="6" class="text-center text-muted">Tidak ada data absensi</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    $btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    $('#history-body').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>');
                }
            });
        }

        function displayHistoryForMonth() {
            var bulan = $('#history_bulan').val();
            var tahun = $('#history_tahun').val();
            var filterPrefix = tahun + '-' + bulan;

            var html = '';
            var filteredData = currentHistory.filter(function(item) {
                return item.tanggal && item.tanggal.startsWith(filterPrefix);
            });

            // Sort by tanggal
            filteredData.sort(function(a, b) {
                return new Date(a.tanggal) - new Date(b.tanggal);
            });

            if (filteredData.length > 0) {
                filteredData.forEach(function(item, index) {
                    var statusBadge = getStatusBadge(item.status);
                    var tanggal = formatDate(item.tanggal);
                    var jamMasuk = item.jammasuk || '-';
                    var jamPulang = item.jampulang || '-';

                    html += '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + tanggal + '</td>' +
                        '<td><span class="badge badge-light-success">' + jamMasuk + '</span></td>' +
                        '<td><span class="badge badge-light-info">' + jamPulang + '</span></td>' +
                        '<td>' + statusBadge + '</td>' +
                        '<td>' + (item.keterangan || '-') + '</td>' +
                    '</tr>';
                });
            } else {
                html = '<tr><td colspan="6" class="text-center text-muted">Tidak ada data absensi untuk periode ini</td></tr>';
            }

            $('#history-body').html(html);

            var namaKaryawan = $('#filter_karyawan option:selected').text();
            $('#history-subtitle').text(namaKaryawan + ' - ' + getMonthName(bulan) + ' ' + tahun);
        }

        function getStatusBadge(status) {
            if (!status) return '<span class="badge badge-light-secondary">-</span>';

            var badgeClass = 'light-secondary';
            var statusUpper = status.toUpperCase();

            if (statusUpper === 'HADIR') badgeClass = 'success';
            else if (statusUpper === 'SAKIT') badgeClass = 'danger';
            else if (statusUpper.indexOf('IZIN') >= 0) badgeClass = 'warning';
            else if (statusUpper === 'CUTI') badgeClass = 'info';
            else if (statusUpper === 'ALPHA' || statusUpper === 'ABSEN') badgeClass = 'dark';
            else if (statusUpper === 'LIBUR') badgeClass = 'secondary';

            return '<span class="badge badge-' + badgeClass + '">' + status + '</span>';
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            var date = new Date(dateStr);
            var options = { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function getMonthName(month) {
            var months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return months[parseInt(month)] || '';
        }

        function loadKaryawanOptions() {
            console.log('Loading karyawan options...');
            $('#filter_karyawan').html('<option value="">Memuat data karyawan...</option>');

            $.ajax({
                url: "{{ route('absensi.user-list') }}",
                type: 'GET',
                timeout: 30000,
                success: function(response) {
                    console.log('User list response:', response);
                    var options = '<option value="">-- Pilih Karyawan --</option>';
                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(item) {
                            // Use username (NIK KTP) as value for API rekapitulasi
                            var displayName = (item.nik || '-') + ' - ' + (item.nama || item.name || '-');
                            options += '<option value="' + (item.username || '') + '">' + displayName + '</option>';
                        });
                        console.log('Loaded ' + response.data.length + ' karyawan');
                    } else {
                        console.log('No data or empty response, fallback to local');
                        loadKaryawanFromLocal();
                        return;
                    }
                    $('#filter_karyawan').html(options);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading karyawan from API:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    console.log('Fallback to local karyawan data...');
                    loadKaryawanFromLocal();
                }
            });
        }

        // Fallback: Load karyawan dari database lokal
        function loadKaryawanFromLocal() {
            $.ajax({
                url: "{{ route('karyawan.getData') }}",
                type: 'GET',
                data: { length: -1 },
                success: function(response) {
                    var options = '<option value="">-- Pilih Karyawan --</option>';
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(item) {
                            // Use nikKtp (NIK KTP) as value for API
                            if (item.nikKtp && item.nikKtp.trim() !== '') {
                                var displayName = (item.nikKry || '-') + ' - ' + (item.namaKaryawan || '-');
                                options += '<option value="' + item.nikKtp + '">' + displayName + '</option>';
                            }
                        });
                        console.log('Loaded ' + response.data.length + ' karyawan from local');
                    }
                    $('#filter_karyawan').html(options);
                },
                error: function() {
                    $('#filter_karyawan').html('<option value="">Gagal memuat data karyawan</option>');
                }
            });
        }
    });
</script>
@endpush
