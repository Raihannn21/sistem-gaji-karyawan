<x-filament-panels::page>
    <style>
        .payroll-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.75rem;
            border: 1px solid #e5e7eb;
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
        .stats-label { font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.12em; }
        .stats-value { font-size: 1.5rem; font-weight: 900; color: #1e293b; margin-top: 0.5rem; }
        
        .report-section {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid #e5e7eb;
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
            border: 1px solid #e5e7eb;
            background: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.85rem center;
            background-size: 1.1rem;
            font-size: 0.85rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

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

        .action-row { display: flex; gap: 8px; justify-content: flex-end; }
        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            transition: 0.2s;
        }
        .btn-blue { background: #f1f5f9; color: #475569; }
        .btn-blue:hover { background: #1d4ed8; color: white; }
        .btn-red { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
        .btn-red:hover { background: #dc2626; color: white; }

        html.dark .stats-card, html.dark .report-section { background: #18181b; border-color: #27272a; }
        html.dark .custom-table th { background: #1f1f23; border-color: #27272a; }
        html.dark .stats-value, html.dark .periode-name, html.dark .money-main { color: white; }
        html.dark .custom-table td { border-color: #27272a; }
        html.dark .search-input { background-color: #18181b; border-color: #27272a; color: white; }
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

        <button 
            wire:click="mountAction('generatePayroll')"
            style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 14px; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 10px; transition: 0.3s; border: none; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);"
            onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)';"
            onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)';"
        >
            <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Generate Rekap Gaji
        </button>
    </div>

    @php
        $search = $this->tableSearch;
        $payrollsQuery = \App\Models\Payroll::when($search, fn($q) => $q->where('periode', 'ilike', "%{$search}%"))
                        ->orderBy('tanggal_mulai', 'desc');
        $payrolls = $payrollsQuery->get();
        $latest = $payrolls->first();
    @endphp

    {{-- Stats Row --}}
    <div class="payroll-dashboard">
        <div class="stats-card">
            <div class="decor"></div>
            <div class="stats-label">Laporan Terakhir</div>
            <div class="stats-value text-blue-600">{{ $latest->periode ?? '-' }}</div>
        </div>
        <div class="stats-card">
            <div class="decor"></div>
            <div class="stats-label">Total Gaji Pokok</div>
            <div class="stats-value text-slate-500">Rp {{ number_format($latest->total_gaji_pokok ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stats-card">
            <div class="decor" style="background: rgba(99, 102, 241, 0.05);"></div>
            <div class="stats-label">Lembur Keluar</div>
            <div class="stats-value text-indigo-600">Rp {{ number_format($latest->total_uang_lembur ?? 0, 0, ',', '.') }}</div>
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
                        <th class="text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
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
                                <div class="money-main">Rp {{ number_format($p->total_gaji_pokok, 0, ',', '.') }}</div>
                                <div class="money-sub text-slate-400">Kehadiran Normal</div>
                            </td>
                            <td class="money-cell">
                                <div class="money-main text-indigo-600">Rp {{ number_format($p->total_uang_lembur, 0, ',', '.') }}</div>
                                <div class="money-sub">Lembur Per jam</div>
                            </td>
                            <td class="money-cell">
                                <div class="grand-total-bg">
                                    Rp {{ number_format($p->grand_total, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                <div class="action-row">
                                    <a href="{{ \App\Filament\Resources\Payrolls\PayrollResource::getUrl('view', ['record' => $p->id]) }}" 
                                       class="btn-action btn-blue">
                                        Rincian
                                    </a>
                                    <button 
                                        wire:click="mountAction('delete', { record: {{ $p->id }} })"
                                        class="btn-action btn-red">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-20 text-slate-400 italic">Belum ada data gaji.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel Asli Filament - DIHAPUS TOTAL --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
