<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi - Kelas {{ $schoolClass->name }}</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Times New Roman', serif; font-size: 10pt; line-height: 1.4; color: #000; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 8px; margin-bottom: 16px; }
        .header h2, .header h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 5px 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .no-print { text-align: center; margin-bottom: 20px; font-family: sans-serif; }
        .btn-print { background: #2563eb; color: #fff; padding: 8px 18px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; }
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; margin-top: 30px; text-align: center; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan Rekap Presensi PDF</button>
    </div>

    <div class="header">
        <h2>{{ $setting->school_name }}</h2>
        <h3>REKAPITULASI PRESENSI KEHADIRAN SISWA</h3>
        <div>Kelas: {{ $schoolClass->name }} | Bulan: {{ $month }} / {{ $year }} | Semester: {{ $setting->active_semester }} TA {{ $setting->academic_year }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="40">No</th>
                <th width="100">NISN</th>
                <th>Nama Peserta Didik</th>
                <th width="40">JK</th>
                <th width="60">Hadir</th>
                <th width="60">Izin</th>
                <th width="60">Sakit</th>
                <th width="60">Alpa</th>
                <th width="80">% Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $st)
                @php
                    $stAtt = $attendances->where('student_id', $st->id);
                    $h = $stAtt->where('status', 'Hadir')->count();
                    $i = $stAtt->where('status', 'Izin')->count();
                    $s = $stAtt->where('status', 'Sakit')->count();
                    $a = $stAtt->where('status', 'Alpa')->count();
                    $tot = $stAtt->count();
                    $pct = $tot > 0 ? round(($h / $tot) * 100) : 100;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $st->nisn }}</td>
                    <td class="text-left"><strong>{{ $st->name }}</strong></td>
                    <td>{{ $st->gender }}</td>
                    <td>{{ $h }}</td>
                    <td>{{ $i }}</td>
                    <td>{{ $s }}</td>
                    <td>{{ $a }}</td>
                    <td><strong>{{ $pct }}%</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="sig-grid">
        <div>
            Mengetahui,<br>
            Kepala Sekolah<br><br><br><br>
            <strong><u>{{ $setting->principal_name }}</u></strong><br>
            NIP. {{ $setting->principal_nip }}
        </div>
        <div>
            Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
            Guru Wali / Pengampu<br><br><br><br>
            <strong><u>{{ $setting->teacher_name }}</u></strong><br>
            NIP. {{ $setting->teacher_nip }}
        </div>
    </div>
</body>
</html>
