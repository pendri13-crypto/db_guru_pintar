@extends('layouts.app')

@section('title', 'Input Penilaian & Leger')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Input Penilaian & Leger Siswa</h2>
        <p style="font-size: 13px; color: #475569;">Rekap nilai harian, formatif, sumatif, UTS, UAS, dan kalkulasi predikat otomatis</p>
    </div>
    @if($selectedClassId && $selectedSubjectId)
        <a href="{{ route('reports.printGrades', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId, 'semester' => $selectedSemester]) }}" target="_blank" class="btn btn-outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
            </svg>
            Cetak Leger Nilai PDF
        </a>
    @endif
</div>

<!-- Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
    <div class="card" style="padding: 16px 20px;">
        <span style="font-size: 11px; color: var(--text-dim); font-weight: 700;">TOTAL SISWA DINILAI</span>
        <div style="font-size: 22px; font-weight: 800; color: #0369a1; margin-top: 4px;">{{ count($students) }} Siswa</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #f59e0b;">
        <span style="font-size: 11px; color: #f59e0b; font-weight: 700;">RATA-RATA KELAS</span>
        <div style="font-size: 22px; font-weight: 800; color: #f59e0b; margin-top: 4px;">{{ $avgScore }} / 100</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #10b981;">
        <span style="font-size: 11px; color: #34d399; font-weight: 700;">NILAI TERTINGGI</span>
        <div style="font-size: 22px; font-weight: 800; color: #34d399; margin-top: 4px;">{{ $highestScore }}</div>
    </div>
    <div class="card" style="padding: 16px 20px; border-left: 3px solid #f43f5e;">
        <span style="font-size: 11px; color: #fb7185; font-weight: 700;">NILAI TERENDAH</span>
        <div style="font-size: 22px; font-weight: 800; color: #fb7185; margin-top: 4px;">{{ $lowestScore }}</div>
    </div>
</div>

<!-- Selectors Filter -->
<div class="card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" action="{{ route('grades.index') }}" style="display: grid; grid-template-columns: 1.5fr 1.5fr 1fr auto; gap: 14px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Pilih Kelas</label>
            <select name="class_id" class="form-select" onchange="this.form.submit()">
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }} ({{ $c->students->count() }} Siswa)</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Mata Pelajaran</label>
            <select name="subject_id" class="form-select" onchange="this.form.submit()">
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" onchange="this.form.submit()">
                <option value="Ganjil" {{ $selectedSemester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ $selectedSemester === 'Genap' ? 'selected' : '' }}>Genap</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 42px;">Muat Nilai</button>
    </form>
</div>

<!-- Grades Table Form -->
<div class="card">
    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
        <input type="hidden" name="semester" value="{{ $selectedSemester }}">

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>NISN & Nama Siswa</th>
                        <th style="width: 80px;">Tugas 1 (15%)</th>
                        <th style="width: 80px;">Tugas 2 (15%)</th>
                        <th style="width: 80px;">Formatif (20%)</th>
                        <th style="width: 80px;">Sumatif (20%)</th>
                        <th style="width: 80px;">UTS (15%)</th>
                        <th style="width: 80px;">UAS (15%)</th>
                        <th style="width: 80px; text-align: center;">Nilai Akhir</th>
                        <th style="width: 60px; text-align: center;">Predikat</th>
                        <th>Catatan Capaian Belajar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $idx => $st)
                        @php
                            $g = $grades[$st->id] ?? null;
                        @endphp
                        <tr id="row_{{ $st->id }}">
                            <td style="color: var(--text-dim);">{{ $idx + 1 }}</td>
                            <td>
                                <div style="font-weight: 700; color: var(--title-color);">{{ $st->name }}</div>
                                <div style="font-size: 11px; color: #60a5fa;">{{ $st->nisn }}</div>
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][tugas_1]" value="{{ $g->tugas_1 ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][tugas_2]" value="{{ $g->tugas_2 ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][formatif]" value="{{ $g->formatif ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][sumatif]" value="{{ $g->sumatif ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][uts]" value="{{ $g->uts ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" max="100" name="grades[{{ $st->id }}][uas]" value="{{ $g->uas ?? '' }}" class="form-control score-input" data-student="{{ $st->id }}" style="padding: 6px; text-align: center;">
                            </td>
                            <td style="text-align: center;">
                                <span id="final_{{ $st->id }}" style="font-size: 14px; font-weight: 800; color: #f59e0b;">
                                    {{ $g->final_score ?? '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span id="pred_{{ $st->id }}" style="font-size: 13px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: rgba(58, 134, 255, 0.15); color: #60a5fa;">
                                    {{ $g->predicate ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <input type="text" name="grades[{{ $st->id }}][notes]" value="{{ $g->notes ?? '' }}" placeholder="Catatan kemajuan capaian..." class="form-control" style="padding: 6px 10px; font-size: 12px;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: center; padding: 32px; color: var(--text-dim);">
                                Silakan pilih kelas dan mata pelajaran untuk mengolah penilaian siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($students) > 0)
            <div style="display: flex; justify-content: flex-end; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary" style="padding: 11px 28px;">
                    Simpan Seluruh Nilai & Leger
                </button>
            </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    // Live calculation of final score and predicate on typing
    document.querySelectorAll('.score-input').forEach(input => {
        input.addEventListener('input', function() {
            const stuId = this.getAttribute('data-student');
            const row = document.getElementById('row_' + stuId);
            const inputs = row.querySelectorAll('.score-input');

            const t1 = parseFloat(inputs[0].value) || 0;
            const t2 = parseFloat(inputs[1].value) || 0;
            const f  = parseFloat(inputs[2].value) || 0;
            const s  = parseFloat(inputs[3].value) || 0;
            const uts = parseFloat(inputs[4].value) || 0;
            const uas = parseFloat(inputs[5].value) || 0;

            const final = ((t1 * 0.15) + (t2 * 0.15) + (f * 0.20) + (s * 0.20) + (uts * 0.15) + (uas * 0.15)).toFixed(1);
            let pred = 'D';
            if (final >= 88) pred = 'A';
            else if (final >= 75) pred = 'B';
            else if (final >= 60) pred = 'C';

            document.getElementById('final_' + stuId).textContent = final;
            document.getElementById('pred_' + stuId).textContent = pred;
        });
    });
</script>
@endpush
@endsection
