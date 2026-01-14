<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Events\AfterSheet;

class KaryawanExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Karyawan::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
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
            'ALAMAT',
            'UMUR TAHUN',
            'UMUR BULAN',
        ];
    }

    /**
     * @param mixed $karyawan
     */
    public function map($karyawan): array
    {
        static $no = 0;
        $no++;

        // Hitung umur dalam tahun
        $umurTahun = '';
        if ($karyawan->tglLahir) {
            $birthDate = \Carbon\Carbon::parse($karyawan->tglLahir);
            $umurTahunValue = (int) $birthDate->diffInYears(now());
            $umurTahun = "\t" . $umurTahunValue;
        }

        // Hitung umur dalam bulan
        $umurBulan = '';
        if ($karyawan->tglLahir) {
            $birthDate = \Carbon\Carbon::parse($karyawan->tglLahir);
            $umurBulanValue = (int) $birthDate->diffInMonths(now());
            $umurBulan = "\t" . $umurBulanValue;
        }

        return [
            "\t" . $no,
            "\t" . $karyawan->nikKry,
            $karyawan->namaKaryawan,
            "\t" . $karyawan->nikKtp,
            $karyawan->unit,
            $karyawan->gol,
            $karyawan->profesi,
            $karyawan->statusPegawai,
            $karyawan->tempatLahir,
            $karyawan->tglLahir ? $karyawan->tglLahir->format('d-m-Y') : '',
            $karyawan->jenisKelamin,
            $karyawan->tglMulaiKerja ? $karyawan->tglMulaiKerja->format('d-m-Y') : '',
            $karyawan->skTetap ? $karyawan->skTetap : '',
            $karyawan->pendidikan,
            $karyawan->tamatan,
            "\t" . $karyawan->noHp,
            $karyawan->email,
            $karyawan->alamat,
            $umurTahun,
            $umurBulan,
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 30,
            'D' => 20,
            'E' => 25,
            'F' => 15,
            'G' => 25,
            'H' => 20,
            'I' => 20,
            'J' => 18,
            'K' => 15,
            'L' => 18,
            'M' => 18,
            'N' => 15,
            'O' => 35,
            'P' => 18,
            'Q' => 30,
            'R' => 40,
            'S' => 12,
            'T' => 12,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get highest row and column
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Format all cells as text to prevent scientific notation
                $sheet->getStyle('A2:' . $highestColumn . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            },
        ];
    }
}
