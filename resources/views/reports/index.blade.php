@extends('layouts.app')

@section('title', 'Pusat Laporan PDF')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Pusat Cetak Dokumen & Laporan PDF</h2>
    <p style="font-size: 13px; color: #475569;">Cetak rekapitulasi presensi, leger nilai siswa, dan jurnal harian KBM resmi bertanda tangan</p>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    <!-- 1. Cetak Rekapitulasi Presensi -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="width: 42px; height: 42px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; border: 1px solid #a7f3d0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>
                </svg>
            </div>
            <h3 style="font-size: 16px; font-weight: 800; color: #0369a1; margin-bottom: 6px;">Rekapitulasi Presensi Siswa</h3>
            <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.45; margin-bottom: 16px;">
                Cetak laporan persentase kehadiran bulanan (Hadir, Izin, Sakit, Alpa) per kelas.
            </p>
        </div>

        <form action="{{ route('reports.printAttendance') }}" method="GET" target="_blank">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label">Pilih Kelas</label>
                <select name="class_id" class="form-select" required>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 14px;">
                <div>
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>Bulan {{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" value="{{ date('Y') }}" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                🖨️ Cetak Rekap Presensi
            </button>
        </form>
    </div>

    <!-- 2. Cetak Leger Nilai Siswa -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(139, 92, 246, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
            <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin-bottom: 6px;">Leger Nilai Akademik</h3>
            <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.45; margin-bottom: 16px;">
                Cetak daftar nilai formatif, sumatif, UTS, UAS, nilai akhir dan predikat resmi.
            </p>
        </div>

        <form action="{{ route('reports.printGrades') }}" method="GET" target="_blank">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label">Pilih Kelas</label>
                <select name="class_id" class="form-select" required>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 8px; margin-bottom: 14px;">
                <div>
                    <label class="form-label">Mapel</label>
                    <select name="subject_id" class="form-select" required>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                🖨️ Cetak Leger Nilai
            </button>
        </form>
    </div>

    <!-- 3. Cetak Jurnal Harian Mengajar KBM -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(58, 134, 255, 0.15); color: #3a86ff; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/>
                </svg>
            </div>
            <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin-bottom: 6px;">Jurnal Harian Mengajar (KBM)</h3>
            <p style="font-size: 12.5px; color: var(--text-muted); line-height: 1.45; margin-bottom: 16px;">
                Cetak buku jurnal agenda mengajar tatap muka, materi TP, dan keterlaksanaan KBM.
            </p>
        </div>

        <form action="{{ route('reports.printJournals') }}" method="GET" target="_blank">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label">Pilih Kelas</label>
                <select name="class_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label">Mata Pelajaran</label>
                <select name="subject_id" class="form-select">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                🖨️ Cetak Jurnal KBM
            </button>
        </form>
    </div>
</div>
@endsection
