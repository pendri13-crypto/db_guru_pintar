<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Modul Ajar Deep Learning - {{ $module->title }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            line-height: 1.5;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .header h1 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 0 0 4px;
        }
        .header h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 0;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 16px;
            margin-bottom: 6px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 12px;
            font-size: 10pt;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
            font-family: sans-serif;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan Modul PDF</button>
    </div>

    <div class="header">
        <h1>{{ $setting->school_name }}</h1>
        <h2>MODUL AJAR KURIKULUM MERDEKA (DEEP LEARNING PRO)</h2>
        <div style="font-size: 10pt; margin-top: 4px;">{{ $setting->address }} | NPSN: {{ $setting->npsn }}</div>
    </div>

    <div class="section-title">I. INFORMASI UMUM</div>
    <table>
        <tr>
            <td width="30%"><strong>Mata Pelajaran</strong></td>
            <td>{{ $module->subject->name }}</td>
        </tr>
        <tr>
            <td><strong>Fase / Kelas / Semester</strong></td>
            <td>{{ $module->phase }} / Kelas {{ $module->grade_level }} / Ganjil (2025/2026)</td>
        </tr>
        <tr>
            <td><strong>Topik Pembelajaran</strong></td>
            <td>{{ $module->topic }}</td>
        </tr>
        <tr>
            <td><strong>Alokasi Pertemuan</strong></td>
            <td>{{ $module->total_meetings }} Pertemuan ({{ $module->total_meetings * 2 }} x 40 Menit)</td>
        </tr>
        <tr>
            <td><strong>Profil Pelajar Pancasila</strong></td>
            <td>{{ $module->pancasila_profile }}</td>
        </tr>
        <tr>
            <td><strong>Pendekatan Pedagogi</strong></td>
            <td>Deep Learning (Mindful Learning, Meaningful Learning, Joyful Learning)</td>
        </tr>
    </table>

    <div class="section-title">II. KOMPONEN INTI & TUJUAN PEMBELAJARAN</div>
    <p><strong>A. Tujuan Pembelajaran (TP):</strong></p>
    <p style="white-space: pre-line;">{{ $module->learning_goals }}</p>

    <p><strong>B. Pemetaan Asesmen Awal (Diagnostik) & Pembelajaran Berdiferensiasi:</strong></p>
    <table>
        <thead>
            <tr>
                <th width="25%">Kategori Kesiapan</th>
                <th width="35%">Ciri / Karakteristik Pemahaman</th>
                <th width="40%">Tindak Lanjut Diferensiasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($diagnosticTable as $diag)
                <tr>
                    <td><strong>{{ $diag['kategori'] }}</strong></td>
                    <td>{{ $diag['ciri'] }}</td>
                    <td>{{ $diag['tindak_lanjut'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. SKENARIO KEGIATAN PEMBELAJARAN</div>
    @foreach($scenarios as $sc)
        <div style="margin-bottom: 14px; page-break-inside: avoid;">
            <p><strong>Pertemuan {{ $sc['pertemuan'] }}: {{ $sc['topik'] }} ({{ $sc['durasi'] }})</strong></p>
            <ul style="margin-top: 4px; padding-left: 20px;">
                <li><strong>Pendahuluan (Mindful):</strong> {{ $sc['pendahuluan'] }}</li>
                <li><strong>Kegiatan Inti (Meaningful & Joyful):</strong> {{ $sc['kegiatan_inti'] }}</li>
                <li><strong>Penutup (Reflection):</strong> {{ $sc['penutup'] }}</li>
            </ul>
        </div>
    @endforeach

    <div class="section-title">IV. RUBRIK ASESMEN & LEMBAR KERJA (LKPD)</div>
    <table>
        <thead>
            <tr>
                <th>Aspek Penilaian</th>
                <th>Skor 4 (Sangat Mahir)</th>
                <th>Skor 3 (Mahir)</th>
                <th>Skor 2 (Cukup)</th>
                <th>Skor 1 (Perlu Bimbingan)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessmentRubric as $rub)
                <tr>
                    <td><strong>{{ $rub['aspek'] }}</strong></td>
                    <td>{{ $rub['skor_4'] }}</td>
                    <td>{{ $rub['skor_3'] }}</td>
                    <td>{{ $rub['skor_2'] }}</td>
                    <td>{{ $rub['skor_1'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-grid">
        <div class="sig-box">
            Mengetahui,<br>
            Kepala Sekolah<br><br><br><br>
            <strong><u>{{ $setting->principal_name }}</u></strong><br>
            NIP. {{ $setting->principal_nip }}
        </div>
        <div class="sig-box">
            Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
            Guru Mata Pelajaran<br><br><br><br>
            <strong><u>{{ $setting->teacher_name }}</u></strong><br>
            NIP. {{ $setting->teacher_nip }}
        </div>
    </div>
</body>
</html>
