@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Jadwal Mengajar Tatap Muka</h2>
        <p style="font-size: 13px; color: #475569;">Kelola jadwal tatap muka kelas, jam pelajaran, dan ruang kelas</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('addScheduleModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
        </svg>
        Tambah Jadwal
    </button>
</div>

<!-- Day Filter Tabs -->
<div style="display: flex; gap: 8px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 4px;">
    <a href="{{ route('schedules.index', ['day' => 'Semua']) }}" class="btn btn-sm {{ $selectedDay === 'Semua' ? 'btn-primary' : 'btn-outline' }}">
        Semua Hari
    </a>
    @foreach($days as $d)
        <a href="{{ route('schedules.index', ['day' => $d]) }}" class="btn btn-sm {{ $selectedDay === $d ? 'btn-primary' : 'btn-outline' }}">
            {{ $d }}
        </a>
    @endforeach
</div>

<!-- Schedule Grid / Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 18px;">
    @forelse($schedules as $sch)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 13px; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 4px 10px; border-radius: 8px; border: 1px solid #bae6fd;">
                        {{ $sch->day }}
                    </span>
                    <span style="font-size: 12px; font-weight: 700; color: #d97706; background: #fef3c7; padding: 4px 10px; border-radius: 8px; border: 1px solid #fde68a;">
                        {{ $sch->start_time }} - {{ $sch->end_time }}
                    </span>
                </div>

                <div style="font-size: 17px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">
                    {{ $sch->subject->name }}
                </div>
                <div style="font-size: 13px; color: #0284c7; font-weight: 600; margin-bottom: 8px;">
                    Kelas {{ $sch->schoolClass->name }} (Wali: {{ $sch->schoolClass->homeroom_teacher ?? '-' }})
                </div>
                <div style="font-size: 12px; color: var(--text-dim); display: flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Ruang: {{ $sch->room }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border-color);">
                <a href="{{ route('attendance.index', ['class_id' => $sch->class_id]) }}" class="btn btn-sm btn-outline" style="color: #34d399; border-color: rgba(16, 185, 129, 0.3);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Presensi Kelas
                </a>
                <div style="display: flex; gap: 6px;">
                    <button type="button" class="btn btn-sm btn-outline" onclick="editSchedule({{ json_encode($sch) }})">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                    </button>
                    <form action="{{ route('schedules.destroy', $sch->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.3);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-dim);">
            Belum ada jadwal mengajar pada hari {{ $selectedDay }}.
        </div>
    @endforelse
</div>

<!-- Modal Tambah Schedule -->
<div id="addScheduleModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: #0f172a;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Tambah Jadwal Mengajar</h3>
        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Hari *</label>
                    <select name="day" class="form-select" required>
                        @foreach($days as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas *</label>
                    <select name="class_id" class="form-select" required>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai *</label>
                    <input type="text" name="start_time" class="form-control" placeholder="08:30" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai *</label>
                    <input type="text" name="end_time" class="form-control" placeholder="09:50" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ruang Kelas / Lab *</label>
                <input type="text" name="room" class="form-control" placeholder="Contoh: Lab Komputer 1" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addScheduleModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Schedule -->
<div id="editScheduleModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: #0f172a;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Edit Jadwal Mengajar</h3>
        <form id="editScheduleForm" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Hari *</label>
                    <select name="day" id="edit_sch_day" class="form-select" required>
                        @foreach($days as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas *</label>
                    <select name="class_id" id="edit_sch_class_id" class="form-select" required>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">Kelas {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mata Pelajaran *</label>
                <select name="subject_id" id="edit_sch_subject_id" class="form-select" required>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai *</label>
                    <input type="text" name="start_time" id="edit_sch_start_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai *</label>
                    <input type="text" name="end_time" id="edit_sch_end_time" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ruang Kelas / Lab *</label>
                <input type="text" name="room" id="edit_sch_room" class="form-control" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editScheduleModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    function editSchedule(sch) {
        document.getElementById('editScheduleForm').action = '/schedules/' + sch.id;
        document.getElementById('edit_sch_day').value = sch.day;
        document.getElementById('edit_sch_class_id').value = sch.class_id;
        document.getElementById('edit_sch_subject_id').value = sch.subject_id;
        document.getElementById('edit_sch_start_time').value = sch.start_time;
        document.getElementById('edit_sch_end_time').value = sch.end_time;
        document.getElementById('edit_sch_room').value = sch.room;
        openModal('editScheduleModal');
    }
</script>
@endpush
@endsection
