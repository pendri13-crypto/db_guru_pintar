@extends('layouts.app')

@section('title', 'Cetak Kartu Pelajar & QR Code')

@push('styles')
<style>
    .card-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .id-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #172554 100%);
        border: 1px solid rgba(58, 134, 255, 0.4);
        border-radius: 16px;
        padding: 20px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(58, 134, 255, 0.15);
    }

    .id-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .card-header-school {
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        padding-bottom: 12px;
        margin-bottom: 14px;
    }

    .school-logo-badge {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #3a86ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
    }

    .school-name-text {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #ffffff;
        line-height: 1.2;
    }

    .card-tag {
        font-size: 8px;
        color: #f59e0b;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .card-body-content {
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .student-photo-box {
        width: 72px;
        height: 90px;
        background: #1e293b;
        border: 2px solid rgba(58, 134, 255, 0.5);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #64748b;
    }

    .student-details {
        flex: 1;
        overflow: hidden;
    }

    .student-card-name {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-card-nisn {
        font-size: 11px;
        color: #60a5fa;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .student-card-class {
        font-size: 11px;
        color: var(--text-dim);
    }

    .card-qr-box {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .qr-visual {
        background: #ffffff;
        padding: 6px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Cetak Kartu & QR Code Siswa</h2>
        <p style="font-size: 13px; color: #475569;">Kartu pelajar resmi dengan QR Code presensi terintegrasi</p>
    </div>
    @if($selectedClassId)
        <a href="{{ route('cards.print', ['class_id' => $selectedClassId]) }}" target="_blank" class="btn btn-yellow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
            </svg>
            Cetak Semua Kartu Kelas Ini
        </a>
    @endif
</div>

<!-- Class Selector -->
<div class="card" style="margin-bottom: 24px; padding: 16px 20px;">
    <form method="GET" action="{{ route('cards.index') }}" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
        <label class="form-label" style="margin-bottom: 0; white-space: nowrap; font-weight: 700; color: var(--text-main);">Pilih Kelas:</label>
        <select name="class_id" class="form-select" onchange="this.form.submit()" style="max-width: 320px;">
            @foreach($classes->where('students_count', '>', 0) as $cls)
                <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                    Kelas {{ $cls->name }} ({{ $cls->students_count }} Siswa)
                </option>
            @endforeach
            @if($classes->where('students_count', '==', 0)->count() > 0)
                <optgroup label="Kelas Lainnya (Belum Ada Siswa)">
                    @foreach($classes->where('students_count', '==', 0) as $cls)
                        <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                            Kelas {{ $cls->name }} (0 Siswa)
                        </option>
                    @endforeach
                </optgroup>
            @endif
        </select>
        <span style="font-size: 13px; color: #94a3b8;">
            Total <strong style="color: #60a5fa;">{{ count($students) }}</strong> kartu siswa siap dicetak
        </span>
    </form>
</div>

<!-- Cards Grid Preview -->
<div class="card-preview-grid">
    @forelse($students as $stu)
        <div class="id-card">
            <div class="card-header-school">
                <div class="school-logo-badge">GP</div>
                <div>
                    <div class="school-name-text">{{ $setting->school_name ?? 'SMP NEGERI UNGGULAN INDONESIA' }}</div>
                    <div class="card-tag">KARTU TANDA PELAJAR RESMI</div>
                </div>
            </div>

            <div class="card-body-content">
                <div class="student-photo-box">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span style="font-size: 9px; margin-top: 4px; font-weight: 600;">{{ $stu->gender == 'L' ? 'L' : 'P' }}</span>
                </div>

                <div class="student-details">
                    <div class="student-card-name">{{ $stu->name }}</div>
                    <div class="student-card-nisn">NISN: {{ $stu->nisn }}</div>
                    <div class="student-card-class">Kelas: {{ $stu->schoolClass->name }} | TA: 2025/2026</div>
                    <div style="font-size: 10px; color: var(--text-dim); margin-top: 4px;">Status: Aktif</div>
                </div>
            </div>

            <div class="card-qr-box">
                <div>
                    <div style="font-size: 9px; font-weight: 700; color: #a78bfa;">SMART ATTENDANCE QR</div>
                    <div style="font-size: 8px; color: var(--text-dim);">Scan via Kamera Guru Pintar</div>
                </div>
                <div class="qr-visual">
                    <!-- SVG QR Representation -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=65x65&data={{ urlencode($stu->qr_code) }}" alt="QR Code" width="55" height="55" style="display: block;">
                </div>
            </div>
            
            <div style="margin-top: 14px; text-align: right;">
                <a href="{{ route('cards.print', ['student_id' => $stu->id]) }}" target="_blank" class="btn btn-sm btn-outline" style="font-size: 11px;">
                    Cetak Satuan
                </a>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-dim);">
            Pilih kelas untuk menampilkan kartu pelajar siswa.
        </div>
    @endforelse
</div>
@endsection
