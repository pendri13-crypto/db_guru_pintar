<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TeachingModule;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class ModuleAiController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        $modules = TeachingModule::with('subject')->latest()->paginate(9);
        return view('modules_ai.index', compact('modules', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('modules_ai.create', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|string',
            'phase' => 'required|string',
            'topic' => 'required|string|max:255',
            'total_meetings' => 'required|integer|min:1|max:5',
            'learning_model' => 'nullable|string',
            'pancasila_profiles' => 'nullable|array',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $topic = $request->topic;
        $totalMeetings = (int)$request->total_meetings;
        $profiles = $request->filled('pancasila_profiles') ? implode(', ', $request->pancasila_profiles) : 'Bernalar Kritis, Kreatif, Gotong Royong, Mandiri';

        // Deep Learning Scenario Generator Engine
        $goals = "1. Peserta didik mampu memahami dan menganalisis konsep inti {$topic} secara mendalam melalui pendekatan Mindful Learning.\n"
               . "2. Peserta didik mampu menghubungkan konsep {$topic} dengan fenomena kontekstual nyata melalui Meaningful Learning.\n"
               . "3. Peserta didik mampu mengekspresikan pemahaman, menciptakan solusi/karya, dan berkolaborasi dalam suasana Joyful Learning.";

        $diagnosticTable = [
            [
                'kategori' => 'Paham Utuh (High Mastery)',
                'ciri' => "Mampu menjelaskan konsep {$topic}, menganalisis studi kasus mandiri, dan memberikan contoh inovatif.",
                'tindak_lanjut' => 'Diberikan tantangan pengayaan berbasis proyek nyata (HOTS Level C5-C6) sebagai tutor sebaya.'
            ],
            [
                'kategori' => 'Paham Sebagian (Moderate Mastery)',
                'ciri' => "Memahami definisi dasar {$topic} namun masih memerlukan bimbingan dalam pengaplikasian studi kasus rumit.",
                'tindak_lanjut' => 'Diberikan latihan berjenjang (scaffolding) dan diskusi kelompok terbimbing.'
            ],
            [
                'kategori' => 'Belum Paham (Needs Support)',
                'ciri' => "Belum menguasai prasyarat dasar dan konsep fundamental mengenai {$topic}.",
                'tindak_lanjut' => 'Pendampingan langsung intensif oleh guru dengan analogi visual/konkret dan materi pengantar sederhana.'
            ]
        ];

        $scenarios = [];
        $themes = [
            1 => ['fokus' => 'Mindful Exploration & Apersepsi Kontekstual', 'sub' => 'Membangun rasa ingin tahu dan kesadaran penuh terhadap urgensi materi.'],
            2 => ['fokus' => 'Meaningful Investigation & Deep Analysis', 'sub' => 'Eksplorasi data, pengujian hipotesis, dan pembedahan masalah kontekstual.'],
            3 => ['fokus' => 'Joyful Collaboration & Hands-on Experiment', 'sub' => 'Praktik kolaboratif, manipulasi alat/media, dan simulasi interaktif yang menyenangkan.'],
            4 => ['fokus' => 'Creation & Critical Problem Solving', 'sub' => 'Merancang produk/solusi, penulisan laporan karya, dan validasi hasil.'],
            5 => ['fokus' => 'Showcase, Authentic Assessment & Metacognitive Reflection', 'sub' => 'Gelar karya (Gallery Walk), umpan balik konstruktif, dan perayaan capaian belajar.']
        ];

        for ($m = 1; $m <= $totalMeetings; $m++) {
            $t = $themes[$m] ?? ['fokus' => "Pendalaman Materi Pertemuan {$m}", 'sub' => 'Aktivitas belajar lanjutan'];
            $scenarios[] = [
                'pertemuan' => $m,
                'topik' => "Pertemuan {$m}: {$t['fokus']} - {$topic}",
                'durasi' => '2 x 40 Menit (80 JP)',
                'pendahuluan' => "• Guru membuka dengan salam hangat, doa bersama, dan mindfulness breathing 2 menit untuk memusatkan fokus belajar peserta didik.\n• Apersepsi kontekstual: Guru menyajikan pemantik visual/video terkait topik {$topic}.\n• Pertanyaan Pemantik: \"Bagaimana konsep ini dapat mempermudah kehidupan kita sehari-hari?\"",
                'kegiatan_inti' => "• Sintaks Deep Learning: {$t['sub']}.\n• Peserta didik bekerja dalam kelompok heterogen 4-5 orang menyelesaikan lembar penyelidikan.\n• Guru berkeliling melakukan observasi formatif, memberikan bimbingan diferensiasi (scaffolding) bagi kelompok yang membutuhkan bantuan.",
                'penutup' => "• Peserta didik menyimpulkan 1 kata kunci dan 1 makna terpenting yang diperoleh hari ini.\n• Joyful reflection: Penilaian diri menggunakan emoji board.\n• Guru menyampaikan apresiasi dan sekilas rencana seru untuk pertemuan berikutnya."
            ];
        }

        $assessmentRubric = [
            [
                'aspek' => 'Pemahaman Konseptual',
                'skor_4' => "Menjelaskan konsep {$topic} dengan sangat akurat, terstruktur, dan mampu memberikan analogi mandiri.",
                'skor_3' => "Menjelaskan konsep dengan benar disertai contoh relevan.",
                'skor_2' => "Memahami sebagian konsep namun penjelasan belum lengkap.",
                'skor_1' => "Belum mampu menjelaskan konsep utama secara tepat."
            ],
            [
                'aspek' => 'Penerapan & Keterampilan Analisis',
                'skor_4' => 'Mampu memecahkan masalah kompleks dengan solusi inovatif dan logis.',
                'skor_3' => 'Mampu menyelesaikan tugas sesuai instruksi dan langkah kerja dengan benar.',
                'skor_2' => 'Memerlukan bantuan parsial untuk menyelesaikan tugas utama.',
                'skor_1' => 'Memerlukan pendampingan intensif penuh.'
            ],
            [
                'aspek' => 'Kolaborasi & Karakter Pelajar Pancasila',
                'skor_4' => 'Sangat aktif berkomunikasi, menghargai pendapat, dan memimpin inisiatif kelompok.',
                'skor_3' => 'Aktif berkontribusi positif dalam kerja tim.',
                'skor_2' => 'Cukup kooperatif namun pasif dalam mengemukakan pendapat.',
                'skor_1' => 'Kurang terlibat dalam dinamika kelompok.'
            ]
        ];

        $lkpd = [
            'judul' => "LEMBAR KERJA PESERTA DIDIK (LKPD) INTERAKTIF - {$topic}",
            'petunjuk' => 'Diskusikan bersama kelompok Anda. Gunakan nalar kritis dan kreativitas dalam menyelesaikan setiap tahapan misi di bawah ini.',
            'tugas' => [
                "Misi 1 (Eksplorasi): Identifikasi 3 tantangan utama di lingkungan sekitar yang berkaitan dengan {$topic}.",
                "Misi 2 (Analisis Data): Rumuskan langkah-langkah solusi konkret dan buat skema diagram alirnya.",
                "Misi 3 (Kreasi Solusi): Rancang produk/karya nyata sederhana sebagai solusi dari topik bahasan.",
                "Misi 4 (Refleksi Mendalam): Apa hal paling berkesan dan paling menantang yang Anda pelajari dalam proses ini?"
            ]
        ];

        $module = TeachingModule::create([
            'subject_id' => $subject->id,
            'title' => "Modul Ajar Deep Learning: {$topic}",
            'grade_level' => $request->grade_level,
            'phase' => $request->phase,
            'topic' => $topic,
            'total_meetings' => $totalMeetings,
            'pancasila_profile' => $profiles,
            'learning_goals' => $goals,
            'diagnostic_table' => json_encode($diagnosticTable, JSON_UNESCAPED_UNICODE),
            'scenario_content' => json_encode($scenarios, JSON_UNESCAPED_UNICODE),
            'assessment_rubric' => json_encode($assessmentRubric, JSON_UNESCAPED_UNICODE),
            'lkpd_content' => json_encode($lkpd, JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()->route('modules-ai.show', $module->id)
            ->with('success', 'Modul Ajar Deep Learning Kurikulum Merdeka berhasil digenerate secara otomatis!');
    }

    public function show(TeachingModule $modules_ai)
    {
        $module = $modules_ai->load('subject');
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        $diagnosticTable = json_decode($module->diagnostic_table, true) ?? [];
        $scenarios = json_decode($module->scenario_content, true) ?? [];
        $assessmentRubric = json_decode($module->assessment_rubric, true) ?? [];
        $lkpd = json_decode($module->lkpd_content, true) ?? [];

        return view('modules_ai.show', compact('module', 'setting', 'diagnosticTable', 'scenarios', 'assessmentRubric', 'lkpd'));
    }

    public function print(TeachingModule $modules_ai)
    {
        $module = $modules_ai->load('subject');
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        $diagnosticTable = json_decode($module->diagnostic_table, true) ?? [];
        $scenarios = json_decode($module->scenario_content, true) ?? [];
        $assessmentRubric = json_decode($module->assessment_rubric, true) ?? [];
        $lkpd = json_decode($module->lkpd_content, true) ?? [];

        return view('modules_ai.print', compact('module', 'setting', 'diagnosticTable', 'scenarios', 'assessmentRubric', 'lkpd'));
    }

    public function destroy(TeachingModule $modules_ai)
    {
        $modules_ai->delete();
        return redirect()->route('modules-ai.index')->with('success', 'Modul ajar berhasil dihapus!');
    }
}
