@extends('layouts.app')

@section('title', 'Agenda Mengajar Guru (Jurnal KBM)')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Agenda Mengajar Guru (Jurnal KBM)</h2>
        <p style="font-size: 13px; color: #475569;">Jurnal harian KBM, keterlaksanaan materi, catatan kehadiran, dan solusi kendala</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('reports.printJournals') }}" target="_blank" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
            </svg>
            Cetak Jurnal PDF
        </a>
        <button type="button" class="btn btn-primary" onclick="openModal('addJournalModal')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
            </svg>
            Tambah Jurnal KBM
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" action="{{ route('journals.index') }}" style="display: flex; gap: 14px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Filter Kelas</label>
            <select name="class_id" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Mata Pelajaran</label>
            <select name="subject_id" class="form-select">
                <option value="">Semua Mapel</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="height: 42px;">Filter</button>
            <a href="{{ route('journals.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('journals.index') }}';" class="btn btn-outline" style="height: 42px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
        </div>
    </form>
</div>

<!-- Journal List Table -->
<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal & Jam</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Ke-</th>
                    <th>Materi Pokok & TP</th>
                    <th>Aktivitas KBM</th>
                    <th>Kehadiran</th>
                    <th>Keterlaksanaan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $j)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #fff;">{{ \Carbon\Carbon::parse($j->date)->isoFormat('dddd, D MMMM Y') }}</div>
                        </td>
                        <td>
                            <span style="background: rgba(58, 134, 255, 0.15); color: #60a5fa; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 11px;">
                                {{ $j->schoolClass->name }}
                            </span>
                        </td>
                        <td style="font-weight: 600; color: #c084fc;">{{ $j->subject->name }}</td>
                        <td>
                            <span style="font-weight: 800; color: #f59e0b;">Pertemuan {{ $j->meeting_number }}</span>
                        </td>
                        <td style="max-width: 250px;">
                            <div style="font-weight: 700; color: #fff; margin-bottom: 2px;">{{ $j->topic }}</div>
                            <div style="font-size: 11px; color: var(--text-dim); line-height: 1.3;">{{ Str::limit($j->learning_objective, 70) }}</div>
                        </td>
                        <td style="max-width: 220px; font-size: 12px; color: var(--text-muted);">
                            {{ Str::limit($j->activities, 75) }}
                        </td>
                        <td>
                            <span style="color: #34d399; font-weight: 700;">{{ $j->total_present }} Hadir</span>
                            @if($j->total_absent > 0)
                                <span style="color: #fb7185; font-size: 11px; display: block;">{{ $j->total_absent }} Absen</span>
                            @endif
                        </td>
                        <td>
                            <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                Terlaksana
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('journals.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jurnal KBM ini?')" style="display: inline;">
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
                        <td colspan="9" style="text-align: center; padding: 32px; color: var(--text-dim);">
                            Belum ada agenda mengajar / jurnal KBM yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(30, 44, 79, 0.6); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="font-size: 13px; color: var(--text-dim);">
            Menampilkan <strong style="color: #fff;">{{ $journals->firstItem() ?? 0 }}</strong> - <strong style="color: #fff;">{{ $journals->lastItem() ?? 0 }}</strong> dari <strong style="color: #fff;">{{ $journals->total() }}</strong> jurnal
        </div>
        <div>
            {{ $journals->links('pagination.custom') }}
        </div>
    </div>
</div>

<!-- Modal Add Journal -->
<div id="addJournalModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 650px; background: #0f172a; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Catat Jurnal Mengajar Baru</h3>
        <form action="{{ route('journals.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Tanggal KBM *</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas *</label>
                    <select name="class_id" class="form-select" required>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pertemuan Ke- *</label>
                    <input type="number" name="meeting_number" value="1" min="1" max="30" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mata Pelajaran *</label>
                <select name="subject_id" class="form-select" required>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Topik / Materi Pokok Pembelajaran *</label>
                <input type="text" name="topic" class="form-control" placeholder="Contoh: Berpikir Komputasional: Dekomposisi & Pola" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tujuan Pembelajaran (TP) *</label>
                <textarea name="learning_objective" rows="2" class="form-control" placeholder="Tujuan yang ingin dicapai pada pertemuan ini..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Ringkasan Aktivitas KBM</label>
                <textarea name="activities" rows="2" class="form-control" placeholder="Apersepsi, diskusi kelompok, praktikum komputer, penutup..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Jumlah Siswa Hadir</label>
                    <input type="number" name="total_present" value="32" min="0" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Siswa Tidak Hadir</label>
                    <input type="number" name="total_absent" value="0" min="0" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Kendala & Solusi / Tindak Lanjut</label>
                <input type="text" name="obstacle_solution" class="form-control" placeholder="Kendala teknis lab, diferensiasi belajar, dll">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addJournalModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Jurnal KBM</button>
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
