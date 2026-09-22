@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Kelola Data Kelas & Wali Kelas</h2>
        <p style="font-size: 13px; color: #475569;">Atur daftar kelas, jenjang, tahun ajaran, dan tetapkan Wali Kelas untuk setiap rombel</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openModal('addClassModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
        </svg>
        Tambah Kelas
    </button>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat / Jenjang</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $cls)
                    <tr>
                        <td>
                            <span style="font-weight: 800; color: #38bdf8; font-size: 14px;">
                                {{ $cls->name }}
                            </span>
                        </td>
                        <td>
                            <span style="background: rgba(139, 92, 246, 0.15); color: #c084fc; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 11px;">
                                Kelas {{ $cls->level }}
                            </span>
                        </td>
                        <td style="color: var(--text-dim); font-size: 13px; font-weight: 500;">{{ $cls->academic_year ?? '-' }}</td>
                        <td>
                            @if($cls->homeroom_teacher)
                                <div style="font-weight: 700; color: var(--title-color);">{{ $cls->homeroom_teacher }}</div>
                            @else
                                <span style="color: #fb7185; font-style: italic; font-size: 12px;">Belum Diatur</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #10b981;">{{ $cls->students_count ?? 0 }} Siswa</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" class="btn btn-sm btn-outline" onclick="editClass({{ json_encode($cls) }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('classes.destroy', $cls->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $cls->name }}? Pastikan kelas ini sudah kosong dari siswa.')" style="display: inline;">
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
                        <td colspan="6" style="text-align: center; padding: 32px; color: var(--text-dim);">
                            Belum ada data kelas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div id="addClassModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: var(--bg-card); max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--title-color); margin-bottom: 16px;">Tambah Kelas Baru</h3>
        <form action="{{ route('classes.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Nama Kelas *</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: 8A atau VIII-C" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat / Jenjang *</label>
                    <input type="number" name="level" class="form-control" placeholder="Contoh: 8" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="academic_year" class="form-control" placeholder="Contoh: 2025/2026" value="2025/2026">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Wali Kelas</label>
                <input type="text" name="homeroom_teacher" class="form-control" placeholder="Nama Guru Wali Kelas (Opsional)">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addClassModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="editClassModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 99; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; background: var(--bg-card); max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--title-color); margin-bottom: 16px;">Edit Data Kelas</h3>
        <form id="editClassForm" action="" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Nama Kelas *</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat / Jenjang *</label>
                    <input type="number" id="edit_level" name="level" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" id="edit_academic_year" name="academic_year" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Wali Kelas</label>
                <input type="text" id="edit_homeroom_teacher" name="homeroom_teacher" class="form-control">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editClassModal')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function editClass(data) {
        document.getElementById('editClassForm').action = '/classes/' + data.id;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_level').value = data.level;
        document.getElementById('edit_academic_year').value = data.academic_year || '';
        document.getElementById('edit_homeroom_teacher').value = data.homeroom_teacher || '';
        openModal('editClassModal');
    }
</script>
@endpush
