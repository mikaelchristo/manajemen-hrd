<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class KaryawanImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnError,
    SkipsOnFailure,
    WithChunkReading
{
    use SkipsErrors, SkipsFailures;

    public function chunkSize(): int
    {
        return 100;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Log raw row data for debugging
            Log::info('Processing row', ['row_keys' => array_keys($row), 'row_data' => $row]);

            // Get values directly from row - sesuai dengan header Excel:
            // NIK KRY | NAMA KARYAWAN | NIK KTP | UNIT | GOL | PROFESI | STATUS PEGAWAI |
            // TEMPAT LAHIR | TGL_LAHIR | JENIS KELAMIN | TGL MASUK KERJA | SK TETAP |
            // PENDIDIKAN | TAMATAN | No HP | EMAIL | ALAMAT

            $nikKaryawan = $row['nik_kry'] ?? null;
            $namaKaryawan = $row['nama_karyawan'] ?? null;
            $nikKtp = $row['nik_ktp'] ?? null;
            $unit = $row['unit'] ?? null;
            $golongan = $row['gol'] ?? null;
            $profesi = $row['profesi'] ?? null;
            $statusPegawai = $row['status_pegawai'] ?? null;
            $tempatLahir = $row['tempat_lahir'] ?? null;
            $tanggalLahir = $row['tgl_lahir'] ?? null;
            $jenisKelamin = $row['jenis_kelamin'] ?? null;
            $tanggalMasukKerja = $row['tgl_masuk_kerja'] ?? null;
            $skTetap = $row['sk_tetap'] ?? null; // Ini adalah nomor SK, bukan tanggal
            $pendidikan = $row['pendidikan'] ?? null;
            $tamatan = $row['tamatan'] ?? null;
            $noHp = $row['no_hp'] ?? null;
            $email = $row['email'] ?? null;
            $alamat = $row['alamat'] ?? null;

            // Skip if critical fields are empty
            if (empty(trim($nikKaryawan ?? '')) || empty(trim($namaKaryawan ?? '')) || empty(trim($nikKtp ?? ''))) {
                Log::warning('Skipping row: Missing critical fields', [
                    'nikKaryawan' => $nikKaryawan,
                    'namaKaryawan' => $namaKaryawan,
                    'nikKtp' => $nikKtp
                ]);
                return null;
            }

            // Trim values
            $nikKaryawan = trim($nikKaryawan);
            $namaKaryawan = trim($namaKaryawan);
            $nikKtp = trim($nikKtp);

            // Cek apakah NIK sudah ada
            if (Karyawan::where('nikKry', $nikKaryawan)->exists()) {
                Log::info('Skipping duplicate NIK: ' . $nikKaryawan);
                return null;
            }

            // Transform tanggal lahir
            $tglLahir = $this->transformDate($tanggalLahir);

            if (!$tglLahir) {
                Log::warning('Invalid date for NIK: ' . $nikKaryawan, ['date' => $tanggalLahir]);
                return null;
            }

            // Transform tanggal masuk kerja (buat nullable jika kosong)
            $tglMulaiKerja = !empty($tanggalMasukKerja) ? $this->transformDate($tanggalMasukKerja) : null;

            // SK Tetap adalah nomor SK (string), bukan tanggal - jadi langsung simpan sebagai string
            $noSkTetap = !empty($skTetap) ? trim($skTetap) : null;

            // Set default values
            $unit = !empty($unit) ? trim($unit) : 'N/A';
            $profesi = !empty($profesi) ? trim($profesi) : 'N/A';
            $statusPegawai = !empty($statusPegawai) ? trim($statusPegawai) : 'N/A';
            $tempatLahir = !empty($tempatLahir) ? trim($tempatLahir) : 'N/A';
            $jenisKelamin = !empty($jenisKelamin) ? trim($jenisKelamin) : 'Laki-laki';

            $karyawanData = [
                'nikKry' => $nikKaryawan,
                'namaKaryawan' => $namaKaryawan,
                'nikKtp' => $nikKtp,
                'unit' => $unit,
                'gol' => !empty($golongan) ? trim($golongan) : null,
                'profesi' => $profesi,
                'statusPegawai' => $statusPegawai,
                'tempatLahir' => $tempatLahir,
                'tglLahir' => $tglLahir,
                'tglMulaiKerja' => $tglMulaiKerja,
                'jenisKelamin' => $jenisKelamin,
                'skTetap' => $noSkTetap,
                'pendidikan' => !empty($pendidikan) ? trim($pendidikan) : null,
                'tamatan' => !empty($tamatan) ? trim($tamatan) : null,
                'noHp' => !empty($noHp) ? trim($noHp) : null,
                'email' => !empty($email) ? trim($email) : null,
                'alamat' => !empty($alamat) ? trim($alamat) : null,
            ];

            Log::info('Creating karyawan', ['data' => $karyawanData]);

            return new Karyawan($karyawanData);
        } catch (\Exception $e) {
            Log::error('Error processing row: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }

    /**
     * Transform date to proper format
     * Support format: "07 April 2000", "21 Maret 1996", dll
     */
    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Check if it's Excel date serial number
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            // Clean the value
            $value = trim($value);

            // Indonesian month names mapping
            $indonesianMonths = [
                'Januari' => 'January',
                'Februari' => 'February',
                'Maret' => 'March',
                'April' => 'April',
                'Mei' => 'May',
                'Juni' => 'June',
                'Juli' => 'July',
                'Agustus' => 'August',
                'September' => 'September',
                'Oktober' => 'October',
                'November' => 'November',
                'Desember' => 'December',
            ];

            // Replace Indonesian month names with English
            $dateStr = str_replace(array_keys($indonesianMonths), array_values($indonesianMonths), $value);

            // Try parsing format like "07 April 2000"
            try {
                $date = Carbon::createFromFormat('d F Y', $dateStr);
                if ($date && $date->year >= 1900 && $date->year <= 2100) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Continue to next format
            }

            // Try other common date formats
            $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'd-M-Y', 'm/d/Y', 'Y/m/d', 'd F Y', 'j F Y'];
            foreach ($formats as $format) {
                try {
                    $date = Carbon::createFromFormat($format, $dateStr);
                    if ($date && $date->year >= 1900 && $date->year <= 2100) {
                        return $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Last resort: try Carbon parse
            $parsed = Carbon::parse($dateStr);
            if ($parsed && $parsed->year >= 1900 && $parsed->year <= 2100) {
                return $parsed->format('Y-m-d');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
