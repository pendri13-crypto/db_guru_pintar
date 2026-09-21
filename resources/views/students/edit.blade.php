@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px;">Edit Data Siswa</h2>
            <p style="font-size: 13px; color: var(--text-dim);">Perbarui informasi data siswa: {{ $student->name }}</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline">Kembali ke Daftar</a>
    </div>

    <div class="card">
        <form action="{{ route('students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Siswa *</label>
                    <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas Terdaftar *</label>
                    <select name="class_id" class="form-select" required>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ $student->class_id == $cls->id ? 'selected' : '' }}>Kelas {{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">NISN *</label>
                    <input type="text" name="nisn" value="{{ $student->nisn }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" value="{{ $student->nis }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin *</label>
                    <select name="gender" class="form-select" required>
                        <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Siswa *</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif" {{ $student->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Mutasi" {{ $student->status == 'Mutasi' ? 'selected' : '' }}>Mutasi</option>
                        <option value="Lulus" {{ $student->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">No. Telepon Siswa</label>
                    <input type="text" name="phone" value="{{ $student->phone }}" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon Orang Tua</label>
                    <input type="text" name="parent_phone" value="{{ $student->parent_phone }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Siswa</label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Domisili</label>
                <textarea name="address" rows="3" class="form-control">{{ $student->address }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <a href="{{ route('students.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
