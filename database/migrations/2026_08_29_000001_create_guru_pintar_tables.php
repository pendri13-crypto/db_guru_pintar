<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Classes / Kelas
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 8C, 8K, 8D, 7A, 9B
            $table->string('level')->default('8'); // 7, 8, 9, 10, 11, 12
            $table->string('academic_year')->default('2025/2026');
            $table->string('homeroom_teacher')->nullable();
            $table->timestamps();
        });

        // 2. Subjects / Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name'); // INFORMATIKA, MATEMATIKA, dll
            $table->string('category')->default('Wajib'); // Wajib / Pilihan
            $table->integer('weekly_hours')->default(3);
            $table->string('semester')->default('Ganjil');
            $table->timestamps();
        });

        // 3. Students / Siswa
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('nis')->nullable();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('phone')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('qr_code')->unique();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['Aktif', 'Mutasi', 'Lulus'])->default('Aktif');
            $table->timestamps();
        });

        // 4. Schedules / Jadwal Mengajar
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->string('start_time'); // e.g. 08:30
            $table->string('end_time'); // e.g. 09:50
            $table->string('room')->default('Ruang Kelas');
            $table->timestamps();
        });

        // 5. Attendances / Presensi Siswa
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->date('date');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir');
            $table->time('check_in_time')->nullable();
            $table->string('method')->default('Manual'); // Manual, QR Scan
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });

        // 6. Grades / Penilaian & Leger
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('semester')->default('Ganjil');
            $table->float('tugas_1')->nullable();
            $table->float('tugas_2')->nullable();
            $table->float('formatif')->nullable();
            $table->float('sumatif')->nullable();
            $table->float('uts')->nullable();
            $table->float('uas')->nullable();
            $table->float('final_score')->nullable();
            $table->string('predicate', 2)->nullable(); // A, B, C, D
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'semester']);
        });

        // 7. Teaching Journals / Agenda Mengajar Guru (Jurnal KBM)
        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->date('date');
            $table->integer('meeting_number')->default(1);
            $table->string('topic');
            $table->text('learning_objective');
            $table->text('activities')->nullable();
            $table->boolean('is_completed')->default(true);
            $table->integer('total_present')->default(0);
            $table->integer('total_absent')->default(0);
            $table->text('obstacle_solution')->nullable();
            $table->timestamps();
        });

        // 8. Guidances / Bimbingan Guru Wali
        Schema::create('guidances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['Konseling', 'Apresiasi', 'Pelanggaran', 'Panggilan Ortu'])->default('Konseling');
            $table->string('title');
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->text('parent_followup')->nullable();
            $table->enum('status', ['Selesai', 'Dalam Proses', 'Perlu Pemantauan'])->default('Selesai');
            $table->timestamps();
        });

        // 9. Teaching Modules / Modul Ajar Deep Learning AI
        Schema::create('teaching_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('title');
            $table->string('grade_level')->default('8');
            $table->string('phase')->default('Fase D');
            $table->string('topic');
            $table->integer('total_meetings')->default(3); // 1-5 pertemuan
            $table->text('pancasila_profile')->nullable();
            $table->text('learning_goals')->nullable();
            $table->longText('diagnostic_table')->nullable();
            $table->longText('scenario_content')->nullable(); // Skenario kegiatan per pertemuan
            $table->longText('assessment_rubric')->nullable(); // Rubrik asesmen
            $table->longText('lkpd_content')->nullable(); // LKPD interaktif
            $table->timestamps();
        });

        // 10. Chat Messages / Asisten Chatbot Guru AI
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('message');
            $table->timestamps();
        });

        // 11. School Settings / Pengaturan & Profil
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMP NEGERI UNGGULAN INDONESIA');
            $table->string('npsn')->default('20109988');
            $table->text('address')->nullable();
            $table->string('principal_name')->default('Dr. H. Ahmad Dahlan, M.Pd');
            $table->string('principal_nip')->default('19750512 199903 1 002');
            $table->string('teacher_name')->default('Budi Santoso, S.Kom., M.Kom');
            $table->string('teacher_nip')->default('19880415 201201 1 004');
            $table->string('teacher_subject')->default('Informatika');
            $table->string('academic_year')->default('2025/2026');
            $table->string('active_semester')->default('Ganjil');
            $table->text('signature_image')->nullable();
            $table->text('school_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('teaching_modules');
        Schema::dropIfExists('guidances');
        Schema::dropIfExists('teaching_journals');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classes');
    }
};
