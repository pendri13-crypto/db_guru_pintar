@extends('layouts.app')

@section('title', 'Bimbingan Guru Wali & Konseling')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Bimbingan Guru Wali & Konseling Siswa</h2>
        <p style="font-size: 13px; color: #475569;">Pencatatan konseling perkembangan belajar, apresiasi prestasi, dan tindak lanjut ke orang tua</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('addGuidanceModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
        </svg>
        Tambah Catatan Bimbingan
    </button>
</div>

<!-- Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
    <div class="card" style="padding: 16px 20px;">
        <span style="font-size: 11px; color: var(--text-dim); font-weight: 700;">TOTAL CATATAN</span>
        <div style="font-size: 22px; font-weight: 800; color: #0369a1; margin-top: 4px;">{{ $stats['total'] }}</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #10b981;">
        <span style="font-size: 11px; color: #34d399; font-weight: 700;">APRESIASI / PRESTASI</span>
        <div style="font-size: 22px; font-weight: 800; color: #34d399; margin-top: 4px;">{{ $stats['apresiasi'] }}</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #38bdf8;">
        <span style="font-size: 11px; color: #38bdf8; font-weight: 700;">SESI KONSELING</span>
        <div style="font-size: 22px; font-weight: 800; color: #38bdf8; margin-top: 4px;">{{ $stats['konseling'] }}</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #f43f5e;">
        <span style="font-size: 11px; color: #fb7185; font-weight: 700;">PELANGGARAN / PEMANTAUAN</span>
        <div style="font-size: 22px; font-weight: 800; color: #fb7185; margin-top: 4px;">{{ $stats['pelanggaran'] }}</div>
    </div>
</div>

<!-- Guidance List Table -->
<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Siswa & Kelas</th>
                    <th>Kategori</th>
                    <th>Perihal & Deskripsi Masalah</th>
                    <th>Tindakan / Solusi Guru</th>
                    <th>Tindak Lanjut Orang Tua</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guidances as $g)
                    <tr>
                        <td style="color: var(--text-dim); font-size: 12px;">{{ $g->date }}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--title-color);">{{ $g->student->name }}</div>
                            <span style="background: rgba(58, 134, 255, 0.15); color: #60a5fa; font-weight: 700; padding: 2px 6px; border-radius: 4px; font-size: 10px;">
                                Kelas {{ $g->student->schoolClass->name }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badgeColor = match($g->type) {
                                    'Apresiasi' => '#10b981',
                                    'Konseling' => '#38bdf8',
                                    'Pelanggaran' => '#f43f5e',
                                    default => '#f59e0b',
                                };
                            @endphp
                            <span style="border: 1px solid {{ $badgeColor }}; color: {{ $badgeColor }}; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                {{ $g->type }}
                            </span>
                        </td>
                        <td style="max-width: 250px;">
                            <div style="font-weight: 700; color: var(--title-color); margin-bottom: 2px;">{{ $g->title }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted); line-height: 1.4;">{{ $g->description }}</div>
                        </td>
                        <td style="max-width: 200px; font-size: 12px; color: #e2e8f0;">
                            {{ $g->action_taken ?? '-' }}
                        </td>
                        <td style="max-width: 180px; font-size: 12px; color: var(--text-dim);">
                            {{ $g->parent_followup ?? '-' }}
                        </td>
                        <td>
                            <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                {{ $g->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('guidance.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus catatan bimbingan ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.3);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-dim);">
                            Belum ada catatan bimbingan siswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(30, 44, 79, 0.6); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="font-size: 13px; color: var(--text-dim);">
            Menampilkan <strong style="color: #fff;">{{ $guidances->firstItem() ?? 0 }}</strong> - <strong style="color: #fff;">{{ $guidances->lastItem() ?? 0 }}</strong> dari <strong style="color: #fff;">{{ $guidances->total() }}</strong> catatan
        </div>
        <div>
            {{ $guidances->links('pagination.custom') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Guidance -->
<div id="addGuidanceModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 600px; background: #0f172a; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Tambah Catatan Bimbingan Siswa</h3>
        <form action="{{ route('guidance.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Pilih Siswa *</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Cari Nama Siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->name }} (Kelas {{ $st->schoolClass->name }}) - NISN: {{ $st->nisn }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal Bimbingan *</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="type" class="form-select" required>
                        <option value="Konseling">Konseling Belajar</option>
                        <option value="Apresiasi">Apresiasi & Prestasi Siswa</option>
                        <option value="Pelanggaran">Pelanggaran / Kedisiplinan</option>
                        <option value="Panggilan Ortu">Panggilan Orang Tua</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Judul / Perihal *</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Juara 1 Lomba Coding / Konseling Kesulitan Belajar" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Kasus / Masalah / Capaian *</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Jelaskan secara detail..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Tindakan / Solusi dari Guru Wali</label>
                <textarea name="action_taken" rows="2" class="form-control" placeholder="Langkah pembinaan atau penghargaan yang diberikan..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tindak Lanjut Orang Tua</label>
                    <input type="text" name="parent_followup" class="form-control" placeholder="Konfirmasi telepon / pertemuan wali murid...">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Penanganan *</label>
                    <select name="status" class="form-select" required>
                        <option value="Selesai">Selesai</option>
                        <option value="Dalam Proses">Dalam Proses</option>
                        <option value="Perlu Pemantauan">Perlu Pemantauan</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addGuidanceModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Bimbingan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
@endpush
@endsection
