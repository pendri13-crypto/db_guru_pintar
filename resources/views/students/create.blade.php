@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px;">Tambah Siswa Baru</h2>
            <p style="font-size: 13px; color: var(--text-dim);">Lengkapi informasi identitas siswa untuk penerbitan NISN & Kartu QR</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline">Kembali ke Daftar</a>
    </div>

    <div class="card">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Siswa *</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Muhammad Rizky Pratama" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas Terdaftar *</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">Kelas {{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">NISN (10 Digit) *</label>
                    <input type="text" name="nisn" class="form-control" placeholder="30891000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIS Lokal</label>
                    <input type="text" name="nis" class="form-control" placeholder="24001">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin *</label>
                    <select name="gender" class="form-select" required>
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">No. Telepon / WhatsApp Siswa</label>
                    <input type="text" name="phone" class="form-control" placeholder="08123456789">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon Orang Tua / Wali</label>
                    <input type="text" name="parent_phone" class="form-control" placeholder="08987654321">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Siswa</label>
                <input type="email" name="email" class="form-control" placeholder="siswa@sekolah.sch.id">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Domisili</label>
                <textarea name="address" rows="3" class="form-control" placeholder="Alamat lengkap tempat tinggal..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                <a href="{{ route('students.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
            </div>
        </form>
    </div>
</div>
@endsection
