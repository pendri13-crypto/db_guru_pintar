@extends('layouts.app')

@section('title', 'Dashboard Guru Pintar')

@push('styles')
<style>
    /* Top 4 Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--shadow-card);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(58, 134, 255, 0.35);
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-blue { background: rgba(58, 134, 255, 0.15); color: #3a86ff; }
    .icon-green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .icon-purple { background: rgba(139, 92, 246, 0.15); color: #a78bfa; }
    .icon-amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: #0369a1;
        letter-spacing: -0.5px;
    }

    /* AI Prominent Banner */
    .ai-banner {
        background: linear-gradient(135deg, #381273 0%, #4c1d95 40%, #312e81 100%);
        border: 1px solid rgba(139, 92, 246, 0.4);
        border-radius: var(--radius-xl);
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-glow-purple);
        position: relative;
        overflow: hidden;
    }

    .ai-banner::before {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, rgba(168, 85, 247, 0) 70%);
        pointer-events: none;
    }

    .ai-banner-content {
        position: relative;
        z-index: 2;
    }

    .ai-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f59e0b;
        color: #1e1b4b;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
    }

    .ai-banner h2 {
        font-size: 22px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .ai-banner p {
        font-size: 13.5px;
        color: #e2e8f0;
        max-width: 820px;
        line-height: 1.5;
    }

    .btn-ai-action {
        background: #f59e0b;
        color: #0f172a;
        font-weight: 700;
        font-size: 13.5px;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        flex-shrink: 0;
        position: relative;
        z-index: 2;
        transition: all 0.2s ease;
    }

    .btn-ai-action:hover {
        background: #fbbf24;
        transform: translateY(-2px);
    }

    /* Quick Access Module Cards (12 Grid) */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
    }

    .section-title svg {
        color: var(--primary);
        width: 20px;
        height: 20px;
    }

    .section-badge {
        font-size: 12px;
        color: var(--text-dim);
        font-weight: 600;
    }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 32px;
    }

    .quick-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 180px;
        box-shadow: var(--shadow-card);
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .quick-card:hover {
        background-color: var(--bg-card-hover);
        border-color: rgba(58, 134, 255, 0.4);
        transform: translateY(-3px);
    }

    .quick-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .quick-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-pill {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        background: rgba(255, 255, 255, 0.07);
        color: var(--text-muted);
    }

    .quick-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 6px;
    }

    .quick-desc {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.45;
        margin-bottom: 14px;
    }

    .quick-link {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .quick-card:hover .quick-link {
        color: #60a5fa;
        gap: 8px;
        transition: gap 0.2s ease;
    }

    /* Analytics & Schedule Widgets */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .widget-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .widget-title h3 {
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
    }

    .widget-subtitle {
        font-size: 12px;
        color: var(--text-dim);
        margin-bottom: 18px;
    }

    .chart-container {
        height: 180px;
        position: relative;
    }

    /* Score Progress Widget */
    .score-item {
        margin-top: 14px;
    }

    .score-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .score-subject {
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.5px;
    }

    .score-num {
        font-size: 13.5px;
        font-weight: 700;
        color: #ffffff;
    }

    .progress-bar-bg {
        width: 100%;
        height: 10px;
        background-color: #1e293b;
        border-radius: 6px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        border-radius: 6px;
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.5);
    }

    /* Schedules Bottom Card */
    .schedule-card-wrapper {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px;
        box-shadow: var(--shadow-card);
    }

    .schedule-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .schedule-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
    }

    .schedule-title svg {
        color: var(--primary);
        width: 18px;
        height: 18px;
    }

    .schedule-link-all {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }

    .schedule-link-all:hover {
        text-decoration: underline;
    }

    .schedule-items-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .schedule-pill-box {
        background-color: #0e172e;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 16px 18px;
        position: relative;
        transition: border-color 0.2s ease;
    }

    .schedule-pill-box:hover {
        border-color: rgba(58, 134, 255, 0.4);
    }

    .schedule-day-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .schedule-day {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
    }

    .schedule-time-badge {
        font-size: 11px;
        font-weight: 600;
        background-color: rgba(58, 134, 255, 0.15);
        color: #60a5fa;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .schedule-subject-name {
        font-size: 15px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }

    .schedule-class-name {
        font-size: 12px;
        color: var(--text-dim);
    }

    @media (max-width: 1200px) {
        .stats-grid, .quick-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .schedule-items-grid {
            grid-template-columns: 1fr;
        }
        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .stats-grid, .quick-grid {
            grid-template-columns: 1fr;
        }
        .ai-banner {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<!-- Dashboard Welcome Bar & Quick Actions -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h2 style="font-size: 21px; font-weight: 800; color: #fff; margin-bottom: 4px;">Selamat Datang, {{ $setting->teacher_name ?? 'Budi Santoso, S.Kom' }} 👋</h2>
        <p style="font-size: 13px; color: var(--text-dim);">Panel Administrasi Guru & Kurikulum Merdeka Terintegrasi AI</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        @if($totalStudentsAll > 0)
        <button type="button" onclick="openDeleteAllModal()" class="btn btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.35); background: rgba(244, 63, 94, 0.08); font-size: 13px; padding: 8px 16px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            Hapus Semua Siswa
        </button>
        @endif
        <a href="{{ route('students.index') }}" class="btn btn-primary" style="font-size: 13px; padding: 8px 16px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Kelola Siswa
        </a>
    </div>
</div>

<!-- 1. Top 4 Stats Cards -->
<div class="stats-grid">
    <!-- Stat 1: Total Siswa -->
    <div class="stat-card" style="position: relative;">
        <div class="stat-icon-wrapper icon-blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="stat-info" style="flex: 1;">
            <span class="stat-label">Total Siswa</span>
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                <span class="stat-value">{{ $totalStudents }}</span>
                @if($totalStudentsAll > 0)
                <button type="button" onclick="openDeleteAllModal()" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.35); background: rgba(244, 63, 94, 0.08); font-size: 11px; padding: 3px 8px; gap: 4px;" title="Hapus Semua Data Siswa">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Hapus
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Kelas -->
    <div class="stat-card">
        <div class="stat-icon-wrapper icon-green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4M5 21V10.85a3 3 0 0 0 4 0V21m6 0v-10.15a3 3 0 0 0 4 0V21"/>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Kelas</span>
            <span class="stat-value">{{ $totalClasses }}</span>
        </div>
    </div>

    <!-- Stat 3: Mata Pelajaran -->
    <div class="stat-card">
        <div class="stat-icon-wrapper icon-purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Mata Pelajaran</span>
            <span class="stat-value">{{ $totalSubjects }}</span>
        </div>
    </div>

    <!-- Stat 4: Hadir Hari Ini -->
    <div class="stat-card">
        <div class="stat-icon-wrapper icon-amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>
            </svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Hadir Hari Ini</span>
            <span class="stat-value">{{ $attendanceTodayPercent }}%</span>
        </div>
    </div>
</div>

<!-- 2. AI Featured Prominent Banner -->
<div class="ai-banner">
    <div class="ai-banner-content">
        <div class="ai-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
            </svg>
            <span>DEEP LEARNING AI PRO</span>
        </div>
        <h2>Buat Modul Ajar Deep Learning Otomatis</h2>
        <p>Susun modul pembelajaran Kurikulum Merdeka lengkap dengan skenario kegiatan per pertemuan (hingga 5 pertemuan), tabel diagnostik, rubrik asesmen, dan LKPD interaktif siap cetak.</p>
    </div>
    <a href="{{ route('modules-ai.create') }}" class="btn-ai-action">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
        </svg>
        <span>Buka Modul Ajar AI</span>
    </a>
</div>

<!-- 3. Akses Cepat Modul & Menu Administrasi Guru (12 Menu Lengkap) -->
<div class="section-header">
    <div class="section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
        </svg>
        <span>Akses Cepat Modul & Menu Administrasi Guru</span>
    </div>
    <span class="section-badge">12 Menu Lengkap</span>
</div>

<div class="quick-grid">
    <!-- Card 1: Kelola Master Siswa -->
    <div class="quick-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>
                <span class="quick-pill">MASTER DATA</span>
            </div>
            <div class="quick-title">Kelola Master Siswa</div>
            <div class="quick-desc">Olah data seluruh siswa, NISN, import Excel, edit & hapus data.</div>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px; pt-2; border-top: 1px solid rgba(255,255,255,0.05);">
            <a href="{{ route('students.index') }}" class="quick-link" style="text-decoration: none;">Buka Fitur →</a>
            @if($totalStudentsAll > 0)
            <button type="button" onclick="openDeleteAllModal()" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.35); background: rgba(244, 63, 94, 0.08); font-size: 11px; padding: 3px 8px; gap: 4px;" title="Hapus Semua Data Siswa">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Hapus
            </button>
            @endif
        </div>
    </div>

    <!-- Card 2: Cetak Kartu & QR Code -->
    <a href="{{ route('cards.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="3" rx="2"/><rect width="5" height="5" x="7" y="7"/><rect width="5" height="5" x="7" y="13"/><rect width="5" height="5" x="13" y="7"/>
                    </svg>
                </div>
                <span class="quick-pill">KARTU PELAJAR</span>
            </div>
            <div class="quick-title">Cetak Kartu & QR Code</div>
            <div class="quick-desc">Cetak kartu pelajar resmi dengan QR Code presensi terintegrasi.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 3: Kelola Mata Pelajaran -->
    <a href="{{ route('subjects.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <span class="quick-pill">KURIKULUM</span>
            </div>
            <div class="quick-title">Kelola Mata Pelajaran</div>
            <div class="quick-desc">Atur daftar mapel, semester, dan alokasi jam mengajar harian.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 4: Jadwal Mengajar -->
    <a href="{{ route('schedules.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                </div>
                <span class="quick-pill">JADWAL</span>
            </div>
            <div class="quick-title">Jadwal Mengajar</div>
            <div class="quick-desc">Kelola jadwal tatap muka kelas, jam pelajaran, dan ruang kelas.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 5: Scan & Input Absensi -->
    <a href="{{ route('attendance.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>
                    </svg>
                </div>
                <span class="quick-pill">PRESENSI</span>
            </div>
            <div class="quick-title">Scan & Input Absensi</div>
            <div class="quick-desc">Pencatatan presensi harian manual & scan QR Code kamera otomatis.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 6: Input Penilaian & Leger -->
    <a href="{{ route('grades.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <span class="quick-pill">NILAI & RAPOR</span>
            </div>
            <div class="quick-title">Input Penilaian & Leger</div>
            <div class="quick-desc">Rekap nilai harian, UTS, UAS, kalkulasi otomatis & leger siswa.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 7: Agenda Mengajar Guru -->
    <a href="{{ route('journals.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/>
                    </svg>
                </div>
                <span class="quick-pill">JURNAL KBM</span>
            </div>
            <div class="quick-title">Agenda Mengajar Guru</div>
            <div class="quick-desc">Jurnal harian KBM, keterlaksanaan materi & catatan kehadiran.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 8: Bimbingan Guru Wali -->
    <a href="{{ route('guidance.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon" style="background: rgba(244, 63, 94, 0.15); color: #f43f5e;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <span class="quick-pill">GURU WALI</span>
            </div>
            <div class="quick-title">Bimbingan Guru Wali</div>
            <div class="quick-desc">Pencatatan konseling, apresiasi siswa & tindak lanjut orang tua.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 9: Modul Ajar Deep Learning AI -->
    <a href="{{ route('modules-ai.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    </svg>
                </div>
                <span class="quick-pill">FITUR UNGGULAN AI</span>
            </div>
            <div class="quick-title">Modul Ajar Deep Learning AI</div>
            <div class="quick-desc">Generator RPP Deep Learning Kurikulum Merdeka (hingga 5 pertemuan).</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 10: Asisten Chatbot Guru AI -->
    <a href="{{ route('chatbot.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-purple">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>
                    </svg>
                </div>
                <span class="quick-pill">ASISTEN AI</span>
            </div>
            <div class="quick-title">Asisten Chatbot Guru AI</div>
            <div class="quick-desc">Konsultan pedagogi AI, pembuat soal HOTS, & draf narasi rapor.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 11: Pusat Laporan PDF -->
    <a href="{{ route('reports.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/>
                    </svg>
                </div>
                <span class="quick-pill">CETAK DOKUMEN</span>
            </div>
            <div class="quick-title">Pusat Laporan PDF</div>
            <div class="quick-desc">Cetak rekapitulasi presensi, leger nilai & jurnal resmi ke PDF.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>

    <!-- Card 12: Pengaturan & Profil -->
    <a href="{{ route('settings.index') }}" class="quick-card">
        <div>
            <div class="quick-card-top">
                <div class="quick-card-icon icon-blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </div>
                <span class="quick-pill">PROFIL & SEKOLAH</span>
            </div>
            <div class="quick-title">Pengaturan & Profil</div>
            <div class="quick-desc">Kelola profil guru, NIP, instansi sekolah, & gambar tanda tangan.</div>
        </div>
        <div class="quick-link">Buka Fitur →</div>
    </a>
