@extends('layouts.app')

@section('title', 'Kelola Master Siswa')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Kelola Master Siswa</h2>
        <p style="font-size: 13px; color: #475569;">Total {{ $totalStudents }} siswa terdaftar di seluruh kelas</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Export Excel -->
        <a href="{{ route('students.export', request()->query()) }}" class="btn btn-outline" style="color: #34d399; border-color: rgba(16, 185, 129, 0.35); background: rgba(16, 185, 129, 0.08);" title="Ekspor Data Siswa ke Excel sesuai Filter Aktif">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
            </svg>
            Ekspor Excel
        </a>

        <!-- Import Excel Modal Trigger -->
        <button type="button" onclick="openImportModal()" class="btn btn-outline" style="color: #60a5fa; border-color: rgba(59, 130, 246, 0.35); background: rgba(59, 130, 246, 0.08);" title="Impor Data Siswa dari File Excel / CSV">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
            </svg>
            Impor Excel
        </button>

        @if($totalStudents > 0)
        <button type="button" onclick="openDeleteAllModal()" class="btn btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.35); background: rgba(244, 63, 94, 0.08);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            Hapus Semua
        </button>
        @endif
        <a href="{{ route('cards.index') }}" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2"/><rect width="5" height="5" x="7" y="7"/><rect width="5" height="5" x="7" y="13"/><rect width="5" height="5" x="13" y="7"/>
            </svg>
            Cetak Kartu QR
        </a>
        <a href="{{ route('students.create') }}" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
            </svg>
            Tambah Siswa
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" action="{{ route('students.index') }}" id="studentFilterForm" style="display: grid; grid-template-columns: 2fr 1.3fr 1fr auto; gap: 14px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari Nama / NISN</label>
            <input type="text" name="search" id="filterSearch" value="{{ request('search') }}" placeholder="Ketik nama atau NISN siswa..." class="form-control">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Filter Kelas</label>
            <select name="class_id" id="filterClass" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas ({{ $classesWithStudentsCount }} Kelas - {{ $totalStudents }} Siswa)</option>
                @foreach($classes->where('students_count', '>', 0) as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                        Kelas {{ $cls->name }} ({{ $cls->students_count }} Siswa)
                    </option>
                @endforeach
                @if($classes->where('students_count', '==', 0)->count() > 0)
                    <optgroup label="Kelas Lainnya (Belum Ada Siswa)">
                        @foreach($classes->where('students_count', '==', 0) as $cls)
                            <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                Kelas {{ $cls->name }} (0 Siswa)
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Jenis Kelamin</label>
            <select name="gender" id="filterGender" class="form-select" onchange="this.form.submit()">
                <option value="">Semua (L / P)</option>
                <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="height: 42px;">Filter</button>
            <a href="{{ route('students.index') }}" onclick="resetStudentFilter(event)" class="btn btn-outline" style="height: 42px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
        </div>
    </form>
</div>

<!-- Student Table -->
<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NISN / NIS</th>
                    <th>Kelas</th>
                    <th>JK</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $stu)
                    <tr>
                        <td style="color: var(--text-dim);">{{ $students->firstItem() + $index }}</td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $stu->name }}</div>
                            <div style="font-size: 11px; color: var(--text-dim);">{{ $stu->email ?? '-' }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: var(--primary);">{{ $stu->nisn }}</span>
                            <span style="font-size: 11px; color: var(--text-dim); display: block;">NIS: {{ $stu->nis ?? '-' }}</span>
                        </td>
                        <td>
                            <span style="background: var(--bg-soft); color: var(--primary); font-weight: 700; padding: 3px 8px; border-radius: 6px; font-size: 12px;">
                                {{ $stu->schoolClass->name }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $stu->gender == 'L' ? '#38bdf8' : '#f472b6' }};">
                                {{ $stu->gender == 'L' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $stu->phone ?? '-' }}</td>
                        <td>
                            <span style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                {{ $stu->status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('students.edit', $stu->id) }}" class="btn btn-sm btn-outline" title="Edit Siswa">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('students.destroy', $stu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.3);" title="Hapus Siswa">
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
                            Tidak ada data siswa yang sesuai filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="font-size: 13px; color: var(--text-dim);">
            Menampilkan <strong style="color: var(--text-main);">{{ $students->firstItem() ?? 0 }}</strong> - <strong style="color: var(--text-main);">{{ $students->lastItem() ?? 0 }}</strong> dari <strong style="color: var(--text-main);">{{ $students->total() }}</strong> siswa
        </div>
        <div>
            {{ $students->links('pagination.custom') }}
        </div>
    </div>
</div>

