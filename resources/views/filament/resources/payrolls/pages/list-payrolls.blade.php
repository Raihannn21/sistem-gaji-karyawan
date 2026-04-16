<x-filament-panels::page>
    <style>
        :root {
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
        }

        .payroll-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: var(--ui-bg-card);
            border-radius: 1.5rem;
            padding: 1.75rem;
            border: 1px solid var(--ui-border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .stats-card:hover { transform: translateY(-5px); }
        .stats-card .decor {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 50%;
        }
        .stats-label { font-size: 0.72rem; font-weight: 800; color: var(--ui-text-soft); text-transform: uppercase; letter-spacing: 0.12em; }
        .stats-value { font-size: 1.58rem; font-weight: 900; color: var(--ui-text-main); margin-top: 0.55rem; line-height: 1.12; }
        
        .report-section {
            background: var(--ui-bg-card);
            border-radius: 1.5rem;
            border: 1px solid var(--ui-border);
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.03);
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
            max-width: 400px;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 0.85rem;
            border: 1px solid var(--ui-border);
            background: var(--ui-bg-card);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.85rem center;
            background-size: 1.1rem;
            font-size: 0.88rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .search-input:focus {
            outline: none;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .ui-select {
            border: 1px solid var(--ui-border);
            border-radius: 0.75rem;
            padding: 0.62rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            min-width: 180px;
            background: var(--ui-bg-card);
        }

        .ui-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 0.8rem;
            padding: 0.68rem 1rem;
            border: 1px solid transparent;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .ui-btn-primary {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.28);
        }

        .ui-btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

        .ui-btn-soft { background: #f1f5f9; color: #334155; border-color: #e2e8f0; }
        .ui-btn-soft:hover { background: #e2e8f0; }

        .ui-btn-success { background: #ecfdf5; color: #166534; border-color: #bbf7d0; }
        .ui-btn-success:hover { background: #dcfce7; }

        .ui-btn-warning { background: #fff7ed; color: #9a3412; border-color: #fed7aa; }
        .ui-btn-warning:hover { background: #ffedd5; }

        .ui-btn-danger { background: #fef2f2; color: #dc2626; border-color: #fee2e2; }
        .ui-btn-danger:hover { background: #fee2e2; }

        .ui-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.25rem 0.6rem;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            border: 1px solid transparent;
        }

        .ui-pill-final { background:#ecfdf5; color:#166534; border-color:#bbf7d0; }
        .ui-pill-draft { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }

        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            background: #f8fafc;
            font-size: 0.65rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            border-bottom: 1px solid #e5e7eb;
        }
        .custom-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }

        .periode-info { display: flex; flex-direction: column; }
        .periode-name { font-weight: 900; color: #1e293b; font-size: 0.95rem; }
        .periode-date { font-size: 0.7rem; color: #94a3b8; font-weight: 600; }

        .money-cell { text-align: right; }
        .money-main { font-weight: 900; color: #111827; font-size: 1rem; }
        .money-sub { font-size: 0.65rem; color: #6366f1; font-weight: 800; text-transform: uppercase; }

        .grand-total-bg {
            background-color: #f0f9ff;
            color: #0284c7;
            padding: 0.4rem 0.85rem;
            border-radius: 10px;
            font-weight: 950;
            display: inline-block;
            border: 1px solid #e0f2fe;
        }

        .action-row { display: flex; gap: 8px; justify-content: flex-end; align-items: center; }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        html.dark .stats-card, html.dark .report-section { border-color: #27272a; }
        html.dark .custom-table th { background: #1f1f23; border-color: #27272a; color: #9ca3af; }
        html.dark .stats-value, html.dark .periode-name, html.dark .money-main { color: white; }
        html.dark .custom-table td { border-color: #27272a; color: #d4d4d8; }
        html.dark .search-input { border-color: #27272a; color: white; }
        html.dark .ui-select { border-color: #3f3f46; color: #e5e7eb; }
        html.dark .ui-btn-soft { background: #27272a; color: #e4e4e7; border-color: #3f3f46; }
        html.dark .ui-btn-soft:hover { background: #3f3f46; }
        html.dark .ui-btn-danger { background: rgba(127, 29, 29, 0.28); color: #fca5a5; border-color: rgba(220, 38, 38, 0.4); }
        html.dark .ui-btn-warning { background: rgba(124, 45, 18, 0.35); color: #fdba74; border-color: rgba(251, 146, 60, 0.3); }
        html.dark .ui-btn-success { background: rgba(20, 83, 45, 0.35); color: #86efac; border-color: rgba(74, 222, 128, 0.3); }
        html.dark .grand-total-bg { background: rgba(2, 132, 199, 0.1); border-color: rgba(2, 132, 199, 0.2); }

        /* Hapus Header Filament Bawaan */
        .fi-header { display: none !important; }
        .fi-main-ctn { padding-top: 1rem !important; }
    </style>

    {{-- Header & Search Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;">
        <div class="search-box" style="margin-bottom: 0; flex: 1; min-width: 300px;">
            <input type="text" wire:model.live.debounce.300ms="tableSearch" 
                   placeholder="Cari nama periode..." class="search-input">
        </div>

        <select wire:model.live="statusFilter"
                class="ui-select">
            <option value="">Semua Status</option>
            <option value="PHL">PHL</option>
            <option value="PKWT">PKWT</option>
        </select>

        <button 
            wire:click="mountAction('generatePayroll')"
            class="ui-btn ui-btn-primary"
            style="padding: 0.75rem 1.15rem; font-size: 0.8rem;"
        >
            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Generate Rekap Gaji
        </button>
    </div>

    @php
        $search = $this->tableSearch;
        $statusFilter = $this->statusFilter;
        $payrollsQuery = \App\Models\Payroll::when($search, fn($q) => $q->where('periode', 'ilike', "%{$search}%"))
                        ->orderBy('tanggal_mulai', 'desc');
        $payrolls = $payrollsQuery->get();

        /** @var \App\Models\Payroll|null $latest */
        $latest = $payrolls->first();

        $latestDetails = collect();
        if ($latest) {
            $latestDetails = $latest->details()
                ->with('employee')
                ->when($statusFilter, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('employment_status', $statusFilter)))
                ->get();
        }

        $latestTotalPokok = $statusFilter ? $latestDetails->sum('total_gaji_kehadiran') : ($latest->total_gaji_pokok ?? 0);
        $latestTotalLembur = $statusFilter ? ($latestDetails->sum('total_gaji_lembur_biasa') + $latestDetails->sum('total_gaji_lembur_libur')) : ($latest->total_uang_lembur ?? 0);
        $latestGrandTotal = $statusFilter ? $latestDetails->sum('total_gaji') : ($latest->grand_total ?? 0);
    @endphp

    {{-- Stats Row --}}
    <div class="payroll-dashboard">
        <div class="stats-card">
            <div class="decor"></div>
            <div class="stats-label">Laporan Terakhir</div>
            <div class="stats-value text-blue-600">{{ $latest->periode ?? '-' }}</div>
            @if($statusFilter)
                <div class="money-sub" style="margin-top: 6px;">Filter: {{ $statusFilter }}</div>
            @endif
        </div>
        <div class="stats-card">
            <div class="decor"></div>
            <div class="stats-label">Total Gaji Pokok</div>
            <div class="stats-value text-slate-500">Rp {{ number_format($latestTotalPokok, 0, ',', '.') }}</div>
        </div>
        <div class="stats-card">
            <div class="decor" style="background: rgba(99, 102, 241, 0.05);"></div>
            <div class="stats-label">Lembur Keluar</div>
            <div class="stats-value text-indigo-600">Rp {{ number_format($latestTotalLembur, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Tabel Utama --}}
    <div class="report-section">
        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Periode Gaji</th>
                        <th class="text-right">Gaji Pokok</th>
                        <th class="text-right">Upah Lembur</th>
                        <th class="text-right">Total Dibayarkan</th>
                        <th class="text-right">Status</th>
                        <th class="text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                        @php
                            /** @var \App\Models\Payroll $p */
                            $details = $p->details()
                                ->with('employee')
                                ->when($statusFilter, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('employment_status', $statusFilter)))
                                ->get();

                            $rowGajiPokok = $statusFilter ? $details->sum('total_gaji_kehadiran') : $p->total_gaji_pokok;
                            $rowUangLembur = $statusFilter ? ($details->sum('total_gaji_lembur_biasa') + $details->sum('total_gaji_lembur_libur')) : $p->total_uang_lembur;
                            $rowGrandTotal = $statusFilter ? $details->sum('total_gaji') : $p->grand_total;
                        @endphp
                        <tr>
                            <td>
                                <div class="periode-info">
                                    <span class="periode-name">{{ $p->periode }}</span>
                                    <span class="periode-date">
                                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->translatedFormat('d M') }} - 
                                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="money-cell">
                                <div class="money-main">Rp {{ number_format($rowGajiPokok, 0, ',', '.') }}</div>
                                <div class="money-sub text-slate-400">Kehadiran Normal</div>
                            </td>
                            <td class="money-cell">
                                <div class="money-main text-indigo-600">Rp {{ number_format($rowUangLembur, 0, ',', '.') }}</div>
                                <div class="money-sub">Lembur Per jam</div>
                            </td>
                            <td class="money-cell">
                                <div class="grand-total-bg">
                                    Rp {{ number_format($rowGrandTotal, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-right">
                                @if($p->status_payroll === 'final')
                                    <span class="ui-pill ui-pill-final">Final</span>
                                @else
                                    <span class="ui-pill ui-pill-draft">Draft</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-row">
                                    <a href="{{ \App\Filament\Resources\Payrolls\PayrollResource::getUrl('view', ['record' => $p->id]) }}" 
                                       class="ui-btn ui-btn-soft">
                                        Rincian
                                    </a>
                                    @if($p->status_payroll === 'final')
                                        <button 
                                            type="button"
                                            x-on:click="if (confirm('Buka kembali finalisasi payroll ini?')) { $wire.reopenPayrollRecord({{ $p->id }}) }"
                                            class="ui-btn ui-btn-warning">
                                            Buka
                                        </button>
                                    @else
                                        <button 
                                            type="button"
                                            x-on:click="if (confirm('Finalisasi payroll ini? Setelah final, data periode akan terkunci.')) { $wire.finalizePayrollRecord({{ $p->id }}) }"
                                            class="ui-btn ui-btn-success">
                                            Finalisasi
                                        </button>
                                    @endif
                                    <button 
                                        type="button"
                                        x-on:click="if (confirm('Hapus payroll ini? Aksi ini tidak dapat dibatalkan.')) { $wire.deletePayrollRecord({{ $p->id }}) }"
                                        class="ui-btn ui-btn-danger">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-20 text-slate-400 italic">Belum ada data gaji.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Asli Filament - DIHAPUS TOTAL --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
