<x-filament-panels::page>
    <style>
        :root {
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
        }

        .payroll-header-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 1.5rem;
            padding: 2.5rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-main-value {
            font-size: 2.5rem;
            font-weight: 950;
            letter-spacing: -0.05em;
            margin-top: 0.5rem;
            color: #38bdf8;
        }

        .header-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
        }

        .detail-section {
            background: var(--ui-bg-card);
            border-radius: 1.5rem;
            border: 1px solid var(--ui-border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            background: #f8fafc;
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #e5e7eb;
        }

        .custom-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.89rem;
        }

        .emp-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .emp-avatar {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #3b82f6;
        }

        .emp-name {
            font-weight: 800;
            color: #1e293b;
        }

        .emp-id {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 700;
            margin-top: 2px;
        }

        .val-badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 800;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        .val-hadir {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #dcfce7;
        }

        .val-lembur {
            background: #fdf2f8;
            color: #9d174d;
            border: 1px solid #fce7f3;
        }

        .total-pay-badge {
            background: #eff6ff;
            color: #1e4ed8;
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 950;
            border: 1px solid #dbeafe;
            display: inline-block;
        }

        .ui-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 800;
            transition: 0.2s;
            border: 1px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.01em;
        }

        .ui-btn-soft {
            background: #ffffff;
            color: #334155;
            border-color: #e2e8f0;
        }

        .ui-btn-soft:hover { background: #f8fafc; }

        .ui-btn-success {
            background: #10b981;
            color: white;
            border-color: #10b981;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .ui-btn-success:hover { background: #059669; }

        .toolbar {
            display:flex;
            justify-content:flex-end;
            gap:10px;
            margin:-0.75rem 0 1rem;
            flex-wrap: wrap;
        }

        .toolbar-btn {
            padding:8px 14px;
            border-radius:10px;
            font-size:0.75rem;
            font-weight:800;
            border:1px solid transparent;
        }

        .toolbar-btn-blue { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .toolbar-btn-gray { background:#f3f4f6; color:#1f2937; border-color:#d1d5db; }

        html.dark .payroll-header-card {
            background: #1e1e2e;
        }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        .emp-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .emp-avatar {
            width: 32px;
            height: 32px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: #3b82f6;
        }

        .emp-name {
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .emp-id {
            font-size: 0.65rem;
            color: #94a3b8;
            font-weight: 700;
        }

        .search-input-custom-nempel {
            width: 100%;
            padding: 1.1rem 1.5rem 1.1rem 3.25rem;
            border: 0;
            background: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23cbd5e1'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 1.5rem center;
            background-size: 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .search-input-custom-nempel:focus {
            outline: none;
            background: #fdfdfd;
        }

        html.dark .search-input-custom-nempel {
            background-color: #18181b;
            border-right: 1px solid #27272a;
            color: white;
        }

        html.dark .emp-avatar {
            background: #27272a;
            color: #60a5fa;
        }

        html.dark .detail-section {
            border-color: #27272a;
        }

        html.dark .custom-table th {
            background: #1f1f23;
            border-color: #27272a;
        }

        html.dark .custom-table td {
            border-color: #27272a;
            color: #d4d4d8;
        }

        html.dark .emp-name {
            color: #f8fafc;
        }

        html.dark .total-pay-badge {
            background: rgba(30, 78, 216, 0.1);
            border-color: rgba(30, 232, 216, 0.1);
            color: #60a5fa;
        }

        html.dark .search-input-custom {
            background-color: #18181b;
            border-color: #27272a;
            color: white;
            box-shadow: none;
        }

        html.dark .toolbar-btn-blue {
            background: rgba(30, 64, 175, 0.35);
            color: #bfdbfe;
            border-color: rgba(147, 197, 253, 0.35);
        }

        html.dark .toolbar-btn-gray {
            background: #27272a;
            color: #e4e4e7;
            border-color: #3f3f46;
        }

        html.dark .ui-btn-soft {
            background: #27272a;
            color: #e5e7eb;
            border-color: #3f3f46;
        }

        html.dark .ui-btn-soft:hover { background: #3f3f46; }

        html.dark select {
            background: #18181b;
            color: #e5e7eb;
            border-color: #3f3f46 !important;
        }
    </style>

    <div class="payroll-header-card">
        <div>
            <div class="header-label">REKAPITULASI DANA GAJI</div>
            <div class="header-main-value">Rp {{ number_format($this->record->grand_total, 0, ',', '.') }}</div>
            <div
                class="mt-4 flex gap-4 text-xs font-bold text-white/50 bg-white/5 p-3 rounded-xl border border-white/10 w-fit">
                <span>{{ $this->record->periode }}</span>
                <span class="w-1 h-3 self-center border-l-2 border-white/20"></span>
                <span>{{ \Carbon\Carbon::parse($this->record->tanggal_mulai)->translatedFormat('d M') }} -
                    {{ \Carbon\Carbon::parse($this->record->tanggal_selesai)->translatedFormat('d M Y') }}</span>
            </div>
        </div>
        <div class="hidden md:block text-right opacity-30">
            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>
    </div>

    <div class="toolbar">
        <button wire:click="mountAction('exportExcel')"
            class="toolbar-btn toolbar-btn-blue">
            Export Excel
        </button>
        <button wire:click="mountAction('exportPdf')"
            class="toolbar-btn toolbar-btn-gray">
            Export PDF
        </button>
    </div>

    <div class="detail-section">
        {{-- Search Row Nempel --}}
        <div class="px-0 py-0 bg-white border-b border-slate-100 dark:bg-transparent" style="display:flex; gap:10px; align-items:center;">
            <input type="text" wire:model.live.debounce.300ms="tableSearch" 
                   placeholder="Cari nama karyawan..." 
                   class="search-input-custom-nempel" style="flex:1;">

            <select wire:model.live="statusFilter"
                    style="margin-right: 12px; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px 12px; font-size: 0.8rem; font-weight: 700; color: #334155; min-width: 150px; background: var(--ui-bg-card);">
                <option value="">Semua Status</option>
                <option value="PHL">PHL</option>
                <option value="PKWT">PKWT</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 25%">Informasi Karyawan</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-right">Pokok</th>
                        <th class="text-right">Upah Lembur</th>
                        <th class="text-right" style="width: 180px">Take Home Pay</th>
                        <th class="text-center" style="width: 200px">Aksi Manager</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $search = $this->tableSearch;
                        $statusFilter = $this->statusFilter;
                        $detailsList = $this->record->details()
                            ->when($search, function ($q) use ($search) {
                                return $q->whereHas('employee', function ($query) use ($search) {
                                    $query->where('nama', 'ilike', "%{$search}%");
                                });
                            })
                            ->when($statusFilter, function ($q) use ($statusFilter) {
                                return $q->whereHas('employee', function ($query) use ($statusFilter) {
                                    $query->where('employment_status', $statusFilter);
                                });
                            })
                            ->get();
                    @endphp
                    @foreach($detailsList as $d)
                        <tr>
                            <td class="align-middle">
                                <div class="emp-cell">
                                    <div class="emp-avatar">
                                        {{ substr($d->employee->nama, 0, 1) }}
                                    </div>
                                    <div style="min-width: 0;">
                                        <div class="emp-name text-sm truncate" title="{{ $d->employee->nama }}">{{ $d->employee->nama }}</div>
                                        <div class="emp-id text-[9px] opacity-70">ID: {{ $d->employee->no_id }} | {{ $d->employee->employment_status }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle whitespace-nowrap">
                                <span class="val-badge val-hadir">
                                    {{ $d->total_hadir }} HARI
                                </span>
                            </td>
                            <td class="text-right align-middle font-bold text-slate-700 dark:text-slate-200 whitespace-nowrap">
                                Rp {{ number_format($d->total_gaji_kehadiran, 0, ',', '.') }}
                            </td>
                            <td class="text-right align-middle whitespace-nowrap">
                                <div class="font-bold text-pink-600 text-sm">Rp
                                    {{ number_format($d->total_gaji_lembur_biasa + $d->total_gaji_lembur_libur, 0, ',', '.') }}
                                </div>
                                <div class="text-[9px] text-slate-400 font-black uppercase">
                                    {{ (int) $d->jam_lembur_biasa + (int) $d->jam_lembur_libur }} Jam manual</div>
                            </td>
                            <td class="text-right align-middle">
                                <div class="total-pay-badge whitespace-nowrap" style="font-size: 0.95rem; display: block; text-align: center;">
                                    Rp {{ number_format($d->total_gaji, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="flex justify-center items-center gap-3 whitespace-nowrap">
                                    {{-- Mini Card: Rumus --}}
                                    <button wire:click="mountAction('rincian', { record: {{ $d->id }} })"
                                        class="ui-btn ui-btn-soft">
                                        Rumus
                                    </button>

                                    {{-- Mini Card: Kirim Slip --}}
                                    <button wire:click="mountAction('kirim_slip', { record: {{ $d->id }} })"
                                        class="ui-btn ui-btn-success">
                                        Kirim Slip
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>