@extends('layouts.app')

@section('title', 'Live Scanner QR Presensi')

@push('styles')
<style>
    .scanner-layout {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 24px;
    }

    .scanner-viewport {
        background: #000;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 2px solid var(--primary);
        box-shadow: 0 0 25px rgba(58, 134, 255, 0.25);
        position: relative;
        min-height: 320px;
    }

    .scan-status-overlay {
        position: absolute;
        bottom: 14px;
        left: 14px;
        right: 14px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 10;
    }

    .scan-result-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
    }

    .scan-success-pill {
        animation: pulseGlow 1.5s infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
        50% { box-shadow: 0 0 25px rgba(16, 185, 129, 0.8); }
    }
</style>
@endpush

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 4px;">Live Scanner QR Presensi</h2>
        <p style="font-size: 13px; color: var(--text-dim);">Arahkan kartu QR pelajar ke kamera untuk mencatat presensi instan</p>
    </div>
    <a href="{{ route('attendance.index') }}" class="btn btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Rekap Presensi
    </a>
</div>

<div class="scanner-layout">
    <!-- Camera Viewport Card -->
    <div class="card" style="padding: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                <span style="font-size: 13px; font-weight: 700; color: #fff;">Kamera Scanner Aktif</span>
            </div>
            <button id="btnToggleCamera" class="btn btn-sm btn-outline" onclick="toggleCamera()">
                Restart Kamera
            </button>
        </div>

        <div class="scanner-viewport">
            <div id="reader" style="width: 100%;"></div>
            <div class="scan-status-overlay" id="scanStatus">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <span id="scanStatusText">Arahkan QR Code ke area kotak kamera...</span>
            </div>
        </div>

        <!-- Manual NISN Input Fallback -->
        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border-color);">
            <form id="manualScanForm" onsubmit="event.preventDefault(); processQrCode(document.getElementById('manualNisnInput').value);">
                <label class="form-label">Atau Masukkan NISN / Kode Kartu Manual:</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="manualNisnInput" class="form-control" placeholder="Contoh: 30891000 atau GP-STU-30891000">
                    <button type="submit" class="btn btn-primary" style="flex-shrink: 0;">Presensi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Scanned History -->
    <div class="scan-result-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <div style="font-size: 16px; font-weight: 800; color: #fff;">Riwayat Scan Hari Ini</div>
            <span style="font-size: 11px; background: rgba(58, 134, 255, 0.15); color: #60a5fa; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                Live Feed
            </span>
        </div>

        <div id="latestScanAlert" style="display: none; margin-bottom: 16px; padding: 14px; border-radius: 12px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981;">
            <div style="font-weight: 800; color: #34d399; font-size: 14px; margin-bottom: 2px;" id="alertStudentName">-</div>
            <div style="font-size: 12px; color: #e2e8f0;" id="alertStudentDetail">-</div>
        </div>

        <div class="table-responsive" style="max-height: 420px;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="scanFeedBody">
                    @forelse($recentScans as $sc)
                        <tr>
                            <td style="font-weight: 700; color: #60a5fa;">{{ $sc->check_in_time }}</td>
                            <td style="font-weight: 700; color: #fff;">{{ $sc->student->name }}</td>
                            <td>
                                <span style="background: rgba(58, 134, 255, 0.15); color: #38bdf8; font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">
                                    {{ $sc->student->schoolClass->name }}
                                </span>
                            </td>
                            <td>
                                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">
                                    Hadir
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyScanRow">
                            <td colspan="4" style="text-align: center; padding: 32px; color: var(--text-dim);">
                                Belum ada presensi QR yang tercatat hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let html5QrCode = null;
    let isProcessing = false;

    function playBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880; // A5 pitch
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch (e) {
            console.log(e);
        }
    }

    function processQrCode(decodedText) {
        if (isProcessing || !decodedText) return;
        isProcessing = true;

        const statusEl = document.getElementById('scanStatusText');
        statusEl.textContent = 'Memverifikasi kode: ' + decodedText + '...';

        fetch("{{ route('attendance.scanProcess') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                playBeep();
                statusEl.innerHTML = `<strong style="color: #34d399;">✓ Presensi Berhasil: ${data.student.name}</strong>`;
                
                // Show notification alert
                const alertBox = document.getElementById('latestScanAlert');
                alertBox.style.display = 'block';
                document.getElementById('alertStudentName').textContent = '✓ ' + data.student.name + ' (' + data.student.class + ')';
                document.getElementById('alertStudentDetail').textContent = 'NISN: ' + data.student.nisn + ' | Pukul: ' + data.student.time + ' WIB';

                // Add to table
                const emptyRow = document.getElementById('emptyScanRow');
                if (emptyRow) emptyRow.remove();

                const tbody = document.getElementById('scanFeedBody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td style="font-weight: 700; color: #60a5fa;">${data.student.time}</td>
                    <td style="font-weight: 700; color: #fff;">${data.student.name}</td>
                    <td><span style="background: rgba(58, 134, 255, 0.15); color: #38bdf8; font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">${data.student.class}</span></td>
                    <td><span style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 11px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">Hadir</span></td>
                `;
                tbody.insertBefore(newRow, tbody.firstChild);

                document.getElementById('manualNisnInput').value = '';
            } else {
                statusEl.innerHTML = `<span style="color: #fb7185;">✗ ${data.message}</span>`;
            }
        })
        .catch(err => {
            statusEl.innerHTML = `<span style="color: #fb7185;">✗ Gagal memproses kode QR.</span>`;
        })
        .finally(() => {
            setTimeout(() => {
                isProcessing = false;
                statusEl.textContent = 'Siap memindai kartu berikutnya...';
            }, 1800);
        });
    }

    function initScanner() {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                processQrCode(decodedText);
            },
            (errorMessage) => {
                // Ignore scanning errors
            }
        ).catch(err => {
            document.getElementById('scanStatusText').textContent = 'Kamera tidak terdeteksi. Silakan gunakan input manual NISN di bawah.';
        });
    }

    function toggleCamera() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => initScanner()).catch(() => initScanner());
        } else {
            initScanner();
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        initScanner();
    });
</script>
@endpush
@endsection