</div>

<!-- 4. Analytics & Schedule Widgets -->
<div class="bottom-grid">
    <!-- Tren Kehadiran (7 Hari Terakhir) -->
    <div class="card">
        <div class="widget-title">
            <h3>Tren Kehadiran (7 Hari Terakhir)</h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3a86ff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
            </svg>
        </div>
        <div class="widget-subtitle">Persentase kehadiran siswa harian</div>
        <div class="chart-container">
            <canvas id="attendanceTrendChart"></canvas>
        </div>
    </div>

    <!-- Rata-rata Nilai per Mata Pelajaran -->
    <div class="card">
        <div class="widget-title">
            <h3>Rata-rata Nilai per Mata Pelajaran</h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
        </div>
        <div class="widget-subtitle">Nilai akumulasi akademik kelas</div>
        
        <div class="score-item">
            <div class="score-header">
                <span class="score-subject">INFORMATIKA</span>
                <span class="score-num">{{ $avgScore }} / 100</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $avgScore }}%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- 5. Jadwal Mengajar Terdaftar -->
<div class="schedule-card-wrapper">
    <div class="schedule-header">
        <div class="schedule-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
            </svg>
            <span>Jadwal Mengajar Terdaftar</span>
        </div>
        <a href="{{ route('schedules.index') }}" class="schedule-link-all">Lihat Semua Jadwal →</a>
    </div>

    <div class="schedule-items-grid">
        @forelse($schedules as $sch)
            <div class="schedule-pill-box">
                <div class="schedule-day-row">
                    <span class="schedule-day">{{ $sch->day }}</span>
                    <span class="schedule-time-badge">{{ $sch->start_time }} - {{ $sch->end_time }}</span>
                </div>
                <div class="schedule-subject-name">{{ $sch->subject->name }}</div>
                <div class="schedule-class-name">Kelas: {{ $sch->schoolClass->name }} ({{ $sch->room }})</div>
            </div>
        @empty
            <div class="schedule-pill-box">
                <div class="schedule-day-row">
                    <span class="schedule-day">Senin</span>
                    <span class="schedule-time-badge">08:30 - 09:50</span>
                </div>
                <div class="schedule-subject-name">INFORMATIKA</div>
                <div class="schedule-class-name">Kelas: 8C</div>
            </div>
        @endforelse
    </div>
