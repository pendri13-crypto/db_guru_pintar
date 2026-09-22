<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::getSortedClasses();
        $query = Student::with('schoolClass');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();
        $totalStudents = Student::count();
        $classesWithStudentsCount = $classes->where('students_count', '>', 0)->count();

        return view('students.index', compact('students', 'classes', 'totalStudents', 'classesWithStudentsCount'));
    }

    public function create()
    {
        $classes = SchoolClass::getSortedClasses();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn',
            'nis' => 'nullable|string',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $qrCode = 'GP-STU-' . $request->nisn;

        Student::create(array_merge($request->all(), [
            'qr_code' => $qrCode,
            'status' => 'Aktif',
        ]));

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::getSortedClasses();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'nis' => 'nullable|string',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'status' => 'required|in:Aktif,Mutasi,Lulus',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus!');
    }

    public function destroyAll(Request $request)
    {
        $classId = $request->input('class_id');

        if ($classId && $classId !== 'all') {
            $schoolClass = SchoolClass::find($classId);
            $count = Student::where('class_id', $classId)->count();

            if ($count === 0) {
                return redirect()->route('students.index')->with('error', 'Tidak ada data siswa yang ditemukan pada kelas yang dipilih.');
            }

            DB::transaction(function () use ($classId) {
                Student::where('class_id', $classId)->delete();
            });

            $className = $schoolClass ? 'Kelas ' . $schoolClass->name : 'kelas terpilih';
            return redirect()->route('students.index')->with('success', "Berhasil menghapus {$count} data siswa dari {$className} beserta data terkait.");
        }

        $count = Student::count();

        if ($count === 0) {
            return redirect()->route('students.index')->with('error', 'Tidak ada data siswa untuk dihapus.');
        }

        DB::transaction(function () {
            Student::query()->delete();
        });

        return redirect()->route('students.index')->with('success', "Berhasil menghapus SELURUH ({$count}) data siswa beserta riwayat presensi, nilai, dan bimbingan terkait.");
    }

    /**
     * Unduh Template Excel untuk Import Data Siswa
     */
    public function template()
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Template Siswa
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template_Import_Siswa');

        // Header Title
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'FORMAT IMPORT DATA MASTER SISWA - GURU PINTAR');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Subtitle / Instruction
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Petunjuk: Kolom bertanda (*) WAJIB diisi (NISN, Nama Lengkap, Kelas, JK). Nilai JK: L atau P. Jangan mengubah urutan baris header (Baris 4).');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));

        // Table Header (Row 4)
        $headers = [
            'A4' => 'NISN *',
            'B4' => 'NIS',
            'C4' => 'Nama Lengkap *',
            'D4' => 'Kelas *',
            'E4' => 'Jenis Kelamin (L/P) *',
            'F4' => 'No HP / WhatsApp',
            'G4' => 'No HP Orang Tua',
            'H4' => 'Email',
            'I4' => 'Alamat',
            'J4' => 'Status (Aktif/Mutasi/Lulus)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style Header Row
        $sheet->getStyle('A4:J4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1D4ED8'],
                ],
            ],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Dummy Sample Rows
        $sampleData = [
            ['0089123001', '24001', 'Ahmad Rizky Pratama', '8A', 'L', '081234567801', '081298765401', 'ahmad.rizky@sekolah.sch.id', 'Jl. Merdeka No. 12, Jakarta', 'Aktif'],
            ['0089123002', '24002', 'Siti Nurhaliza Putri', '8A', 'P', '081234567802', '081298765402', 'siti.nur@sekolah.sch.id', 'Jl. Mawar Melati No. 45, Jakarta', 'Aktif'],
            ['0089123003', '24003', 'Budi Santoso Wibowo', '8B', 'L', '081234567803', '081298765403', 'budi.santoso@sekolah.sch.id', 'Jl. Kenanga Asri No. 8, Jakarta', 'Aktif'],
            ['0089123004', '24004', 'Dewi Lestari Anggraeni', '8B', 'P', '081234567804', '081298765404', 'dewi.lestari@sekolah.sch.id', 'Jl. Pahlawan No. 99, Jakarta', 'Aktif'],
        ];

        $rowNum = 5;
        foreach ($sampleData as $row) {
            $sheet->setCellValueExplicit('A' . $rowNum, $row[0], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $rowNum, $row[1], DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $sheet->setCellValue('D' . $rowNum, $row[3]);
            $sheet->setCellValue('E' . $rowNum, $row[4]);
            $sheet->setCellValueExplicit('F' . $rowNum, $row[5], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $rowNum, $row[6], DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $rowNum, $row[7]);
            $sheet->setCellValue('I' . $rowNum, $row[8]);
            $sheet->setCellValue('J' . $rowNum, $row[9]);

            $sheet->getStyle("A{$rowNum}:J{$rowNum}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        // Auto size columns for sheet 1
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 2: Daftar Kelas Referensi
        $classSheet = $spreadsheet->createSheet();
        $classSheet->setTitle('Referensi_Kelas');

        $classSheet->setCellValue('A1', 'DAFTAR KELAS YANG TERSEDIA DI SISTEM');
        $classSheet->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $classSheet->mergeCells('A1:C1');

        $classSheet->setCellValue('A3', 'Nama Kelas');
        $classSheet->setCellValue('B3', 'Tingkat / Jenjang');
        $classSheet->setCellValue('C3', 'Tahun Ajaran');
        $classSheet->getStyle('A3:C3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '334155']]],
        ]);

        $classes = SchoolClass::orderBy('name')->get();
        $cRowNum = 4;
        foreach ($classes as $cls) {
            $classSheet->setCellValue('A' . $cRowNum, $cls->name);
            $classSheet->setCellValue('B' . $cRowNum, 'Kelas ' . $cls->level);
            $classSheet->setCellValue('C' . $cRowNum, $cls->academic_year ?? '2025/2026');
            $classSheet->getStyle("A{$cRowNum}:C{$cRowNum}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
            $classSheet->getStyle("A{$cRowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $classSheet->getStyle("B{$cRowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $classSheet->getStyle("C{$cRowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cRowNum++;
        }

        foreach (['A', 'B', 'C'] as $col) {
            $classSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set active sheet back to sheet 0
        $spreadsheet->setActiveSheetIndex(0);

        $fileName = 'Template_Import_Siswa_GuruPintar.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Ekspor Data Siswa ke File Excel (.xlsx)
     */
    public function export(Request $request)
    {
        $query = Student::with('schoolClass');

        $filterLabel = 'Semua Kelas';
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
            $cls = SchoolClass::find($request->class_id);
            if ($cls) {
                $filterLabel = 'Kelas ' . $cls->name;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->orderBy('name')->get();
        $setting = SchoolSetting::first();
        $schoolName = $setting->school_name ?? 'SMP NEGERI UNGGULAN INDONESIA';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data_Siswa');

        // Document Title Banner
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'DATA INDUK SISWA - ' . strtoupper($schoolName));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('1E3A8A'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A2', 'Cakupan: ' . $filterLabel . ' | Tanggal Unduh: ' . date('d F Y, H:i') . ' WIB | Total: ' . $students->count() . ' Siswa');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('475569'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers (Row 4)
        $headers = [
            'A4' => 'No',
            'B4' => 'NISN',
            'C4' => 'NIS',
            'D4' => 'Nama Lengkap',
            'E4' => 'Kelas',
            'F4' => 'JK',
            'G4' => 'No. Telepon / WA',
            'H4' => 'No. HP Orang Tua',
            'I4' => 'Email',
            'J4' => 'Alamat',
            'K4' => 'Status',
            'L4' => 'Kode QR Presensi',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A4:L4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0F172A']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $rowNum = 5;
        foreach ($students as $idx => $stu) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValueExplicit('B' . $rowNum, $stu->nisn, DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $rowNum, $stu->nis ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $rowNum, $stu->name);
            $sheet->setCellValue('E' . $rowNum, $stu->schoolClass ? $stu->schoolClass->name : '-');
            $sheet->setCellValue('F' . $rowNum, $stu->gender);
            $sheet->setCellValueExplicit('G' . $rowNum, $stu->phone ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $rowNum, $stu->parent_phone ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValue('I' . $rowNum, $stu->email ?? '-');
            $sheet->setCellValue('J' . $rowNum, $stu->address ?? '-');
            $sheet->setCellValue('K' . $rowNum, $stu->status);
            $sheet->setCellValue('L' . $rowNum, $stu->qr_code ?? ('GP-STU-' . $stu->nisn));

            $bgColor = ($rowNum % 2 == 0) ? 'F8FAFC' : 'FFFFFF';
            $sheet->getStyle("A{$rowNum}:L{$rowNum}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            ]);

            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("L{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $cleanFilter = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filterLabel);
        $fileName = 'Data_Siswa_' . $cleanFilter . '_' . date('Y-m-d_His') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import Data Siswa dari File Excel (.xlsx, .xls, .csv)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'duplicate_action' => 'required|in:skip,update',
        ], [
            'file.required' => 'Silakan pilih file Excel / CSV terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file maksimal adalah 10MB.',
        ]);

        $file = $request->file('file');
        $duplicateAction = $request->input('duplicate_action', 'skip');

        try {
            $reader = IOFactory::createReaderForFile($file->getPathname());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return redirect()->route('students.index')->with('error', 'File Excel kosong atau tidak memiliki data yang valid.');
        }

        // Cache classes
        $classesCache = [];
        foreach (SchoolClass::all() as $cls) {
            $classesCache[strtoupper(trim($cls->name))] = $cls->id;
        }

        $headerRowIndex = null;
        $colMap = [
            'nisn' => null,
            'nis' => null,
            'name' => null,
            'class' => null,
            'gender' => null,
            'phone' => null,
            'parent_phone' => null,
            'email' => null,
            'address' => null,
            'status' => null,
        ];

        // Detect header row
        foreach ($rows as $rIndex => $row) {
            $rowValues = array_map(function ($val) {
                return strtolower(trim((string) $val));
            }, $row);

            $hasNisn = false;
            $hasName = false;

            foreach ($rowValues as $colKey => $text) {
                if (str_contains($text, 'nisn')) {
                    $colMap['nisn'] = $colKey;
                    $hasNisn = true;
                } elseif (str_contains($text, 'nama')) {
                    $colMap['name'] = $colKey;
                    $hasName = true;
                } elseif (str_contains($text, 'nis') && !str_contains($text, 'nisn')) {
                    $colMap['nis'] = $colKey;
                } elseif (str_contains($text, 'kelas') || str_contains($text, 'rombel')) {
                    $colMap['class'] = $colKey;
                } elseif (str_contains($text, 'kelamin') || $text === 'jk') {
                    $colMap['gender'] = $colKey;
                } elseif (str_contains($text, 'ortu') || str_contains($text, 'wali')) {
                    $colMap['parent_phone'] = $colKey;
                } elseif (str_contains($text, 'hp') || str_contains($text, 'telepon') || str_contains($text, 'wa')) {
                    if (!$colMap['phone']) {
                        $colMap['phone'] = $colKey;
                    }
                } elseif (str_contains($text, 'email')) {
                    $colMap['email'] = $colKey;
                } elseif (str_contains($text, 'alamat')) {
                    $colMap['address'] = $colKey;
                } elseif (str_contains($text, 'status')) {
                    $colMap['status'] = $colKey;
                }
            }

            if ($hasNisn && $hasName) {
                $headerRowIndex = $rIndex;
                break;
            }
        }

        // Fallback standard columns if header detection didn't match all
        if (!$headerRowIndex) {
            $headerRowIndex = 4;
            $colMap = [
                'nisn' => 'A',
                'nis' => 'B',
                'name' => 'C',
                'class' => 'D',
                'gender' => 'E',
                'phone' => 'F',
                'parent_phone' => 'G',
                'email' => 'H',
                'address' => 'I',
                'status' => 'J',
            ];
        } else {
            $defaultLetters = ['A' => 'nisn', 'B' => 'nis', 'C' => 'name', 'D' => 'class', 'E' => 'gender', 'F' => 'phone', 'G' => 'parent_phone', 'H' => 'email', 'I' => 'address', 'J' => 'status'];
            foreach ($defaultLetters as $ltr => $k) {
                if (empty($colMap[$k])) {
                    $colMap[$k] = $ltr;
                }
            }
        }

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $failedRows = [];

        DB::beginTransaction();
        try {
            $existingNisns = array_flip(Student::pluck('nisn')->toArray());
            $insertData = [];
            $updateData = [];
            $now = now();

            foreach ($rows as $rIndex => $row) {
                if ($rIndex <= $headerRowIndex) {
                    continue; // Skip header and preceding title rows
                }

                $nisn = trim((string) ($row[$colMap['nisn']] ?? ''));
                $name = trim((string) ($row[$colMap['name']] ?? ''));

                // Skip blank row
                if (empty($nisn) && empty($name)) {
                    continue;
                }

                // Validation
                if (empty($nisn) || empty($name)) {
                    $failedRows[] = "Baris {$rIndex}: NISN atau Nama Lengkap kosong.";
                    continue;
                }

                $nis = trim((string) ($row[$colMap['nis']] ?? ''));
                $classNameRaw = trim((string) ($row[$colMap['class']] ?? ''));
                $genderRaw = strtoupper(trim((string) ($row[$colMap['gender']] ?? 'L')));
                $phone = trim((string) ($row[$colMap['phone']] ?? ''));
                $parentPhone = trim((string) ($row[$colMap['parent_phone']] ?? ''));
                $email = trim((string) ($row[$colMap['email']] ?? ''));
                $address = trim((string) ($row[$colMap['address']] ?? ''));
                $statusRaw = trim((string) ($row[$colMap['status']] ?? 'Aktif'));

                // Parse Gender
                $gender = (str_starts_with($genderRaw, 'P') || str_contains(strtolower($genderRaw), 'perempuan')) ? 'P' : 'L';

                // Parse Status
                $status = in_array(ucfirst(strtolower($statusRaw)), ['Aktif', 'Mutasi', 'Lulus']) ? ucfirst(strtolower($statusRaw)) : 'Aktif';

                // Resolve Class
                $cleanClassName = preg_replace('/^(kelas|kls)\s+/i', '', $classNameRaw);
                $cleanClassName = trim($cleanClassName);
                if (empty($cleanClassName)) {
                    $cleanClassName = '8A';
                }

                $classKey = strtoupper($cleanClassName);
                if (isset($classesCache[$classKey])) {
                    $classId = $classesCache[$classKey];
                } else {
                    preg_match('/\d+/', $cleanClassName, $matches);
                    $level = $matches[0] ?? '8';

                    $newClass = SchoolClass::create([
                        'name' => $cleanClassName,
                        'level' => $level,
                        'academic_year' => '2025/2026',
                    ]);
                    $classId = $newClass->id;
                    $classesCache[$classKey] = $classId;
                }

                $qrCode = 'GP-STU-' . $nisn;

                $data = [
                    'class_id' => $classId,
                    'nisn' => $nisn,
                    'nis' => !empty($nis) ? $nis : null,
                    'name' => $name,
                    'gender' => $gender,
                    'phone' => !empty($phone) ? $phone : null,
                    'parent_phone' => !empty($parentPhone) ? $parentPhone : null,
                    'email' => !empty($email) ? $email : null,
                    'address' => !empty($address) ? $address : null,
                    'qr_code' => $qrCode,
                    'status' => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (isset($existingNisns[$nisn])) {
                    if ($duplicateAction === 'update') {
                        // For update, we want to respect existing data if new data is empty.
                        // Since bulk upsert overrides entirely, we'll fetch existing record for updating in bulk.
                        // Actually, upsert is meant for complete replacements, but since we are replacing all fields from excel,
                        // it is fine to overwrite with the mapped data.
                        $updateData[] = $data;
                    } else {
                        $skippedCount++;
                    }
                } else {
                    $insertData[] = $data;
                }
            }

            // Bulk Insert for New Records
            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    Student::insertOrIgnore($chunk);
                    $createdCount += count($chunk);
                }
            }

            // Bulk Upsert for Existing Records
            if (!empty($updateData)) {
                foreach (array_chunk($updateData, 500) as $chunk) {
                    Student::upsert(
                        $chunk, 
                        ['nisn'], 
                        ['class_id', 'name', 'nis', 'gender', 'phone', 'parent_phone', 'email', 'address', 'status', 'qr_code', 'updated_at']
                    );
                    $updatedCount += count($chunk);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('students.index')->with('error', 'Terjadi kesalahan saat memproses data import: ' . $e->getMessage());
        }

        $msg = "Import data selesai! Berhasil menambahkan <strong>{$createdCount}</strong> data siswa baru.";
        if ($updatedCount > 0) {
            $msg .= " Diperbarui: <strong>{$updatedCount}</strong> siswa.";
        }
        if ($skippedCount > 0) {
            $msg .= " Dilewati (sudah ada): <strong>{$skippedCount}</strong> siswa.";
        }
        if (!empty($failedRows)) {
            $msg .= " (" . count($failedRows) . " baris tidak valid dilewati).";
        }

        return redirect()->route('students.index')->with('success', $msg);
    }
}
