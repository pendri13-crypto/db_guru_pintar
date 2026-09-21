@extends('layouts.app')

@section('title', 'Asisten Chatbot Guru AI')

@push('styles')
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        height: calc(100vh - 150px);
    }

    .chat-sidebar {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .prompt-chip {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 12px;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        display: block;
        width: 100%;
        margin-bottom: 8px;
    }

    .prompt-chip:hover {
        background: rgba(58, 134, 255, 0.12);
        border-color: var(--primary);
        color: #fff;
        transform: translateX(3px);
    }

    .chat-main {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        background: rgba(11, 19, 40, 0.6);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .message-row {
        display: flex;
        gap: 12px;
        max-width: 85%;
    }

    .message-row.user {
        margin-left: auto;
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-weight: 800;
        font-size: 13px;
    }

    .msg-avatar.ai { background: linear-gradient(135deg, #8b5cf6, #3a86ff); color: #fff; }
    .msg-avatar.user { background: #334155; color: #fff; border: 1px solid var(--primary); }

    .msg-bubble {
        padding: 14px 18px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.55;
    }

    .message-row.ai .msg-bubble {
        background: #0d172e;
        border: 1px solid var(--border-color);
        color: #e2e8f0;
    }

    .message-row.user .msg-bubble {
        background: var(--primary);
        color: #ffffff;
    }

    .chat-input-area {
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
        background: rgba(11, 19, 40, 0.8);
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    <!-- Sidebar Prompts & Mode -->
    <div class="chat-sidebar">
        <div>
            <div style="font-size: 14px; font-weight: 800; color: #fff; margin-bottom: 4px;">Konsultan Guru AI</div>
            <div style="font-size: 11px; color: var(--text-dim); margin-bottom: 16px;">Pilih template perintah instan:</div>

            <button type="button" class="prompt-chip" onclick="sendQuickPrompt('Buatkan paket 5 Soal HOTS (Pilihan Ganda & Uraian) beserta kunci jawaban & rubrik untuk materi Pemrograman Python kelas 8', 'hots_questions')">
                🎯 <strong>Paket Soal HOTS</strong><br>
                <span style="font-size: 10.5px; color: var(--text-dim);">Soal C4-C6 + Kunci & Rubrik</span>
            </button>

            <button type="button" class="prompt-chip" onclick="sendQuickPrompt('Tuliskan draf narasi deskripsi capaian rapor Kurikulum Merdeka untuk 3 kategori capaian siswa di mata pelajaran Informatika', 'report_narrative')">
                📝 <strong>Draf Narasi Rapor</strong><br>
                <span style="font-size: 10.5px; color: var(--text-dim);">Deskripsi TP Rapor Merdeka</span>
            </button>

            <button type="button" class="prompt-chip" onclick="sendQuickPrompt('Bagaimana cara menerapkan pendekatan Deep Learning (Mindful, Meaningful, Joyful Learning) pada topik Jaringan Komputer?', 'lesson_strategy')">
                💡 <strong>Strategi Deep Learning</strong><br>
                <span style="font-size: 10.5px; color: var(--text-dim);">Langkah pembelajaran bermakna</span>
            </button>

            <button type="button" class="prompt-chip" onclick="sendQuickPrompt('Bagaimana cara melakukan diferensiasi proses bagi siswa yang belum paham materi logika percabangan?', 'general')">
                📖 <strong>Diferensiasi Belajar</strong><br>
                <span style="font-size: 10.5px; color: var(--text-dim);">Scaffolding siswa lambat belajar</span>
            </button>
        </div>

        <form action="{{ route('chatbot.clear') }}" method="POST">
            @csrf
            <input type="hidden" name="session_id" value="{{ $sessionId }}">
            <button type="submit" class="btn btn-sm btn-outline" style="width: 100%; justify-content: center; font-size: 11px; color: #fb7185; border-color: rgba(244,63,94,0.3);">
                🗑️ Reset Obrolan
            </button>
        </form>
    </div>

    <!-- Chat Messages Viewport -->
    <div class="chat-main">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                <div>
                    <div style="font-size: 14px; font-weight: 800; color: #fff;">Asisten Chatbot Guru AI Pro</div>
                    <div style="font-size: 11px; color: var(--text-dim);">Pedagogical Assistant & Kurikulum Merdeka Expert</div>
                </div>
            </div>
            <span style="background: rgba(139, 92, 246, 0.2); color: #c084fc; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                AI Ready
            </span>
        </div>

        <div class="chat-messages-area" id="chatArea">
            <!-- Welcome Initial Message -->
            <div class="message-row ai">
                <div class="msg-avatar ai">AI</div>
                <div class="msg-bubble">
                    Halo Bapak/Ibu Guru! 🎓 Saya adalah <strong>Asisten Chatbot Guru AI</strong>. Ada yang bisa saya bantu hari ini? Anda bisa meminta saya menyusun <strong>Soal HOTS</strong>, membuat <strong>Draf Narasi Rapor</strong>, atau merancang strategi <strong>Deep Learning (Mindful, Meaningful, Joyful)</strong>.
                </div>
            </div>

            @foreach($messages as $msg)
                <div class="message-row {{ $msg->role === 'user' ? 'user' : 'ai' }}">
                    <div class="msg-avatar {{ $msg->role === 'user' ? 'user' : 'ai' }}">
                        {{ $msg->role === 'user' ? 'BS' : 'AI' }}
                    </div>
                    <div class="msg-bubble" style="white-space: pre-line;">
                        {{ $msg->message }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Input Box Form -->
        <div class="chat-input-area">
            <form id="chatForm" onsubmit="handleSend(event)" style="display: flex; gap: 10px;">
                <input type="text" id="userInput" class="form-control" placeholder="Tanyakan apapun seputar RPP, pedagogi, soal HOTS, atau narasi rapor..." autocomplete="off">
                <button type="submit" class="btn btn-yellow" style="padding: 10px 20px; flex-shrink: 0;" id="btnSend">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const sessionId = "{{ $sessionId }}";
    const chatArea = document.getElementById('chatArea');

    function scrollToBottom() {
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    function appendMessage(role, text) {
        const row = document.createElement('div');
        row.className = 'message-row ' + (role === 'user' ? 'user' : 'ai');
        row.innerHTML = `
            <div class="msg-avatar ${role === 'user' ? 'user' : 'ai'}">${role === 'user' ? 'BS' : 'AI'}</div>
            <div class="msg-bubble" style="white-space: pre-line;">${text}</div>
        `;
        chatArea.appendChild(row);
        scrollToBottom();
    }

    function sendQuickPrompt(promptText, mode = 'general') {
        document.getElementById('userInput').value = promptText;
        executeSend(promptText, mode);
    }

    function handleSend(e) {
        e.preventDefault();
        const input = document.getElementById('userInput');
        const text = input.value.trim();
        if (!text) return;
        executeSend(text, 'general');
    }

    function executeSend(text, mode = 'general') {
        const input = document.getElementById('userInput');
        input.value = '';
        appendMessage('user', text);

        const btn = document.getElementById('btnSend');
        btn.disabled = true;

        fetch("{{ route('chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                session_id: sessionId,
                message: text,
                mode: mode
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                appendMessage('ai', data.ai_response);
            }
        })
        .catch(err => {
            appendMessage('ai', 'Maaf, terjadi kendala komunikasi dengan AI server. Silakan coba sesaat lagi.');
        })
        .finally(() => {
            btn.disabled = false;
        });
    }

    document.addEventListener("DOMContentLoaded", scrollToBottom);
</script>
@endpush
@endsection