<!-- Modal Hapus Semua Data Siswa -->
<div id="deleteAllModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px; animation: fadeIn 0.2s ease;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; max-width: 520px; width: 100%; box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3); overflow: hidden; position: relative;">
        <!-- Modal Header -->
        <div style="padding: 24px 26px 18px; border-bottom: 1px solid var(--border-color); display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 46px; height: 46px; border-radius: 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; justify-content: center; color: #ef4444; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">Hapus Semua Data Siswa</h3>
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
            <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12.5px; font-weight: 700; color: #ef4444; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Dampak Penghapusan:
                </div>
                <ul style="font-size: 12px; color: var(--text-dim); padding-left: 18px; margin: 0; line-height: 1.6;">
                    <li>Seluruh data biodata siswa yang dipilih akan dihapus.</li>
                    <li>Riwayat presensi harian & scan QR siswa akan ikut terhapus.</li>
                    <li>Rekap nilai tugas, formatif, sumatif & nilai rapor siswa akan ikut terhapus.</li>
                    <li>Catatan bimbingan konseling wali kelas akan ikut terhapus.</li>
                </ul>
            </div>

            <!-- Scope Choice (All Students vs Specific Class) -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="color: var(--text-main); font-weight: 700;">Pilih Cakupan Penghapusan:</label>
                <select name="class_id" id="deleteScopeSelect" class="form-select">
                    <option value="all">⚠️ HAPUS SELURUH SISWA (Semua Kelas - Total {{ $totalStudents }} Siswa)</option>
                    <optgroup label="Hapus per Kelas Tertentu:">
                        @foreach($classes->where('students_count', '>', 0) as $cls)
                            <option value="{{ $cls->id }}">Hanya Kelas {{ $cls->name }} ({{ $cls->students_count }} Siswa)</option>
                        @endforeach
                        @if($classes->where('students_count', '==', 0)->count() > 0)
                            <optgroup label="Kelas Kosong (0 Siswa):">
                                @foreach($classes->where('students_count', '==', 0) as $cls)
                                    <option value="{{ $cls->id }}">Kelas {{ $cls->name }} (0 Siswa)</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </optgroup>
                </select>
            </div>

            <!-- Confirmation Text Input -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label" style="font-size: 12px; color: var(--text-dim);">
                    Ketik kata <strong style="color: #ef4444; letter-spacing: 0.5px;">HAPUS</strong> untuk mengaktifkan tombol konfirmasi:
                </label>
                <input type="text" id="confirmDeleteInput" placeholder="Ketik HAPUS di sini..." class="form-control" style="text-transform: uppercase; font-weight: 700; letter-spacing: 1px;" autocomplete="off">
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
                <button type="button" onclick="closeDeleteAllModal()" class="btn btn-outline" style="padding: 10px 20px;">
                    Batal
                </button>
                <button type="submit" id="btnSubmitDeleteAll" class="btn" style="background: #ef4444; color: #fff; padding: 10px 22px; font-weight: 700; opacity: 0.5; cursor: not-allowed;" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Ya, Hapus Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Impor Data Siswa (Excel) -->
