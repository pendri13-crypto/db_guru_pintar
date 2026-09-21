<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = SchoolSetting::first() ?? SchoolSetting::create([
            'school_name' => 'SMP NEGERI UNGGULAN INDONESIA',
            'npsn' => '20109988',
            'address' => 'Jl. Pendidikan Nasional No. 45, Jakarta',
            'principal_name' => 'Dr. H. Ahmad Dahlan, M.Pd',
            'principal_nip' => '19750512 199903 1 002',
            'teacher_name' => 'Budi Santoso, S.Kom., M.Kom',
            'teacher_nip' => '19880415 201201 1 004',
            'teacher_subject' => 'Informatika',
            'academic_year' => '2025/2026',
            'active_semester' => 'Ganjil',
        ]);

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'npsn' => 'required|string|max:50',
            'address' => 'required|string',
            'principal_name' => 'required|string|max:255',
            'principal_nip' => 'nullable|string|max:100',
            'teacher_name' => 'required|string|max:255',
            'teacher_nip' => 'nullable|string|max:100',
            'teacher_subject' => 'required|string|max:100',
            'academic_year' => 'required|string|max:50',
            'active_semester' => 'required|string|max:50',
        ]);

        $setting = SchoolSetting::first() ?? new SchoolSetting();
        $setting->fill($request->all());
        $setting->save();

        return redirect()->route('settings.index')->with('success', 'Profil dan pengaturan sekolah berhasil diperbarui!');
    }
}
