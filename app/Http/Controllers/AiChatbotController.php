<?php
//tes
namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Subject;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiChatbotController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->get('session_id', session('ai_session_id', (string)Str::uuid()));
        session(['ai_session_id' => $sessionId]);

        $messages = ChatMessage::where('session_id', $sessionId)->orderBy('created_at')->get();
        $subjects = Subject::orderBy('name')->get();
        $setting = SchoolSetting::first() ?? new SchoolSetting();

        return view('chatbot.index', compact('messages', 'sessionId', 'subjects', 'setting'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'required|string',
            'mode' => 'nullable|string', // general, hots_questions, report_narrative, lesson_strategy
        ]);

        $sessionId = $request->session_id;
        $userMsg = trim($request->message);
        $mode = $request->mode ?? 'general';

        // Store user message
        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'message' => $userMsg,
        ]);

        // Generate intelligent AI response
        $aiResponse = $this->generatePedagogicalAiResponse($userMsg, $mode);

        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'message' => $aiResponse,
        ]);

        return response()->json([
            'success' => true,
            'user_message' => $userMsg,
            'ai_response' => $aiResponse,
        ]);
    }

    public function clearSession(Request $request)
    {
        $sessionId = $request->session_id ?? session('ai_session_id');
        if ($sessionId) {
            ChatMessage::where('session_id', $sessionId)->delete();
        }
        return redirect()->route('chatbot.index')->with('success', 'Riwayat percakapan asisten AI telah direset.');
    }

    private function generatePedagogicalAiResponse(string $msg, string $mode): string
    {
        $lower = strtolower($msg);

        // 1. Soal HOTS generator
        if ($mode === 'hots_questions' || str_contains($lower, 'soal') || str_contains($lower, 'hots') || str_contains($lower, 'kuis') || str_contains($lower, 'ujian')) {
            return "### 🎯 Paket Soal Asesmen HOTS (Higher Order Thinking Skills)\n\n"
                 . "Berikut rancangan paket soal berbasis stimulasi kontekstual level kognitif C4 (Menganalisis) hingga C6 (Mencipta) yang siap Anda gunakan:\n\n"
                 . "---\n\n"
                 . "#### 📌 **Soal 1: Pilihan Ganda Kompleks (Level C4 - Analisis)**\n"
                 . "**Stimulus:**\n"
                 . "*Sebuah instansi sekolah berencana menerapkan sistem presensi berbasis barcode. Namun pada hari pertama, terjadi antrean panjang karena kamera scanner lambat membaca kode yang rusak akibat lipatan kartu.*\n\n"
                 . "**Pertanyaan:** Langkah optimasi algoritma manakah yang paling efektif untuk mengatasi antrean tersebut tanpa menambah anggaran pengadaan alat baru?\n"
                 . "A. Mewajibkan siswa mengetik 10 digit NISN secara manual.\n"
                 . "B. Menerapkan algoritma pre-caching data lokal dan toleransi error koreksi QR Code (Error Correction Level H).\n"
                 . "C. Mematikan fitur verifikasi identitas di aplikasi.\n"
                 . "D. Mengganti semua kartu dengan sistem absensi kertas manual.\n\n"
                 . "**Kunci Jawaban:** **B**\n"
                 . "**Pembahasan & Rubrik:** Pilihan B mengoptimalkan kinerja pembacaan citra dan mengurangi latensi jaringan tanpa biaya perangkat keras tambahan.\n\n"
                 . "---\n\n"
                 . "#### 📌 **Soal 2: Uraian Studi Kasus (Level C5 & C6 - Evaluasi & Kreasi)**\n"
                 . "**Pertanyaan:**\n"
                 . "Rancanglah 1 buah diagram alir (flowchart) atau pseudocode penanganan darurat (fail-safe) jika server presensi pusat tiba-tiba terputus dari koneksi internet selama jam masuk sekolah!\n\n"
                 . "**Rubrik Penilaian:**\n"
                 . "• **Skor 4 (Maksimal):** Mencantumkan alur deteksi *offline*, penyimpanan *Local Storage/SQLite Cache*, dan sinkronisasi otomatis (*background sync*) saat jaringan kembali aktif.\n"
                 . "• **Skor 2-3:** Alur benar namun belum mempertimbangkan redundansi data ganda (*duplicate entry*).\n"
                 . "• **Skor 1:** Alur tidak menyelesaikan kendala konektivitas.";
        }

        // 2. Draf Narasi Rapor Kurikulum Merdeka
        if ($mode === 'report_narrative' || str_contains($lower, 'rapor') || str_contains($lower, 'narasi') || str_contains($lower, 'deskripsi') || str_contains($lower, 'capaian')) {
            return "### 📝 Rekomendasi Narasi Deskripsi Capaian Rapor (Kurikulum Merdeka)\n\n"
                 . "Berikut adalah 3 varian deskripsi capaian pembelajaran yang terpersonalisasi untuk buku rapor:\n\n"
                 . "1. **Kategori Sangat Mahir (Skor 88 - 100):**\n"
                 . "   > *\"Menunjukkan penguasaan yang sangat istimewa dalam menganalisis permasalahan logika berpikir komputasional, sangat terampil merancang algoritma terstruktur secara mandiri, serta aktif memimpin kolaborasi tim dalam pemecahan proyek berbasis teknologi.\"*\n\n"
                 . "2. **Kategori Berkembang Sesuai Harapan (Skor 75 - 87):**\n"
                 . "   > *\"Menunjukkan pemahaman yang baik dalam memahami konsep dasar informatika dan implementasi flowchart, mampu menyelesaikan tugas praktikum tepat waktu, serta perlu sedikit pendalaman dalam optimasi algoritma yang lebih kompleks.\"*\n\n"
                 . "3. **Kategori Perlu Bimbingan (Skor < 75):**\n"
                 . "   > *\"Mampu mengenali perangkat keras dan fungsi dasar aplikasi dengan bimbingan. Perlu penguatan secara berkala pada pemahaman logika percabangan kondisional dan pembiasaan latihan algoritma mandiri.\"*";
        }

        // 3. Strategi Pembelajaran Deep Learning
        if ($mode === 'lesson_strategy' || str_contains($lower, 'strategi') || str_contains($lower, 'metode') || str_contains($lower, 'deep learning') || str_contains($lower, 'rpp')) {
            return "### 💡 Rekomendasi Strategi Deep Learning (Mindful, Meaningful, Joyful)\n\n"
                 . "Untuk materi yang Anda tanyakan, berikut langkah implementasi pedagogis yang paling efektif di kelas:\n\n"
                 . "1. **Mindful Learning (20 Menit Awal):**\n"
                 . "   - Mulai dengan *Silent Reflection* 2 menit: Ajak siswa mengamati contoh nyata di sekitar mereka.\n"
                 . "   - Hindari langsung memberi rumus/kode. Berikan teka-teki logika pemantik (*hook inquiry*).\n\n"
                 . "2. **Meaningful Learning (45 Menit Inti):**\n"
                 . "   - *Contextual Challenge*: Berikan studi kasus yang relevan dengan kehidupan remaja saat ini.\n"
                 . "   - *Scaffolding*: Berikan kebebasan memilih cara penyelesaian (visual diagram, narasi, atau pseudocode).\n\n"
                 . "3. **Joyful Learning (15 Menit Penutup):**\n"
                 . "   - Adakan *Peer Showcase* atau *Quick Micro-Quiz* berbasis gamifikasi.\n"
                 . "   - Tutup dengan apresiasi spesifik terhadap proses berpikir siswa, bukan sekadar hasil akhir.";
        }

        // General AI Pedagogical Consultant
        return "### 🤖 Asisten Konsultan Guru AI\n\n"
             . "Terima kasih atas pertanyaannya. Berdasarkan prinsip **Kurikulum Merdeka** dan **Pendekatan Deep Learning**, berikut poin analisis dan rekomendasi praktis untuk Anda:\n\n"
             . "1. **Fokus pada Esensi Kompetensi:** Prioritaskan kedalaman pemahaman (*Depth of Knowledge*) dibandingkan mengejar banyaknya materi secara terburu-buru.\n"
             . "2. **Pembelajaran Berdiferensiasi:** Manfaatkan hasil asesmen awal (diagnostik) untuk memetakan kelompok siswa (Paham Utuh, Paham Sebagian, dan Perlu Bimbingan).\n"
             . "3. **Asesmen Formatif Berkelanjutan:** Gunakan teknik *Exit Ticket* atau *One Minute Paper* di akhir sesi untuk mengukur pemahaman siswa secara nyata tanpa membuat mereka tertekan.\n\n"
             . "💡 *Tips: Anda juga dapat menggunakan mode tombol pintas di bawah untuk langsung membuat Paket Soal HOTS, Draf Narasi Rapor, atau Skenario Modul Ajar.*";
    }
}
