<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\GuidanceController;
use App\Http\Controllers\ModuleAiController;
use App\Http\Controllers\AiChatbotController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Kelola Kelas
Route::resource('classes', \App\Http\Controllers\SchoolClassController::class)->except(['create', 'show', 'edit']);

// Master Data Siswa
Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
Route::get('students/template', [StudentController::class, 'template'])->name('students.template');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
Route::delete('students/destroy-all', [StudentController::class, 'destroyAll'])->name('students.destroyAll');
Route::resource('students', StudentController::class);

// Cetak Kartu Siswa & QR Code
Route::get('cards', [CardController::class, 'index'])->name('cards.index');
Route::get('cards/print', [CardController::class, 'print'])->name('cards.print');

// Kelola Mapel
Route::resource('subjects', SubjectController::class)->except(['create', 'show', 'edit']);

// Jadwal Mengajar
Route::resource('schedules', ScheduleController::class)->except(['create', 'show', 'edit']);

// Presensi & Scan QR Code
Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('attendance/manual', [AttendanceController::class, 'storeManual'])->name('attendance.storeManual');
Route::get('attendance/scanner', [AttendanceController::class, 'scanner'])->name('attendance.scanner');
Route::post('attendance/scan-process', [AttendanceController::class, 'scanProcess'])->name('attendance.scanProcess');

// Penilaian & Leger
Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
Route::post('grades/save', [GradeController::class, 'store'])->name('grades.store');

// Agenda Mengajar / Jurnal KBM
Route::resource('journals', JournalController::class)->except(['create', 'show', 'edit']);

// Bimbingan Guru Wali
Route::resource('guidance', GuidanceController::class)->except(['create', 'show', 'edit']);

// Modul Ajar Deep Learning AI (RPP 5 Pertemuan)
Route::resource('modules-ai', ModuleAiController::class)->names([
    'index' => 'modules-ai.index',
    'create' => 'modules-ai.create',
    'show' => 'modules-ai.show',
    'destroy' => 'modules-ai.destroy',
]);
Route::post('modules-ai/generate', [ModuleAiController::class, 'generate'])->name('modules-ai.generate');
Route::get('modules-ai/{modules_ai}/print', [ModuleAiController::class, 'print'])->name('modules-ai.print');

// Asisten Chatbot Guru AI
Route::get('chatbot', [AiChatbotController::class, 'index'])->name('chatbot.index');
Route::post('chatbot/send', [AiChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::post('chatbot/clear', [AiChatbotController::class, 'clearSession'])->name('chatbot.clear');

// Pusat Laporan PDF & Dokumen
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/print-attendance', [ReportController::class, 'printAttendance'])->name('reports.printAttendance');
Route::get('reports/print-grades', [ReportController::class, 'printGrades'])->name('reports.printGrades');
Route::get('reports/print-journals', [ReportController::class, 'printJournals'])->name('reports.printJournals');

// Pengaturan & Profil
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');
