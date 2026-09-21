@extends('layouts.app')

@section('title', 'Generator Modul Ajar Deep Learning AI')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px;">Generator Modul Ajar Deep Learning AI</h2>
            <p style="font-size: 13px; color: var(--text-dim);">Susun RPP Kurikulum Merdeka lengkap dengan tabel diagnostik, skenario per pertemuan, rubrik asesmen, dan LKPD</p>
        </div>
        <a href="{{ route('modules-ai.index') }}" class="btn btn-outline">Kembali ke Modul</a>
    </div>

    <!-- AI Feature Form Card -->
    <div class="card" style="border: 1px solid rgba(139, 92, 246, 0.4); box-shadow: var(--shadow-glow-purple);">
        <div style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(58, 134, 255, 0.1)); padding: 14px 18px; border-radius: 12px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: #8b5cf6; display: flex; align-items: center; justify-content: center; color: #fff;">
                ✨
            </div>
            <div>
                <div style="font-size: 13px; font-weight: 800; color: #fff;">Deep Learning Engine (Mindful, Meaningful, Joyful Learning)</div>
                <div style="font-size: 11px; color: var(--text-muted);">Menghasilkan dokumen RPP 100% komprehensif siap cetak sesuai standar Kemendikbudristek.</div>
            </div>
        </div>

        <form action="{{ route('modules-ai.generate') }}" method="POST" id="moduleGenForm">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Topik / Materi Pokok Pembelajaran *</label>
                <input type="text" name="topic" class="form-control" placeholder="Contoh: Berpikir Komputasional dan Pemrograman Python Dasar" required value="Algoritma & Pemrograman Python Tingkat Menengah">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Mata Pelajaran *</label>
                    <select name="subject_id" class="form-select" required>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fase *</label>
                    <select name="phase" class="form-select" required>
                        <option value="Fase D (SMP / MTs)">Fase D (SMP / MTs)</option>
                        <option value="Fase E (SMA / SMK Kelas 10)">Fase E (SMA / SMK Kelas 10)</option>
                        <option value="Fase F (SMA / SMK Kelas 11-12)">Fase F (SMA / SMK Kelas 11-12)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat Kelas *</label>
                    <select name="grade_level" class="form-select" required>
                        <option value="8">Kelas 8</option>
                        <option value="7">Kelas 7</option>
                        <option value="9">Kelas 9</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Jumlah Pertemuan (1 - 5 Pertemuan) *</label>
                    <select name="total_meetings" class="form-select" required>
                        <option value="3">3 Pertemuan (Skenario Terstruktur)</option>
                        <option value="5">5 Pertemuan Lengkap (Mastery Learning)</option>
                        <option value="1">1 Pertemuan (Micro Teaching)</option>
                        <option value="2">2 Pertemuan</option>
                        <option value="4">4 Pertemuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Model Pembelajaran Pendukung</label>
                    <select name="learning_model" class="form-select">
                        <option value="Problem Based Learning (PBL)">Problem Based Learning (PBL)</option>
                        <option value="Project Based Learning (PjBL)">Project Based Learning (PjBL)</option>
                        <option value="Discovery & Inquiry Learning">Discovery & Inquiry Learning</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Dimensi Profil Pelajar Pancasila</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 8px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Bernalar Kritis" checked> Bernalar Kritis
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Kreatif" checked> Kreatif
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Gotong Royong" checked> Gotong Royong
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Mandiri" checked> Mandiri
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Beriman & Bertakwa"> Beriman & Bertakwa
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); cursor: pointer;">
                        <input type="checkbox" name="pancasila_profiles[]" value="Berkebinekaan Global"> Berkebinekaan Global
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 18px; border-top: 1px solid var(--border-color);">
                <a href="{{ route('modules-ai.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-yellow" style="padding: 12px 28px; font-size: 14px;" id="btnSubmitGen">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    </svg>
                    Generate Modul Ajar AI
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('moduleGenForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitGen');
        btn.disabled = true;
        btn.innerHTML = `<span>⏳ Menyusun Modul Deep Learning AI...</span>`;
    });
</script>
@endpush
@endsection
