<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * Base URL API Absensi
     */
    protected $baseUrl = 'https://absen.ibnusinabkt.id/restapiv2_2/';

    /**
     * API Key untuk autentikasi
     */
    protected $apiKey = 'absensiyarsi123';

    /**
     * Menampilkan halaman monitoring absensi harian
     */
    public function index()
    {
        $pageTitle = 'Monitoring Absensi';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Monitoring Absensi']
        ];

        return view('absensi.index', compact('pageTitle', 'breadcrumbs'));
    }

    /**
     * LAYER 1: Menghitung statistik absensi secara manual
     * Method ini mengambil semua data dan menghitung total per kategori
     */
    private function hitungStatistikManual($tanggal, $unit)
    {
        // Ambil SEMUA data dari API untuk perhitungan
        $response = Http::withHeaders([
            'X-API-KEY' => $this->apiKey,
        ])->withoutVerifying()->asForm()->post($this->baseUrl . 'getDataHarian', [
            'draw' => 1,
            'start' => 0,
            'length' => -1, // Ambil semua data tanpa pagination
            'tanggal' => $tanggal,
            'unit' => $unit,
        ]);

        // Inisialisasi counter statistik
        $statistics = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'cuti' => 0,
            'alpha' => 0,
            'libur' => 0,
            'total' => 0
        ];

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['data']) && is_array($data['data'])) {
                $statistics['total'] = count($data['data']);

                // PROSES MANUAL: Hitung satu per satu
                foreach ($data['data'] as $item) {
                    $statusCode = strtoupper(trim($item['status'] ?? ''));
                    $kategori = $item['kategori'] ?? null;

                    // Logika perhitungan berdasarkan status dan kategori
                    if ($kategori == '1' && empty($statusCode)) {
                        // Kategori 1 tanpa status = Hadir
                        $statistics['hadir']++;
                    } elseif (in_array($statusCode, ['P', 'P6'])) {
                        // Status P atau P6 = Hadir
                        $statistics['hadir']++;
                    } elseif ($statusCode == 'S') {
                        // Status S = Sakit
                        $statistics['sakit']++;
                    } elseif (in_array($statusCode, ['I', 'IPC', 'IPG'])) {
                        // Status I, IPC, IPG = Izin
                        $statistics['izin']++;
                    } elseif ($statusCode == 'C') {
                        // Status C = Cuti
                        $statistics['cuti']++;
                    } elseif ($statusCode == 'L') {
                        // Status L = Libur
                        $statistics['libur']++;
                    } elseif ($statusCode == 'A') {
                        // Status A = Alpha
                        $statistics['alpha']++;
                    } elseif ($kategori == '1') {
                        // Kategori 1 dengan status tidak dikenali = Hadir
                        $statistics['hadir']++;
                    }
                }
            }

            Log::info('LAYER 1 - Statistik Manual Berhasil Dihitung', [
                'tanggal' => $tanggal,
                'unit' => $unit,
                'statistics' => $statistics
            ]);
        } else {
            Log::error('LAYER 1 - Gagal mengambil data untuk statistik', [
                'status_code' => $response->status(),
                'response' => $response->body()
            ]);
        }

        return $statistics;
    }

    /**
     * LAYER 2: Mengambil data absensi harian untuk DataTables
     * Method ini memanggil layer 1 untuk statistik dan mengirim ke view
     */
    public function getDataHarian(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $unit = $request->get('unit', 0);

            // LAYER 1: Hitung statistik secara manual
            $statistics = $this->hitungStatistikManual($tanggal, $unit);

            // LAYER 2: Ambil data dengan pagination untuk tabel
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->asForm()->post($this->baseUrl . 'getDataHarian', [
                'draw' => $request->get('draw', 1),
                'start' => $request->get('start', 0),
                'length' => $request->get('length', 10),
                'search[value]' => $request->get('search')['value'] ?? '',
                'order[0][column]' => $request->get('order')[0]['column'] ?? 0,
                'order[0][dir]' => $request->get('order')[0]['dir'] ?? 'asc',
                'columns[0][data]' => 'nama',
                'tanggal' => $tanggal,
                'unit' => $unit,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // Transform data untuk menyesuaikan dengan view
                if (isset($result['data']) && is_array($result['data'])) {
                    $result['data'] = array_map(function($item) {
                        // Determine status based on kategori and status field
                        $statusCode = $item['status'] ?? null;
                        $kategori = $item['kategori'] ?? null;

                        // Jika kategori = 1 dan status null, artinya HADIR
                        if ($kategori == '1' && empty($statusCode)) {
                            $statusDisplay = 'HADIR';
                            $statusCode = 'P';
                        } else {
                            $statusDisplay = $this->mapStatus($statusCode);
                        }

                        return [
                            'id' => $item['id'] ?? null,
                            'userid' => $item['username'] ?? null,
                            'nama' => $item['nama'] ?? '-',
                            'nik' => $item['nik'] ?? '-',
                            'unit' => $item['namaunit'] ?? '-',
                            'unitid' => $item['unitid'] ?? null,
                            'tanggal' => $item['tanggal'] ?? '-',
                            'jammasuk' => $item['jammasuk'] ?? null,
                            'jampulang' => $item['jampulang'] ?? null,
                            'shift' => $item['shift'] ?? null,
                            'kode' => $item['kode'] ?? null,
                            'status' => $statusDisplay,
                            'status_code' => $statusCode,
                            'keterangan' => $item['keterangan'] ?? null,
                            'kategori' => $kategori,
                            'terlambat' => $item['terlambat'] ?? null,
                        ];
                    }, $result['data']);
                }

                // LAYER 2: Tambahkan statistik yang sudah dihitung di Layer 1
                $result['statistics'] = $statistics;

                Log::info('LAYER 2 - Response Final', [
                    'has_statistics' => isset($result['statistics']),
                    'statistics' => $result['statistics'],
                    'data_count' => count($result['data'] ?? [])
                ]);

                return response()->json($result);
            }

            Log::error('API Error getDataHarian: ' . $response->body());
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'statistics' => $statistics,
                'error' => 'Gagal mengambil data dari API'
            ]);

        } catch (\Exception $e) {
            Log::error('Exception getDataHarian: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'statistics' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'cuti' => 0, 'alpha' => 0, 'libur' => 0],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Map status code ke nama status yang lebih mudah dibaca
     */
    private function mapStatus($code)
    {
        if (empty($code)) return 'HADIR';

        $statusMap = [
            'A' => 'ABSEN',
            'C' => 'CUTI',
            'I' => 'IZIN',
            'IPC' => 'IZIN POTONG CUTI',
            'IPG' => 'IZIN POTONG GAJI',
            'L' => 'LIBUR',
            'S' => 'SAKIT',
            'P' => 'HADIR',
            'P6' => 'HADIR',
        ];

        return $statusMap[$code] ?? $code;
    }

    /**
     * Menampilkan halaman lembur bulanan
     */
    public function lembur()
    {
        $pageTitle = 'Data Lembur';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Data Lembur']
        ];

        return view('absensi.lembur', compact('pageTitle', 'breadcrumbs'));
    }

    /**
     * Mengambil data lembur bulanan untuk DataTables
     */
    public function getDataLembur(Request $request)
    {
        try {
            $bulan = $request->get('bulan', date('m'));
            $tahun = $request->get('tahun', date('Y'));

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->asForm()->post($this->baseUrl . 'getDataLembur', [
                'draw' => $request->get('draw', 1),
                'start' => $request->get('start', 0),
                'length' => $request->get('length', 10),
                'search[value]' => $request->get('search')['value'] ?? '',
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // Transform data untuk menyesuaikan dengan view
                if (isset($result['data']) && is_array($result['data'])) {
                    $result['data'] = array_map(function($item) {
                        return [
                            'id' => $item['id'] ?? null,
                            'userid' => $item['username'] ?? null,
                            'nama' => $item['nama'] ?? '-',
                            'nik' => $item['nik'] ?? '-',
                            'unit' => $item['namaunit'] ?? '-',
                            'tanggal' => $item['tanggal'] ?? '-',
                            'jammasuk' => $item['jammasuk'] ?? null,
                            'jampulang' => $item['jampulang'] ?? null,
                            'durasi' => $item['jamlembur'] ?? '-',
                            'keterangan' => $item['keterangan'] ?? '-',
                        ];
                    }, $result['data']);
                }

                return response()->json($result);
            }

            Log::error('API Error getDataLembur: ' . $response->body());
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal mengambil data dari API'
            ]);

        } catch (\Exception $e) {
            Log::error('Exception getDataLembur: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mengambil daftar status absen
     */
    public function getStatusList()
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($this->baseUrl . 'getStatusList');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Gagal mengambil data status'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil daftar unit kerja dari API eksternal
     */
    public function getUnitList()
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($this->baseUrl . 'getUnitList');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data unit'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar karyawan dari API eksternal
     */
    public function getUserList(Request $request)
    {
        try {
            $url = $this->baseUrl . 'getUserList';

            // Jika ada filter unit_id
            if ($request->has('unit_id') && $request->unit_id) {
                $url .= '?unit_id=' . $request->unit_id;
            }

            Log::info('Fetching user list from: ' . $url);

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->timeout(60)->get($url);

            Log::info('User list response status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                Log::info('User list count: ' . (is_array($data) ? count($data) : 'not array'));
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            Log::error('Failed to get user list: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data karyawan: HTTP ' . $response->status()
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception in getUserList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Input absen manual
     */
    public function inputAbsen(Request $request)
    {
        $request->validate([
            'userid' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . 'inputData', [
                'userid' => $request->userid,
                'tanggal' => $request->tanggal,
                'status' => $request->status,
                'keterangan' => $request->keterangan ?? '',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data absen berhasil disimpan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data absen',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update absen manual
     */
    public function updateAbsen(Request $request)
    {
        $request->validate([
            'absenid' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . 'updateData', [
                'absenid' => $request->absenid,
                'tanggal' => $request->tanggal,
                'status' => $request->status,
                'keterangan' => $request->keterangan ?? '',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data absen berhasil diupdate',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data absen',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data absen
     */
    public function deleteAbsen(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . 'deleteData', [
                'id' => $request->id,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data absen berhasil dihapus',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data absen',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Input lembur manual
     */
    public function inputLembur(Request $request)
    {
        $request->validate([
            'userid' => 'required',
            'tanggal' => 'required|date',
            'jammasuk' => 'required',
            'jampulang' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . 'inputLembur', [
                'userid' => $request->userid,
                'tanggal' => $request->tanggal,
                'jammasuk' => $request->jammasuk,
                'jampulang' => $request->jampulang,
                'keterangan' => $request->keterangan ?? '',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data lembur berhasil disimpan',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data lembur',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update lembur manual
     */
    public function updateLembur(Request $request)
    {
        $request->validate([
            'absenid' => 'required',
            'tanggal' => 'required|date',
            'jammasuk' => 'required',
            'jampulang' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post($this->baseUrl . 'updateLembur', [
                'absenid' => $request->absenid,
                'tanggal' => $request->tanggal,
                'jammasuk' => $request->jammasuk,
                'jampulang' => $request->jampulang,
                'keterangan' => $request->keterangan ?? '',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data lembur berhasil diupdate',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data lembur',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data absensi ke Excel
     */
    public function exportData(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            $unit = $request->get('unit', 0);

            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->timeout(60)->get($this->baseUrl . 'getExportData', [
                'tanggal' => $tanggal,
                'unit' => $unit,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $data = $result['data'] ?? [];

                if (empty($data)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data untuk diexport'
                    ], 400);
                }

                // Buat file Excel menggunakan PhpSpreadsheet
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Data Absensi');

                // Header
                $headers = ['No', 'Nama', 'NIK', 'Unit', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Shift', 'Status', 'Keterangan'];
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $sheet->getStyle($col . '1')->getFont()->setBold(true);
                    $sheet->getStyle($col . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('4472C4');
                    $sheet->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
                    $col++;
                }

                // Data rows
                $row = 2;
                $no = 1;
                foreach ($data as $item) {
                    $statusCode = $item['status'] ?? null;
                    $kategori = $item['kategori'] ?? null;

                    // Determine status display
                    if ($kategori == '1' && empty($statusCode)) {
                        $statusDisplay = 'HADIR';
                    } else {
                        $statusDisplay = $this->mapStatus($statusCode);
                    }

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $item['nama'] ?? '-');
                    $sheet->setCellValue('C' . $row, $item['nik'] ?? '-');
                    $sheet->setCellValue('D' . $row, $item['unit'] ?? $item['namaunit'] ?? '-');
                    $sheet->setCellValue('E' . $row, $item['tanggal'] ?? '-');
                    $sheet->setCellValue('F' . $row, $item['jammasuk'] ?? '-');
                    $sheet->setCellValue('G' . $row, $item['jampulang'] ?? '-');
                    $sheet->setCellValue('H' . $row, $item['shift'] ?? '-');
                    $sheet->setCellValue('I' . $row, $statusDisplay);
                    $sheet->setCellValue('J' . $row, $item['keterangan'] ?? '-');
                    $row++;
                    $no++;
                }

                // Auto-size columns
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Generate filename
                $filename = 'Absensi_' . $tanggal . '.xlsx';

                // Create Excel file
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

                // Output to browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: max-age=0');

                $writer->save('php://output');
                exit;
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengexport data dari API'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rekapitulasi absen user - Menghitung dari data harian per tanggal
     */
    public function getRekapitulasiUser(Request $request)
    {
        try {
            $userid = $request->get('userid');
            $awal = $request->get('awal');
            $akhir = $request->get('akhir');

            // Inisialisasi statistik
            $statistics = [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'cuti' => 0,
                'alpha' => 0,
                'telat' => 0,
                'libur' => 0,
            ];

            $historyData = [];

            // Loop melalui setiap tanggal dalam rentang
            $startDate = new \DateTime($awal);
            $endDate = new \DateTime($akhir);
            $interval = new \DateInterval('P1D');
            $dateRange = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

            foreach ($dateRange as $date) {
                $tanggal = $date->format('Y-m-d');

                // Ambil data harian untuk tanggal ini
                $response = Http::withHeaders([
                    'X-API-KEY' => $this->apiKey,
                ])->withoutVerifying()->asForm()->post($this->baseUrl . 'getDataHarian', [
                    'draw' => 1,
                    'start' => 0,
                    'length' => -1,
                    'tanggal' => $tanggal,
                    'unit' => 0,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $allData = $result['data'] ?? [];

                    // Cari data karyawan berdasarkan userid (username/NIK KTP)
                    foreach ($allData as $item) {
                        if (($item['username'] ?? '') == $userid) {
                            $statusCode = $item['status'] ?? null;
                            $kategori = $item['kategori'] ?? null;
                            $terlambat = $item['terlambat'] ?? null;

                            // Hitung status
                            if ($kategori == '1' && empty($statusCode)) {
                                $statistics['hadir']++;
                                $statusDisplay = 'HADIR';
                            } else {
                                $statusDisplay = $this->mapStatus($statusCode);
                                switch ($statusCode) {
                                    case 'S':
                                        $statistics['sakit']++;
                                        break;
                                    case 'I':
                                    case 'IPC':
                                    case 'IPG':
                                        $statistics['izin']++;
                                        break;
                                    case 'C':
                                        $statistics['cuti']++;
                                        break;
                                    case 'L':
                                        $statistics['libur']++;
                                        break;
                                    case 'A':
                                        $statistics['alpha']++;
                                        break;
                                    default:
                                        if ($kategori == '1') {
                                            $statistics['hadir']++;
                                            $statusDisplay = 'HADIR';
                                        }
                                }
                            }

                            // Hitung telat
                            if (!empty($terlambat) && $terlambat > 0) {
                                $statistics['telat']++;
                            }

                            // Simpan untuk history
                            $historyData[] = [
                                'tanggal' => $item['tanggal'] ?? $tanggal,
                                'jammasuk' => $item['jammasuk'] ?? null,
                                'jampulang' => $item['jampulang'] ?? null,
                                'shift' => $item['shift'] ?? null,
                                'status' => $statusDisplay,
                                'keterangan' => $item['keterangan'] ?? null,
                                'terlambat' => $terlambat,
                            ];

                            break; // Found the user, move to next date
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'history' => $historyData
            ]);

        } catch (\Exception $e) {
            Log::error('Exception getRekapitulasiUser: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman rekapitulasi absensi
     */
    public function rekapitulasi()
    {
        $pageTitle = 'Rekapitulasi Absensi';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Rekapitulasi Absensi']
        ];

        return view('absensi.rekapitulasi', compact('pageTitle', 'breadcrumbs'));
    }

    /**
     * Mengambil data absensi bulanan per user
     */
    public function getAbsenBulanan(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->withoutVerifying()->get($this->baseUrl . 'getAbsenBulanan', [
                'idkaryawan' => $request->get('idkaryawan'),
                'bulan' => $request->get('bulan'),
                'tahun' => $request->get('tahun'),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $apiData = $result['data'] ?? [];

                // Transform each record
                $transformedData = array_map(function($item) {
                    $statusCode = $item['status'] ?? null;
                    $kategori = $item['kategori'] ?? null;

                    // Determine status display
                    if ($kategori == '1' && empty($statusCode)) {
                        $statusDisplay = 'HADIR';
                    } else {
                        $statusDisplay = $this->mapStatus($statusCode);
                    }

                    return [
                        'tanggal' => $item['tanggal'] ?? null,
                        'jammasuk' => $item['jammasuk'] ?? null,
                        'jampulang' => $item['jampulang'] ?? null,
                        'shift' => $item['shift'] ?? null,
                        'status' => $statusDisplay,
                        'keterangan' => $item['keterangan'] ?? null,
                    ];
                }, $apiData);

                return response()->json([
                    'success' => true,
                    'data' => $transformedData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data absensi bulanan'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
