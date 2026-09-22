@extends('layouts.app')

@section('title', 'Scan & Input Absensi')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Pencatatan Presensi Siswa</h2>
        <p style="font-size: 13px; color: #475569;">Input presensi harian manual atau gunakan scan otomatis kode QR</p>
    </div>
    <a href="{{ route('attendance.scanner') }}" class="btn btn-yellow">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="18" height="18" x="3" y="3" rx="2"/><rect width="5" height="5" x="7" y="7"/><rect width="5" height="5" x="7" y="13"/><rect width="5" height="5" x="13" y="7"/>
        </svg>
        Buka Scanner Kamera QR
    </a>
</div>

<!-- Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 20px;">
    <div class="card" style="padding: 14px 18px;">
        <span style="font-size: 11px; color: var(--text-dim); font-weight: 700;">TOTAL SISWA</span>
        <div style="font-size: 20px; font-weight: 800; color: #0369a1; margin-top: 4px;">{{ $stats['total'] }}</div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #10b981;">
        <span style="font-size: 11px; color: #34d399; font-weight: 700;">HADIR (H)</span>
        <div style="font-size: 20px; font-weight: 800; color: #34d399; margin-top: 4px;">{{ $stats['hadir'] }}</div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #38bdf8;">
        <span style="font-size: 11px; color: #38bdf8; font-weight: 700;">IZIN (I)</span>
        <div style="font-size: 20px; font-weight: 800; color: #38bdf8; margin-top: 4px;">{{ $stats['izin'] }}</div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #f59e0b;">
        <span style="font-size: 11px; color: #f59e0b; font-weight: 700;">SAKIT (S)</span>
        <div style="font-size: 20px; font-weight: 800; color: #f59e0b; margin-top: 4px;">{{ $stats['sakit'] }}</div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #f43f5e;">
        <span style="font-size: 11px; color: #fb7185; font-weight: 700;">ALPA (A)</span>
        <div style="font-size: 20px; font-weight: 800; color: #fb7185; margin-top: 4px;">{{ $stats['alpa'] }}</div>
    </div>
</div>

<!-- Selector Form -->
<div class="card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" action="{{ route('attendance.index') }}" style="display: flex; gap: 16px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label class="form-label">Pilih Kelas</label>
            <select name="class_id" class="form-select" onchange="this.form.submit()">
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }} ({{ $c->students->count() }} Siswa)</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0; width: 200px;">
            <label class="form-label">Tanggal Presensi</label>
            <input type="date" name="date" value="{{ $selectedDate }}" class="form-control" onchange="this.form.submit()">
        </div>
        <button type="button" class="btn btn-outline" onclick="setAllStatus('Hadir')">Set Semua Hadir</button>
    </form>
</div>

<!-- Attendance Form Table -->
<div class="card">
    <form action="{{ route('attendance.storeManual') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>JK</th>
                        <th style="width: 320px; text-align: center;">Status Kehadiran</th>
                        <th>Waktu Scan / Masuk</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $idx => $st)
                        @php
                            $att = $attendances[$st->id] ?? null;
                            $currentStatus = $att ? $att->status : 'Hadir';
                        @endphp
                        <tr>
                            <td style="color: var(--text-dim);">{{ $idx + 1 }}</td>
                            <td style="font-weight: 600; color: #60a5fa;">{{ $st->nisn }}</td>
                            <td style="font-weight: 700; color: var(--title-color);">{{ $st->name }}</td>
                            <td>
                                <span style="font-weight: 600; color: {{ $st->gender == 'L' ? '#38bdf8' : '#f472b6' }};">
                                    {{ $st->gender }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 6px; background: rgba(0,0,0,0.25); padding: 4px; border-radius: 8px;">
                                    <label style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 700;" class="status-radio-label">
                                        <input type="radio" name="statuses[{{ $st->id }}]" value="Hadir" {{ $currentStatus === 'Hadir' ? 'checked' : '' }}>
                                        <span style="color: #34d399;">Hadir</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 700;" class="status-radio-label">
                                        <input type="radio" name="statuses[{{ $st->id }}]" value="Izin" {{ $currentStatus === 'Izin' ? 'checked' : '' }}>
                                        <span style="color: #38bdf8;">Izin</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 700;" class="status-radio-label">
                                        <input type="radio" name="statuses[{{ $st->id }}]" value="Sakit" {{ $currentStatus === 'Sakit' ? 'checked' : '' }}>
                                        <span style="color: #f59e0b;">Sakit</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 700;" class="status-radio-label">
                                        <input type="radio" name="statuses[{{ $st->id }}]" value="Alpa" {{ $currentStatus === 'Alpa' ? 'checked' : '' }}>
                                        <span style="color: #fb7185;">Alpa</span>
                                    </label>
                                </div>
                            </td>
                            <td style="font-size: 12px; color: var(--text-dim);">
                                @if($att && $att->check_in_time)
                                    <span style="color: #34d399; font-weight: 600;">{{ $att->check_in_time }}</span> ({{ $att->method }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <input type="text" name="notes[{{ $st->id }}]" value="{{ $att->notes ?? '' }}" placeholder="Catatan..." class="form-control" style="padding: 6px 10px; font-size: 12px;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--text-dim);">
                                Pilih kelas untuk mengisi presensi siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($students) > 0)
            <div style="display: flex; justify-content: flex-end; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    Simpan Presensi Kelas
                </button>
            </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    function setAllStatus(status) {
        document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
    }
</script>
@endpush
@endsection
