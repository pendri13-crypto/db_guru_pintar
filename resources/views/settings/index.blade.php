@extends('layouts.app')

@section('title', 'Pengaturan & Profil Sekolah')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Pengaturan Profil Guru & Sekolah</h2>
        <p style="font-size: 13px; color: #475569;">Kelola identitas guru, NIP, nama sekolah, kepala sekolah, dan tahun ajaran aktif untuk cetak dokumen resmi</p>
    </div>

    <div class="card">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            
            <h3 style="font-size: 15px; font-weight: 800; color: #38bdf8; margin-bottom: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                1. Data Guru Pengampu
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Guru (dengan Gelar) *</label>
                    <input type="text" name="teacher_name" value="{{ $setting->teacher_name }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIP Guru</label>
                    <input type="text" name="teacher_nip" value="{{ $setting->teacher_nip }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mata Pelajaran Utama Diampu *</label>
                <input type="text" name="teacher_subject" value="{{ $setting->teacher_subject }}" class="form-control" required>
            </div>

            <h3 style="font-size: 15px; font-weight: 800; color: #a78bfa; margin-top: 18px; margin-bottom: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                2. Data Satuan Pendidikan / Sekolah
            </h3>

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Sekolah / Instansi *</label>
                    <input type="text" name="school_name" value="{{ $setting->school_name }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NPSN *</label>
                    <input type="text" name="npsn" value="{{ $setting->npsn }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap Sekolah *</label>
                <textarea name="address" rows="2" class="form-control" required>{{ $setting->address }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nama Kepala Sekolah *</label>
                    <input type="text" name="principal_name" value="{{ $setting->principal_name }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="{{ $setting->principal_nip }}" class="form-control">
                </div>
            </div>

            <h3 style="font-size: 15px; font-weight: 800; color: #f59e0b; margin-top: 18px; margin-bottom: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                3. Kalender Akademik Aktif
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran *</label>
                    <input type="text" name="academic_year" value="{{ $setting->academic_year }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester Berjalan *</label>
                    <select name="active_semester" class="form-select" required>
                        <option value="Ganjil" {{ $setting->active_semester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $setting->active_semester === 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 11px 28px;">
                    Simpan Pengaturan & Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
