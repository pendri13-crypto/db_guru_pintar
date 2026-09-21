@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Kelola Mata Pelajaran</h2>
        <p style="font-size: 13px; color: #475569;">Atur kurikulum, alokasi jam mengajar per minggu, dan semester aktif</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('addSubjectModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
        </svg>
        Tambah Mata Pelajaran
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kategori</th>
                    <th>Alokasi JP</th>
                    <th>Semester</th>
                    <th>Total Jadwal</th>
                    <th>Modul Ajar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $sb)
                    <tr>
                        <td>
                            <span style="font-weight: 700; color: #38bdf8; background: rgba(56, 189, 248, 0.12); padding: 4px 8px; border-radius: 6px; font-size: 11px;">
                                {{ $sb->code }}
                            </span>
                        </td>
                        <td style="font-weight: 700; color: #ffffff;">{{ $sb->name }}</td>
                        <td>
                            <span style="background: rgba(139, 92, 246, 0.15); color: #c084fc; font-weight: 600; padding: 3px 8px; border-radius: 6px; font-size: 11px;">
                                {{ $sb->category }}
                            </span>
                        </td>
                        <td>{{ $sb->weekly_hours }} JP / Minggu</td>
                        <td>{{ $sb->semester }}</td>
                        <td>{{ $sb->schedules_count }} Jadwal Tatap Muka</td>
                        <td>
                            <span style="color: #f59e0b; font-weight: 700;">{{ $sb->modules_count }} Modul</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" class="btn btn-sm btn-outline" onclick="editSubject({{ json_encode($sb) }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('subjects.destroy', $sb->id) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.3);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-dim);">
                            Belum ada mata pelajaran terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Subject -->
<div id="addSubjectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: #0f172a;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Tambah Mata Pelajaran</h3>
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Kode Mapel *</label>
                <input type="text" name="code" class="form-control" placeholder="Contoh: INF-8" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran *</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: INFORMATIKA" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category" class="form-select" required>
                        <option value="Wajib">Wajib</option>
                        <option value="Pilihan">Pilihan / Muatan Lokal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alokasi JP / Minggu *</label>
                    <input type="number" name="weekly_hours" value="3" min="1" max="10" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Semester *</label>
                <select name="semester" class="form-select" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addSubjectModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Subject -->
<div id="editSubjectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: #0f172a;">
        <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 16px;">Edit Mata Pelajaran</h3>
        <form id="editSubjectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Kode Mapel *</label>
                <input type="text" name="code" id="edit_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran *</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category" id="edit_category" class="form-select" required>
                        <option value="Wajib">Wajib</option>
                        <option value="Pilihan">Pilihan / Muatan Lokal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Alokasi JP / Minggu *</label>
                    <input type="number" name="weekly_hours" id="edit_weekly_hours" min="1" max="10" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Semester *</label>
                <select name="semester" id="edit_semester" class="form-select" required>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editSubjectModal')">Batal</button>
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
    function editSubject(subject) {
        document.getElementById('editSubjectForm').action = '/subjects/' + subject.id;
        document.getElementById('edit_code').value = subject.code;
        document.getElementById('edit_name').value = subject.name;
        document.getElementById('edit_category').value = subject.category;
        document.getElementById('edit_weekly_hours').value = subject.weekly_hours;
        document.getElementById('edit_semester').value = subject.semester;
        openModal('editSubjectModal');
    }
</script>
@endpush
@endsection
