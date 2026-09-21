<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Pelajar - {{ $setting->school_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 15px;
            color: #0f172a;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, 85.6mm);
            gap: 8mm;
            justify-content: center;
        }
        .print-card {
            width: 85.6mm;
            height: 53.98mm;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #172554 100%);
            border-radius: 4mm;
            padding: 4mm;
            box-sizing: border-box;
            color: #ffffff;
            position: relative;
            page-break-inside: avoid;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-school {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 2mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .school-title {
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
        .card-type {
            font-size: 6pt;
            color: #f59e0b;
            font-weight: bold;
        }
        .card-main {
            display: flex;
            align-items: center;
            gap: 3mm;
            margin-top: 1mm;
        }
        .photo-ph {
            width: 18mm;
            height: 24mm;
            background: #334155;
            border: 1px solid #60a5fa;
            border-radius: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 7pt;
            font-weight: bold;
            color: #cbd5e1;
        }
        .info-col {
            flex: 1;
            font-size: 7pt;
            line-height: 1.3;
        }
        .stu-name {
            font-size: 8.5pt;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 38mm;
        }
        .stu-nisn {
            font-weight: bold;
            color: #60a5fa;
        }
        .qr-section {
            width: 18mm;
            height: 18mm;
            background: #ffffff;
            padding: 1mm;
            border-radius: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-footer-bar {
            font-size: 5.5pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            border-top: 1px dashed rgba(255,255,255,0.15);
            padding-top: 1mm;
        }
        @media print {
            .no-print { display: none; }
            body { background: #fff; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="print-grid">
        @foreach($students as $stu)
            <div class="print-card">
                <div class="card-school">
                    <div class="school-title">{{ $setting->school_name ?? 'SMP NEGERI UNGGULAN' }}</div>
                    <div class="card-type">KARTU PELAJAR RESMI</div>
                </div>

                <div class="card-main">
                    <div class="photo-ph">
                        FOTO 2x3
                    </div>
                    <div class="info-col">
                        <div class="stu-name">{{ $stu->name }}</div>
                        <div>NISN: <span class="stu-nisn">{{ $stu->nisn }}</span></div>
                        <div>Kelas: <strong>{{ $stu->schoolClass->name }}</strong></div>
                        <div>Jenis Kelamin: {{ $stu->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        <div>Tahun Ajaran: 2025/2026</div>
                    </div>
                    <div class="qr-section">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode($stu->qr_code) }}" alt="QR" width="55" height="55">
                    </div>
                </div>

                <div class="card-footer-bar">
                    <span>Berlaku selama menjadi peserta didik aktif</span>
                    <span>Smart Attendance QR</span>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
