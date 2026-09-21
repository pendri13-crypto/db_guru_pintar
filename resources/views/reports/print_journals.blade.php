<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jurnal Harian Mengajar KBM - {{ $setting->school_name }}</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Times New Roman', serif; font-size: 10pt; line-height: 1.4; color: #000; margin: 0; padding: 15px; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 8px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9pt; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 5px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
        .no-print { text-align: center; margin-bottom: 16px; font-family: sans-serif; }
        .btn-print { background: #2563eb; color: #fff; padding: 8px 18px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; }
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; margin-top: 30px; text-align: center; font-size: 10pt; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan Jurnal KBM PDF</button>
    </div>

    <div class="header">
        <h2>{{ $setting->school_name }}</h2>
        <h3>BUKU JURNAL AGENDA HARIAN MENGAJAR (KBM)</h3>
        <div>Tahun Pelajaran: {{ $setting->academic_year }} | Semester: {{ $setting->active_semester }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="85">Hari / Tanggal</th>
                <th width="50">Kelas</th>
                <th width="80">Mapel</th>
                <th width="40">Ke-</th>
                <th>Materi Pokok & Tujuan Pembelajaran</th>
                <th>Ringkasan Kegiatan KBM</th>
                <th width="65">Presensi</th>
                <th>Catatan / Solusi Kendala</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $idx => $j)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($j->date)->isoFormat('dddd, D/MM/Y') }}</td>
                    <td style="text-align: center;"><strong>{{ $j->schoolClass->name }}</strong></td>
                    <td>{{ $j->subject->name }}</td>
                    <td style="text-align: center;">{{ $j->meeting_number }}</td>
                    <td>
                        <strong>{{ $j->topic }}</strong><br>
                        <span style="font-size: 8.5pt;">{{ $j->learning_objective }}</span>
                    </td>
                    <td style="font-size: 8.5pt;">{{ $j->activities }}</td>
                    <td style="text-align: center;">
                        Hadir: {{ $j->total_present }}<br>
                        Absen: {{ $j->total_absent }}
                    </td>
                    <td style="font-size: 8.5pt;">{{ $j->obstacle_solution ?? '-' }}</td>
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
            Guru Pengampu<br><br><br><br>
            <strong><u>{{ $setting->teacher_name }}</u></strong><br>
            NIP. {{ $setting->teacher_nip }}
        </div>
    </div>
</body>
</html>
