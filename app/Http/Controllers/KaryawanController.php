<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\KaryawanExport;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Karyawan::select('unit')->distinct()->orderBy('unit')->pluck('unit');
        $statuses = ['Tetap', 'PKWT', 'Kontrak'];

        $pageTitle = 'Data Karyawan';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Data Karyawan']
        ];

        return view('karyawan.index', compact('units', 'statuses', 'pageTitle', 'breadcrumbs'));
    }

    /**
     * Get data for DataTables (Server-side processing)
     */
    public function getData(Request $request)
    {
        try {
            $data = Karyawan::select('*');

            // Filter by unit
            if ($request->has('unit') && !empty($request->unit)) {
                $data->where('unit', $request->unit);
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $data->where('statusPegawai', $request->status);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('umur', function($row){
                    if ($row->tglLahir) {
                        $birthDate = \Carbon\Carbon::parse($row->tglLahir);
                        $umurTahun = (int) $birthDate->diffInYears(now());
                        $umurBulan = (int) $birthDate->diffInMonths(now()) % 12;
                        return $umurTahun . ' tahun ' . $umurBulan . ' bulan';
                    }
                    return '-';
                })
                ->addColumn('umur_bulan', function($row){
                    if ($row->tglLahir) {
                        $birthDate = \Carbon\Carbon::parse($row->tglLahir);
                        $umurBulan = (int) $birthDate->diffInMonths(now());
                        return $umurBulan;
                    }
                    return '-';
                })
                ->addColumn('tanggal_pensiun', function($row){
                    if ($row->tglLahir) {
                        $birthDate = \Carbon\Carbon::parse($row->tglLahir);
                        $pensiunDate = $birthDate->copy()->addYears(56);
                        return $pensiunDate->format('d-m-Y');
                    }
                    return '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-info btn-icon me-1 btn-detail" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-primary btn-icon me-1 btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-icon btn-delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </a>';
                    return $btn;
                })
                ->editColumn('tglLahir', function($row){
                    return $row->tglLahir ? \Carbon\Carbon::parse($row->tglLahir)->format('d-m-Y') : '-';
                })
                ->editColumn('tglMulaiKerja', function($row){
                    return $row->tglMulaiKerja ? \Carbon\Carbon::parse($row->tglMulaiKerja)->format('d-m-Y') : '-';
                })
                ->editColumn('skTetap', function($row){
                    return $row->skTetap ? $row->skTetap : '-';
                })
                ->editColumn('nikKtp', function($row){
                    return $row->nikKtp ? $row->nikKtp : '-';
                })
                ->editColumn('gol', function($row){
                    return $row->gol ? $row->gol : '-';
                })
                ->editColumn('pendidikan', function($row){
                    return $row->pendidikan ? $row->pendidikan : '-';
                })
                ->editColumn('tamatan', function($row){
                    return $row->tamatan ? $row->tamatan : '-';
                })
                ->editColumn('noHp', function($row){
                    return $row->noHp ? $row->noHp : '-';
                })
                ->editColumn('email', function($row){
                    return $row->email ? $row->email : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Error in getData: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filter options for dropdowns
     */
    public function getFilters()
    {
        $units = Karyawan::select('unit')->distinct()->orderBy('unit')->pluck('unit');
        $statuses = ['Tetap', 'PKWT', 'Kontrak'];

        return response()->json([
            'units' => $units,
            'statuses' => $statuses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nikKry' => 'required|string|max:50|unique:karyawan,nikKry',
            'namaKaryawan' => 'required|string|max:255',
            'nikKtp' => 'required|string|max:16',
            'unit' => 'required|string|max:100',
            'gol' => 'nullable|string|max:50',
            'profesi' => 'required|string|max:100',
            'statusPegawai' => 'required|string|max:50',
            'tempatLahir' => 'required|string|max:100',
            'tglLahir' => 'required|date',
            'tglMulaiKerja' => 'nullable|date',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'skTetap' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'tamatan' => 'nullable|string|max:255',
            'noHp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $karyawan = Karyawan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil ditambahkan',
                'data' => $karyawan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $karyawan = Karyawan::find($id);

            if (!$karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data karyawan tidak ditemukan'
                ], 404);
            }

            // Calculate umur
            $umurTahun = null;
            $umurBulan = null;
            $tglLahirFormatted = null;
            $tglMulaiKerjaFormatted = null;
            $tglPensiunFormatted = null;

            if ($karyawan->tglLahir) {
                try {
                    $birthDate = \Carbon\Carbon::parse($karyawan->tglLahir);
                    $umurTahun = (int) $birthDate->diffInYears(now());
                    $umurBulan = (int) $birthDate->diffInMonths(now());
                    $tglLahirFormatted = $birthDate->format('d-m-Y');
                    
                    // Hitung tanggal pensiun (56 tahun dari tanggal lahir)
                    $pensiunDate = $birthDate->copy()->addYears(56);
                    $tglPensiunFormatted = $pensiunDate->format('d-m-Y');
                } catch (\Exception $e) {
                    \Log::warning('Error parsing tglLahir for karyawan ' . $id . ': ' . $e->getMessage());
                }
            }

            if ($karyawan->tglMulaiKerja) {
                try {
                    $tglMulaiKerjaFormatted = \Carbon\Carbon::parse($karyawan->tglMulaiKerja)->format('d-m-Y');
                } catch (\Exception $e) {
                    \Log::warning('Error parsing tglMulaiKerja for karyawan ' . $id . ': ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'karyawan' => $karyawan,
                    'umur_tahun' => $umurTahun,
                    'umur_bulan' => $umurBulan,
                    'tgl_lahir_formatted' => $tglLahirFormatted,
                    'tgl_masuk_kerja_formatted' => $tglMulaiKerjaFormatted,
                    'tgl_pensiun_formatted' => $tglPensiunFormatted,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in show method for karyawan ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data karyawan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nikKry' => 'required|string|max:50|unique:karyawan,nikKry,'.$id,
            'namaKaryawan' => 'required|string|max:255',
            'nikKtp' => 'required|string|max:16',
            'unit' => 'required|string|max:100',
            'gol' => 'nullable|string|max:50',
            'profesi' => 'required|string|max:100',
            'statusPegawai' => 'required|string|max:50',
            'tempatLahir' => 'required|string|max:100',
            'tglLahir' => 'required|date',
            'tglMulaiKerja' => 'nullable|date',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
            'skTetap' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'tamatan' => 'nullable|string|max:255',
            'noHp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $karyawan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diupdate',
                'data' => $karyawan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        try {
            $karyawan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all karyawan data
     */
    public function deleteAll()
    {
        try {
            $count = Karyawan::count();

            if ($count == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data karyawan untuk dihapus'
                ], 404);
            }

            Karyawan::truncate();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$count} data karyawan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus semua data karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Excel
     */
    public function export()
    {
        return Excel::download(new KaryawanExport, 'karyawan_'.date('YmdHis').'.xlsx');
    }

    /**
     * Import data from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File harus berformat Excel (xlsx, xls, csv) dan maksimal 2MB',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $import = new KaryawanImport;
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();
            $errors = $import->errors();

            $errorMessages = [];

            // Collect validation failures
            if (!empty($failures) && count($failures) > 0) {
                foreach ($failures as $failure) {
                    $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                }
            }

            // Collect other errors
            if (!empty($errors) && count($errors) > 0) {
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
            }

            if (!empty($errorMessages)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import selesai dengan beberapa error',
                    'errors' => $errorMessages,
                    'partial' => true
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data karyawan berhasil diimport'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal pada beberapa baris',
                'errors' => $errorMessages
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport data karyawan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $headers = [
            'NIK KRY',
            'NAMA KARYAWAN',
            'NIK KTP',
            'UNIT',
            'GOL',
            'PROFESI',
            'STATUS PEGAWAI',
            'TEMPAT LAHIR',
            'TGL_LAHIR',
            'JENIS KELAMIN',
            'TGL MASUK KERJA',
            'SK TETAP',
            'PENDIDIKAN',
            'TAMATAN',
            'No HP',
            'EMAIL',
            'ALAMAT'
        ];

        $filename = 'template_karyawan.csv';
        $handle = fopen('php://temp', 'w');

        // Add BOM for UTF-8 encoding
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, $headers);

        // Add sample data
        fputcsv($handle, [
            '2258/IS/72015',
            'SUCI RAHMADANI, A.Md',
            '1375034603920002',
            'AKUNTANSI',
            'IIA',
            'PENATA AKUNTANSI',
            'TETAP',
            'BUKITTINGGI',
            '01 Januari 1990',
            'Perempuan',
            '01 Januari 2015',
            '82/SK/DE/YARSI/VI-2015',
            'D3 AKUNTANSI',
            'AKTAN BOEKITTINGGI',
            '082388328768',
            'sucirahmadani1344@gmail.com',
            'Jl. Sudirman No. 123'
        ]);

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
