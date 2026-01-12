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
        if ($request->ajax()) {
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
                        $umur = (int) $birthDate->diffInYears(now());
                        return $umur . ' tahun';
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
                    return $row->tglLahir ? $row->tglLahir->format('d-m-Y') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
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
            'gol' => 'required|string|max:50',
            'profesi' => 'required|string|max:100',
            'statusPegawai' => 'required|string|max:50',
            'tempatLahir' => 'required|string|max:100',
            'tglLahir' => 'required|date',
            'tglMulaiKerja' => 'required|date',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
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
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data karyawan tidak ditemukan'
            ], 404);
        }

        // Calculate umur
        $umur = null;
        if ($karyawan->tglLahir) {
            $birthDate = \Carbon\Carbon::parse($karyawan->tglLahir);
            $umur = (int) $birthDate->diffInYears(now()) . ' tahun';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'karyawan' => $karyawan,
                'umur' => $umur,
                'tgl_lahir_formatted' => $karyawan->tglLahir ? $karyawan->tglLahir->format('d-m-Y') : null
            ]
        ]);
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
            'gol' => 'required|string|max:50',
            'profesi' => 'required|string|max:100',
            'statusPegawai' => 'required|string|max:50',
            'tempatLahir' => 'required|string|max:100',
            'tglLahir' => 'required|date',
            'tglMulaiKerja' => 'required|date',
            'jenisKelamin' => 'required|in:Laki-laki,Perempuan',
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
            'NIK Karyawan',
            'Nama Karyawan',
            'NIK KTP',
            'Unit',
            'Golongan',
            'Profesi',
            'Status Pegawai',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Jenis Kelamin'
        ];

        $filename = 'template_karyawan.csv';
        $handle = fopen('php://temp', 'w');

        // Add BOM for UTF-8 encoding
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, $headers);

        // Add sample data
        fputcsv($handle, [
            'K001',
            'John Doe',
            '1234567890123456',
            'IT Department',
            'III/A',
            'Software Developer',
            'Tetap',
            'Jakarta',
            '01 Januari 1990',
            '35',
            'Laki-laki'
        ]);

        fputcsv($handle, [
            'K002',
            'Jane Smith',
            '1234567890123457',
            'Human Resources',
            'III/B',
            'HR Manager',
            'Tetap',
            'Bandung',
            '15 Mei 1988',
            '37',
            'Perempuan'
        ]);

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
