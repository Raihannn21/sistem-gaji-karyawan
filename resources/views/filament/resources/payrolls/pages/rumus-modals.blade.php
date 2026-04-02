@php
    if (!$record) return '-';

    $attendances = \App\Models\Attendance::where('employee_id', $record->employee_id)
        ->whereBetween('tanggal', [$record->payroll->tanggal_mulai, $record->payroll->tanggal_selesai])
        ->orderBy('tanggal')
        ->get();
@endphp
<style>
    .modal-container-modern { font-family: 'Inter', sans-serif; color: #1e293b; }
    .info-header { 
        display: flex; align-items: center; gap: 15px; padding: 15px; 
        background: #f8fafc; border-radius: 15px; border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .avatar-circle {
        width: 45px; height: 45px; border-radius: 50%; background: #3b82f6;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 900; font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 25px; }
    .stat-card { padding: 15px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .stat-title { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .stat-val { font-size: 1.1rem; font-weight: 900; }
    .stat-hint { font-size: 9px; font-weight: 700; opacity: 0.6; margin-top: 2px; }

    .card-blue { background: #f0f7ff; border-color: #e0f0ff; color: #1e40af; }
    .card-pink { background: #fff1f2; border-color: #ffe4e6; color: #9f1239; }
    .card-emerald { background: #10b981; color: white; border: none; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2); }

    .timeline-title { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: #94a3b8; margin-bottom: 12px; padding-left: 5px; }
    .daily-row {
        display: flex; align-items: center; justify-content: space-between; padding: 12px 15px;
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 12px; margin-bottom: 8px;
        transition: 0.2s;
    }
    .daily-row:hover { border-color: #cbd5e1; transform: translateX(3px); }
    .date-box {
        width: 40px; height: 40px; border-radius: 10px; background: #ffffff; border: 1px solid #e2e8f0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    .badge-status { font-size: 8px; font-weight: 900; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; }

    html.dark .info-header, html.dark .stat-card, html.dark .daily-row, html.dark .date-box { background: #1e1e2e; border-color: #2d2d3f; color: #f8fafc; }
    html.dark .modal-container-modern { color: #f8fafc; }
</style>

<div class="modal-container-modern">
    <div class="info-header">
        <div class="avatar-circle">{{ substr($record->employee->nama, 0, 1) }}</div>
        <div>
            <div style="font-weight: 900; font-size: 0.95rem;">{{ $record->employee->nama }}</div>
            <div style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">ID #{{ $record->employee->no_id }} • {{ $record->payroll->periode }}</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card card-blue">
            <div class="stat-title">Gaji Pokok</div>
            <div class="stat-val">Rp {{ number_format($record->total_gaji_kehadiran, 0, ',', '.') }}</div>
            <div class="stat-hint">{{ $record->total_hadir }} HARI KERJA</div>
        </div>
        <div class="stat-card card-pink">
            <div class="stat-title">Uang Lembur</div>
            <div class="stat-val">Rp {{ number_format($record->total_gaji_lembur_biasa + $record->total_gaji_lembur_libur, 0, ',', '.') }}</div>
            <div class="stat-hint">{{ $record->jam_lembur_biasa + $record->jam_lembur_libur }} JAM DIAKUI</div>
        </div>
        <div class="stat-card card-emerald">
            <div class="stat-title" style="color: rgba(255,255,255,0.7)">Take Home Pay</div>
            <div class="stat-val" style="font-size: 1.2rem;">Rp {{ number_format($record->total_gaji, 0, ',', '.') }}</div>
            <div class="stat-hint" style="color: rgba(255,255,255,0.7)">BERSIH DITERIMA</div>
        </div>
    </div>

    <div class="timeline-title">Log Kehadiran Harian</div>
    <div style="max-height: 250px; overflow-y: auto; padding-right: 5px;" class="custom-scrollbar">
        @foreach($attendances as $att)
            @php
                $isLibur = $att->is_holiday;
                $lembur = $isLibur ? floor($att->total_jam_kerja) : ($att->total_jam_kerja > 8 ? floor($att->total_jam_kerja - 8) : 0);
            @endphp
            <div class="daily-row">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="date-box">
                        <span style="font-size: 8px; text-transform: uppercase; color: #94a3b8; line-height: 1;">{{ \Carbon\Carbon::parse($att->tanggal)->translatedFormat('M') }}</span>
                        <span style="font-size: 14px; font-weight: 900; line-height: 1; margin-top: 3px;">{{ \Carbon\Carbon::parse($att->tanggal)->translatedFormat('d') }}</span>
                    </div>
                    <div>
                        <span class="badge-status" style="background: {{ $isLibur ? '#fff1f2' : '#f0f9ff' }}; color: {{ $isLibur ? '#be123c' : '#0369a1' }}; border: 1px solid {{ $isLibur ? '#ffe4e6' : '#e0f2fe' }};">
                            {{ $isLibur ? '🔥 Libur' : '📅 Kerja' }}
                        </span>
                        <div style="font-size: 11px; font-weight: 800; color: #64748b; margin-top: 4px;">{{ floor($att->total_jam_kerja) }} Jam Bekerja</div>
                    </div>
                </div>
                @if($lembur > 0)
                    <div style="text-align: right;">
                        <div style="font-size: 8px; font-weight: 900; text-transform: uppercase; color: #db2777;">Lembur</div>
                        <div style="font-size: 12px; font-weight: 900; color: #be123c;">+{{ $lembur }} Jm</div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