<div id="importModal" style="display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(6px); align-items: center; justify-content: center; padding: 20px; animation: fadeIn 0.2s ease;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; max-width: 580px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); overflow: hidden; position: relative;">
        <!-- Modal Header -->
        <div style="padding: 22px 26px 18px; border-bottom: 1px solid var(--border-color); display: flex; align-items: flex-start; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); display: flex; align-items: center; justify-content: center; color: #3b82f6; flex-shrink: 0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                </svg>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 19px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">Impor Data Siswa via Excel</h3>
                <p style="font-size: 12.5px; color: var(--text-dim); line-height: 1.4;">Tambahkan atau perbarui data master siswa secara massal menggunakan file spreadsheet.</p>
            </div>
            <button type="button" onclick="closeImportModal()" style="background: transparent; border: none; color: var(--text-dim); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 6px;" title="Tutup">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body & Form -->
        <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data" id="formImportStudents" style="padding: 22px 26px 26px;">
            @csrf

            <!-- Step 1: Download Template Callout -->
            <div style="background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 14px; padding: 14px 16px; margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; color: #3b82f6; flex-shrink: 0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">Belum memiliki format file?</div>
                        <div style="font-size: 11.5px; color: var(--text-dim);">Unduh template resmi dengan contoh data & referensi kelas.</div>
                    </div>
                </div>
                <a href="{{ route('students.template') }}" class="btn btn-sm btn-primary" style="white-space: nowrap; padding: 8px 14px; font-size: 12px; font-weight: 700;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    Unduh Template (.xlsx)
                </a>
            </div>

            <!-- Step 2: Upload Drag & Drop Zone -->
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Pilih File Excel / CSV:</label>
                
                <div id="dropZone" style="border: 2px dashed var(--border-color); border-radius: 14px; padding: 24px 16px; text-align: center; cursor: pointer; transition: all 0.2s ease; position: relative;" onclick="document.getElementById('importFileInput').click()">
                    <input type="file" name="file" id="importFileInput" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileSelected(this)">
                    
                    <div id="dropZonePrompt">
                        <div style="width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 50%; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                        </div>
                        <div style="font-size: 13.5px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Klik untuk memilih file atau seret file ke sini</div>
                        <div style="font-size: 11.5px; color: var(--text-dim);">Mendukung format <strong>.xlsx, .xls, .csv</strong> (Maks. 10 MB)</div>
                    </div>

                    <!-- Selected File Info View -->
                    <div id="selectedFileInfo" style="display: none; align-items: center; justify-content: center; gap: 12px; padding: 6px 0;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; color: #10b981; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <div style="text-align: left;">
                            <div id="selectedFileName" style="font-size: 13px; font-weight: 700; color: #10b981; word-break: break-all;">file.xlsx</div>
                            <div id="selectedFileSize" style="font-size: 11px; color: var(--text-dim);">0 KB</div>
                        </div>
                        <button type="button" onclick="event.stopPropagation(); clearSelectedFile()" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 8px; padding: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; margin-left: 10px;" title="Ganti File">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Duplicate Action Choice -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Tindakan Jika NISN Sudah Terdaftar:</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; background: var(--bg-soft); border: 1px solid var(--border-color); padding: 12px 14px; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" id="labelActionSkip">
                        <input type="radio" name="duplicate_action" value="skip" checked style="margin-top: 3px;" onchange="updateRadioStyles()">
                        <div>
                            <div style="font-size: 12.5px; font-weight: 700; color: var(--text-main);">Lewati (Skip)</div>
                            <div style="font-size: 11px; color: var(--text-dim);">Abaikan jika siswa dengan NISN tsb sudah ada.</div>
                        </div>
                    </label>
                    <label style="display: flex; align-items: flex-start; gap: 10px; background: var(--bg-soft); border: 1px solid var(--border-color); padding: 12px 14px; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" id="labelActionUpdate">
                        <input type="radio" name="duplicate_action" value="update" style="margin-top: 3px;" onchange="updateRadioStyles()">
                        <div>
                            <div style="font-size: 12.5px; font-weight: 700; color: var(--text-main);">Perbarui (Update)</div>
                            <div style="font-size: 11px; color: var(--text-dim);">Timpa data profil siswa dengan data baru di file.</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
                <button type="button" onclick="closeImportModal()" class="btn btn-outline" style="padding: 10px 20px;">
                    Batal
                </button>
                <button type="submit" id="btnSubmitImport" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>
                    </svg>
                    <span id="btnSubmitImportText">Mulai Impor Siswa</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Import Modal Functions
    function openImportModal() {
        const modal = document.getElementById('importModal');
        modal.style.display = 'flex';
        clearSelectedFile();
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');
        modal.style.display = 'none';
    }

    function handleFileSelected(input) {
        const file = input.files[0];
        const prompt = document.getElementById('dropZonePrompt');
        const info = document.getElementById('selectedFileInfo');
        const nameEl = document.getElementById('selectedFileName');
        const sizeEl = document.getElementById('selectedFileSize');
        const submitBtn = document.getElementById('btnSubmitImport');
        const dropZone = document.getElementById('dropZone');

        if (file) {
            prompt.style.display = 'none';
            info.style.display = 'flex';
            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            dropZone.style.borderColor = 'rgba(16, 185, 129, 0.6)';
            dropZone.style.background = 'rgba(16, 185, 129, 0.05)';
        } else {
            clearSelectedFile();
        }
    }

    function clearSelectedFile() {
        const input = document.getElementById('importFileInput');
        input.value = '';
        document.getElementById('dropZonePrompt').style.display = 'block';
        document.getElementById('selectedFileInfo').style.display = 'none';
        const submitBtn = document.getElementById('btnSubmitImport');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
        const dropZone = document.getElementById('dropZone');
        dropZone.style.borderColor = 'rgba(59, 130, 246, 0.4)';
        dropZone.style.background = 'rgba(14, 23, 46, 0.6)';
    }

    // Drag and drop events
    const dropZone = document.getElementById('dropZone');
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = '#3a86ff';
            dropZone.style.background = 'rgba(58, 134, 255, 0.1)';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.borderColor = 'rgba(59, 130, 246, 0.4)';
            dropZone.style.background = 'rgba(14, 23, 46, 0.6)';
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            const input = document.getElementById('importFileInput');
            input.files = files;
            handleFileSelected(input);
        }
    }, false);

    // Form submit loading state
    document.getElementById('formImportStudents').addEventListener('submit', function() {
        const submitBtn = document.getElementById('btnSubmitImport');
        const btnText = document.getElementById('btnSubmitImportText');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';
        btnText.textContent = 'Memproses Impor...';
    });

    // Close import modal on click outside
    document.getElementById('importModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImportModal();
        }
    });

    // Delete All Modal Functions
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
            closeImportModal();
        }
    });

    // Reset filter form
    function resetStudentFilter(event) {
        if (event) event.preventDefault();
        const searchInput = document.getElementById('filterSearch');
        const classSelect = document.getElementById('filterClass');
        const genderSelect = document.getElementById('filterGender');

        if (searchInput) searchInput.value = '';
        if (classSelect) classSelect.selectedIndex = 0;
        if (genderSelect) genderSelect.selectedIndex = 0;

        window.location.href = "{{ route('students.index') }}";
    }
</script>
@endpush
@endsection
