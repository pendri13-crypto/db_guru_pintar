<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger Nilai - {{ $subject->name }} Kelas {{ $schoolClass->name }}</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: 'Times New Roman', serif; font-size: 9.5pt; line-height: 1.35; color: #000; margin: 0; padding: 15px; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 8px; margin-bottom: 14px; }
        .header h2, .header h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 4px 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .no-print { text-align: center; margin-bottom: 16px; font-family: sans-serif; }
        .btn-print { background: #2563eb; color: #fff; padding: 8px 18px; border-radius: 6px; border: none; cursor: pointer; font-weight: bold; }
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; margin-top: 25px; text-align: center; font-size: 10pt; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan Leger Nilai PDF</button>
    </div>

    <div class="header">
        <h2>{{ $setting->school_name }}</h2>
        <h3>LEGER REKAPITULASI NILAI AKADEMIK PESERTA DIDIK</h3>
        <div>Mata Pelajaran: <strong>{{ $subject->name }}</strong> | Kelas: <strong>{{ $schoolClass->name }}</strong> | Semester: {{ $semester }} ({{ $setting->academic_year }})</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="85">NISN</th>
                <th>Nama Peserta Didik</th>
                <th width="30">JK</th>
                <th width="50">Tugas 1 (15%)</th>
                <th width="50">Tugas 2 (15%)</th>
                <th width="50">Formatif (20%)</th>
                <th width="50">Sumatif (20%)</th>
                <th width="50">UTS (15%)</th>
                <th width="50">UAS (15%)</th>
                <th width="55">Nilai Akhir</th>
                <th width="40">Predikat</th>
                <th>Deskripsi Capaian Rapor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $st)
                @php
                    $g = $grades[$st->id] ?? null;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $st->nisn }}</td>
                    <td class="text-left"><strong>{{ $st->name }}</strong></td>
                    <td>{{ $st->gender }}</td>
                    <td>{{ $g->tugas_1 ?? '-' }}</td>
                    <td>{{ $g->tugas_2 ?? '-' }}</td>
                    <td>{{ $g->formatif ?? '-' }}</td>
                    <td>{{ $g->sumatif ?? '-' }}</td>
                    <td>{{ $g->uts ?? '-' }}</td>
                    <td>{{ $g->uas ?? '-' }}</td>
                    <td><strong>{{ $g->final_score ?? '-' }}</strong></td>
                    <td><strong>{{ $g->predicate ?? '-' }}</strong></td>
                    <td class="text-left" style="font-size: 8.5pt;">{{ $g->notes ?? '-' }}</td>
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
            Guru Mata Pelajaran<br><br><br><br>
            <strong><u>{{ $setting->teacher_name }}</u></strong><br>
            NIP. {{ $setting->teacher_nip }}
        </div>
    </div>
</body>
</html>