</div>
<!-- Modal Hapus Semua Data Siswa -->
<div id="deleteAllModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(4, 8, 20, 0.82); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; animation: fadeIn 0.2s ease;">
    <div style="background: #121d38; border: 1px solid rgba(244, 63, 94, 0.35); border-radius: 18px; max-width: 520px; width: 100%; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6), 0 0 30px rgba(244, 63, 94, 0.2); overflow: hidden; position: relative;">
        <!-- Modal Header -->
        <div style="padding: 24px 26px 18px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.3); display: flex; align-items: center; justify-content: center; color: #fb7185; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 4px;">Hapus Semua Data Siswa</h3>
                <p style="font-size: 12.5px; color: var(--text-dim); line-height: 1.4;">Tindakan ini permanen dan akan menghapus data siswa dari database sekolah.</p>
            </div>
            <button type="button" onclick="closeDeleteAllModal()" style="background: transparent; border: none; color: var(--text-dim); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 6px;" title="Tutup">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body & Form -->
        <form method="POST" action="{{ route('students.destroyAll') }}" id="formDeleteAll" style="padding: 22px 26px 26px;">
            @csrf
            @method('DELETE')

            <!-- Warning Box -->
            <div style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.25); border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12.5px; font-weight: 700; color: #fb7185; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Dampak Penghapusan:
                </div>
                <ul style="font-size: 12px; color: #fda4af; padding-left: 18px; margin: 0; line-height: 1.6;">
                    <li>Seluruh data biodata siswa yang dipilih akan dihapus.</li>
                    <li>Riwayat presensi harian & scan QR siswa akan ikut terhapus.</li>
                    <li>Rekap nilai tugas, formatif, sumatif & nilai rapor siswa akan ikut terhapus.</li>
                    <li>Catatan bimbingan konseling wali kelas akan ikut terhapus.</li>
                </ul>
            </div>

            <!-- Scope Choice (All Students vs Specific Class) -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="color: #cbd5e1; font-weight: 700;">Pilih Cakupan Penghapusan:</label>
                <select name="class_id" id="deleteScopeSelect" class="form-select" style="background: #0b1328; border-color: rgba(244, 63, 94, 0.3); color: #fff;">
                    <option value="all">⚠️ HAPUS SELURUH SISWA (Semua Kelas - Total {{ $totalStudentsAll }} Siswa)</option>
                    <optgroup label="Hapus per Kelas Tertentu:">
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">Hanya Kelas {{ $cls->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Confirmation Text Input -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label" style="font-size: 12px; color: var(--text-dim);">
                    Ketik kata <strong style="color: #fb7185; letter-spacing: 0.5px;">HAPUS</strong> untuk mengaktifkan tombol konfirmasi:
                </label>
                <input type="text" id="confirmDeleteInput" placeholder="Ketik HAPUS di sini..." class="form-control" style="background: #0b1328; border-color: var(--border-color); text-transform: uppercase; font-weight: 700; letter-spacing: 1px;" autocomplete="off">
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
                <button type="button" onclick="closeDeleteAllModal()" class="btn btn-outline" style="padding: 10px 20px;">
                    Batal
                </button>
                <button type="submit" id="btnSubmitDeleteAll" class="btn" style="background: #e11d48; color: #fff; padding: 10px 22px; font-weight: 700; box-shadow: 0 4px 15px rgba(225, 29, 72, 0.35); opacity: 0.5; cursor: not-allowed;" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Ya, Hapus Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openDeleteAllModal() {
        const modal = document.getElementById('deleteAllModal');
        modal.style.display = 'flex';
        document.getElementById('confirmDeleteInput').value = '';
        const submitBtn = document.getElementById('btnSubmitDeleteAll');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
        setTimeout(() => {
            document.getElementById('confirmDeleteInput').focus();
        }, 50);
    }

    function closeDeleteAllModal() {
        const modal = document.getElementById('deleteAllModal');
        modal.style.display = 'none';
    }

    document.getElementById('confirmDeleteInput').addEventListener('input', function(e) {
        const submitBtn = document.getElementById('btnSubmitDeleteAll');
        if (e.target.value.trim().toUpperCase() === 'HAPUS') {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        }
    });

    // Close modal on click outside
    document.getElementById('deleteAllModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteAllModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteAllModal();
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
        const labels = @json($chartDates);
        const dataValues = @json($chartRates);

        // Chart.js Bar configuration matching dark modern aesthetics
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kehadiran (%)',
                    data: dataValues,
                    backgroundColor: dataValues.map(val => val > 0 ? '#3a86ff' : '#1e2c4f'),
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#121d38',
                        titleColor: '#fff',
                        bodyColor: '#3a86ff',
                        borderColor: '#1e2c4f',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% Hadir';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#8e9bb4',
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' }
                        },
                        border: { display: false }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 25,
                            callback: function(val) { return val + '%'; },
                            color: '#5c6b8c',
                            font: { family: 'Plus Jakarta Sans', size: 10 }
                        },
                        grid: {
                            color: 'rgba(30, 44, 79, 0.5)',
                            drawBorder: false
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
