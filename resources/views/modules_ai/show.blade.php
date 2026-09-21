@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
            <span style="background: rgba(139, 92, 246, 0.15); color: #c084fc; font-weight: 800; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                {{ $module->phase }}
            </span>
            <span style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                {{ $module->total_meetings }} Pertemuan
            </span>
        </div>
        <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px;">{{ $module->title }}</h2>
        <p style="font-size: 13px; color: var(--text-dim);">Mata Pelajaran: {{ $module->subject->name }} | Disusun untuk Kelas {{ $module->grade_level }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('modules-ai.index') }}" class="btn btn-outline">Kembali</a>
        <a href="{{ route('modules-ai.print', $module->id) }}" target="_blank" class="btn btn-yellow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
            </svg>
            Cetak / Download PDF Resmi
        </a>
    </div>
</div>

<!-- Section 1: Identitas & Tujuan Pembelajaran -->
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 16px; font-weight: 800; color: #38bdf8; margin-bottom: 14px;">1. Informasi Umum & Profil Pelajar Pancasila</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 13px; margin-bottom: 18px;">
        <div>
            <div style="color: var(--text-dim); font-size: 11px;">SATUAN PENDIDIKAN:</div>
            <div style="font-weight: 700; color: #fff;">{{ $setting->school_name }}</div>
        </div>
        <div>
            <div style="color: var(--text-dim); font-size: 11px;">GURU PENGAMPU:</div>
            <div style="font-weight: 700; color: #fff;">{{ $setting->teacher_name }} (NIP: {{ $setting->teacher_nip }})</div>
        </div>
        <div>
            <div style="color: var(--text-dim); font-size: 11px;">DIMENSI PROFIL PANCASILA:</div>
            <div style="font-weight: 600; color: #a78bfa;">{{ $module->pancasila_profile }}</div>
        </div>
        <div>
            <div style="color: var(--text-dim); font-size: 11px;">PENDEKATAN & SINTAKS:</div>
            <div style="font-weight: 600; color: #34d399;">Deep Learning (Mindful, Meaningful, Joyful Learning)</div>
        </div>
    </div>

    <div style="background: rgba(0,0,0,0.25); padding: 14px; border-radius: 10px; border-left: 3px solid #3a86ff;">
        <div style="font-weight: 700; color: #fff; margin-bottom: 4px; font-size: 13px;">Tujuan Pembelajaran (TP):</div>
        <div style="font-size: 13px; color: var(--text-muted); white-space: pre-line;">{{ $module->learning_goals }}</div>
    </div>
</div>

<!-- Section 2: Tabel Diagnostik (Asesmen Awal) -->
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 16px; font-weight: 800; color: #f59e0b; margin-bottom: 14px;">2. Pemetaan & Tindak Lanjut Asesmen Diagnostik (Diferensiasi)</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Kategori Kesiapan</th>
                    <th style="width: 35%;">Karakteristik & Ciri Siswa</th>
                    <th style="width: 40%;">Rekomendasi Tindak Lanjut Diferensiasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diagnosticTable as $diag)
                    <tr>
                        <td style="font-weight: 700; color: #60a5fa;">{{ $diag['kategori'] }}</td>
                        <td style="color: #e2e8f0; font-size: 12.5px;">{{ $diag['ciri'] }}</td>
                        <td style="color: #34d399; font-size: 12.5px;">{{ $diag['tindak_lanjut'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Section 3: Skenario Pembelajaran Deep Learning (1-5 Pertemuan) -->
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 16px; font-weight: 800; color: #a78bfa; margin-bottom: 14px;">3. Skenario Pembelajaran Langkah Demi Langkah (Deep Learning)</h3>
    
    <div style="display: flex; flex-direction: column; gap: 18px;">
        @foreach($scenarios as $sc)
            <div style="background: rgba(14, 23, 46, 0.7); border: 1px solid var(--border-color); border-radius: 12px; padding: 18px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
                    <div style="font-size: 15px; font-weight: 800; color: #fff;">{{ $sc['topik'] }}</div>
                    <span style="font-size: 11px; font-weight: 700; color: #f59e0b; background: rgba(245, 158, 11, 0.15); padding: 3px 8px; border-radius: 6px;">
                        Alokasi: {{ $sc['durasi'] }}
                    </span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div>
                        <strong style="color: #38bdf8;">A. Kegiatan Pendahuluan (Mindful Engagement):</strong>
                        <p style="color: var(--text-muted); margin-top: 2px; white-space: pre-line;">{{ $sc['pendahuluan'] }}</p>
                    </div>
                    <div>
                        <strong style="color: #34d399;">B. Kegiatan Inti (Meaningful & Joyful Investigation):</strong>
                        <p style="color: var(--text-muted); margin-top: 2px; white-space: pre-line;">{{ $sc['kegiatan_inti'] }}</p>
                    </div>
                    <div>
                        <strong style="color: #c084fc;">C. Kegiatan Penutup (Metacognitive Reflection):</strong>
                        <p style="color: var(--text-muted); margin-top: 2px; white-space: pre-line;">{{ $sc['penutup'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Section 4: Rubrik Asesmen & LKPD -->
<div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px;">
    <!-- Rubrik Asesmen -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 800; color: #38bdf8; margin-bottom: 14px;">4. Rubrik Penilaian Autentik</h3>
        <div style="display: flex; flex-direction: column; gap: 14px;">
            @foreach($assessmentRubric as $rub)
                <div style="background: rgba(0,0,0,0.25); padding: 12px; border-radius: 8px;">
                    <div style="font-weight: 700; color: #fff; font-size: 13px; margin-bottom: 6px;">{{ $rub['aspek'] }}</div>
                    <div style="font-size: 11.5px; color: var(--text-muted); display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                        <div><strong style="color: #34d399;">Skor 4:</strong> {{ $rub['skor_4'] }}</div>
                        <div><strong style="color: #38bdf8;">Skor 3:</strong> {{ $rub['skor_3'] }}</div>
                        <div><strong style="color: #f59e0b;">Skor 2:</strong> {{ $rub['skor_2'] }}</div>
                        <div><strong style="color: #fb7185;">Skor 1:</strong> {{ $rub['skor_1'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- LKPD Interaktif -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 800; color: #10b981; margin-bottom: 14px;">5. Lembar Kerja Peserta Didik (LKPD)</h3>
        <div style="background: rgba(16, 185, 129, 0.08); border: 1px dashed rgba(16, 185, 129, 0.4); padding: 16px; border-radius: 10px;">
            <div style="font-weight: 800; color: #34d399; font-size: 14px; margin-bottom: 6px;">{{ $lkpd['judul'] ?? 'LKPD Interaktif' }}</div>
            <div style="font-size: 12px; color: var(--text-dim); margin-bottom: 12px;">{{ $lkpd['petunjuk'] ?? '-' }}</div>
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @if(isset($lkpd['tugas']))
                    @foreach($lkpd['tugas'] as $t)
                        <div style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 6px; font-size: 12.5px; color: #fff;">
                            {{ $t }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
