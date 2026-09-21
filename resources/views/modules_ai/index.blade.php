@extends('layouts.app')

@section('title', 'Modul Ajar Deep Learning AI')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Modul Ajar Deep Learning AI</h2>
        <p style="font-size: 13px; color: #475569;">Generator perangkat ajar Kurikulum Merdeka otomatis (1-5 Pertemuan) lengkap dengan tabel diagnostik, rubrik, dan LKPD</p>
    </div>
    <a href="{{ route('modules-ai.create') }}" class="btn btn-yellow">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
        </svg>
        Buat Modul Ajar AI Baru
    </a>
</div>

<!-- Modules Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    @forelse($modules as $m)
        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; border-color: rgba(139, 92, 246, 0.3);">
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span style="background: rgba(139, 92, 246, 0.15); color: #c084fc; font-weight: 800; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                        {{ $m->phase }} • Kelas {{ $m->grade_level }}
                    </span>
                    <span style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                        {{ $m->total_meetings }} Pertemuan
                    </span>
                </div>

                <h3 style="font-size: 16px; font-weight: 800; color: #fff; margin-bottom: 8px; line-height: 1.35;">
                    {{ $m->title }}
                </h3>

                <div style="font-size: 12px; color: #38bdf8; font-weight: 600; margin-bottom: 8px;">
                    Mapel: {{ $m->subject->name }}
                </div>

                <p style="font-size: 12px; color: var(--text-muted); line-height: 1.45; margin-bottom: 12px;">
                    <strong>Profil Pancasila:</strong> {{ $m->pancasila_profile }}
                </p>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--border-color);">
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('modules-ai.show', $m->id) }}" class="btn btn-sm btn-primary">
                        Buka Detail & LKPD
                    </a>
                    <a href="{{ route('modules-ai.print', $m->id) }}" target="_blank" class="btn btn-sm btn-outline" title="Cetak Dokumen PDF">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                        </svg>
                    </a>
                </div>
                <form action="{{ route('modules-ai.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus modul ajar ini?')" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline" style="color: #fb7185; border-color: rgba(244, 63, 94, 0.3);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 48px;">
            <div style="font-size: 40px; margin-bottom: 12px;">✨</div>
            <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 6px;">Belum Ada Modul Ajar AI</h3>
            <p style="font-size: 13px; color: var(--text-dim); max-width: 450px; margin: 0 auto 18px;">
                Gunakan Generator Deep Learning AI untuk membuat RPP Kurikulum Merdeka secara instan hingga 5 pertemuan.
            </p>
            <a href="{{ route('modules-ai.create') }}" class="btn btn-yellow">Mulai Buat Modul Sekarang</a>
        </div>
    @endforelse
</div>

<div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid rgba(30, 44, 79, 0.6); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div style="font-size: 13px; color: var(--text-dim);">
        Menampilkan <strong style="color: #fff;">{{ $modules->firstItem() ?? 0 }}</strong> - <strong style="color: #fff;">{{ $modules->lastItem() ?? 0 }}</strong> dari <strong style="color: #fff;">{{ $modules->total() }}</strong> modul
    </div>
    <div>
        {{ $modules->links('pagination.custom') }}
    </div>
</div>
@endsection
