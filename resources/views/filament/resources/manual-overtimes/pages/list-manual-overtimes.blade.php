<x-filament-panels::page>
    <style>
        :root {
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
        }

        .fi-header { display: none !important; }

        .page-heading-wrap { margin-bottom: 1rem; }
        .page-kicker {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.3rem;
        }
        .page-title {
            font-size: 2.18rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: var(--ui-text-main);
            line-height: 1.05;
        }

        .top-action-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .top-action-btn {
            border: none;
            border-radius: 0.8rem;
            padding: 0.66rem 0.95rem;
            font-size: 0.82rem;
            font-weight: 800;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .top-action-btn:hover { transform: translateY(-1px); }
        .btn-create { background: #2563eb; }
        .btn-create:hover { background: #1d4ed8; }

        .manual-container {
            background: var(--ui-bg-card);
            border-radius: 1.25rem;
            border: 1px solid var(--ui-border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }

        .search-row {
            padding: 1.05rem 1.2rem;
            background: #f9fafb;
            border-bottom: 1px solid var(--ui-border);
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 240px;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--ui-border);
            font-size: 0.84rem;
            background: var(--ui-bg-card);
        }

        .search-input:focus {
            outline: none;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th {
            text-align: left;
            padding: 0.9rem 1.1rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #6b7280;
            background: #f8fafc;
            border-bottom: 1px solid var(--ui-border);
        }
        .custom-table td {
            padding: 0.86rem 1.1rem;
            font-size: 0.84rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 800;
        }
        .badge-status { background: #eef2ff; color: #3730a3; }
        .badge-biasa { background: #ecfeff; color: #155e75; }
        .badge-libur { background: #fff1f2; color: #be123c; }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid #dbeafe;
            text-decoration: none;
        }

        .btn-edit:hover { background: #dbeafe; }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        html.dark .manual-container { border-color: #27272a; }
        html.dark .search-row { background: #1f1f23; border-color: #27272a; }
        html.dark .search-input { border-color: #3f3f46; color: #f8fafc; }
        html.dark .custom-table th { background: #27272a; color: #9ca3af; border-color: #27272a; }
        html.dark .custom-table td { color: #e5e7eb; border-color: #27272a; }
        html.dark .page-kicker { color: #a1a1aa; }
        html.dark .btn-edit {
            background: rgba(30, 64, 175, 0.35);
            color: #bfdbfe;
            border-color: rgba(147, 197, 253, 0.35);
        }
    </style>

    <div class="page-heading-wrap">
        <span class="page-kicker">Operasional > Lembur</span>
        <h1 class="page-title">Input Lembur Manual</h1>

        <div class="top-action-row">
            <button type="button" class="top-action-btn btn-create" wire:click="mountAction('create')">
                Tambah Lembur Manual
            </button>
        </div>
    </div>

    <div class="manual-container">
        <div class="search-row">
            <input type="text" wire:model.live.debounce.300ms="tableSearch" class="search-input" placeholder="Cari karyawan atau keterangan...">

            <select wire:model.live="tableFilters.jenis_lembur.value"
                class="search-input" style="max-width: 180px;">
                <option value="">Semua Jenis</option>
                <option value="biasa">Lembur Biasa</option>
                <option value="libur">Lembur Libur</option>
            </select>

            <select wire:model.live="tableFilters.employment_status.value"
                class="search-input" style="max-width: 160px;">
                <option value="">Semua Status</option>
                <option value="PHL">PHL</option>
                <option value="PKWT">PKWT</option>
            </select>
        </div>

        @php
            $search = $this->tableSearch;
            $jenis = $this->tableFilters['jenis_lembur']['value'] ?? null;
            $status = $this->tableFilters['employment_status']['value'] ?? null;

            $rows = \App\Models\ManualOvertime::with(['employee', 'creator'])
                ->when($search, function ($q) use ($search) {
                    return $q->where(function ($query) use ($search) {
                        $query->whereHas('employee', function ($eq) use ($search) {
                            $eq->where('nama', 'ilike', "%{$search}%");
                        })->orWhere('keterangan', 'ilike', "%{$search}%");
                    });
                })
                ->when($jenis, fn ($q) => $q->where('jenis_lembur', $jenis))
                ->when($status, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('employment_status', $status)))
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->get();
        @endphp

        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Status</th>
                        <th>Jenis</th>
                        <th>Jam</th>
                        <th>Keterangan</th>
                        <th>Input Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>
                                <div style="font-weight:700;">{{ $row->employee->nama }}</div>
                                <div style="font-size:11px; color:#94a3b8;">ID: {{ $row->employee->no_id }}</div>
                            </td>
                            <td><span class="badge badge-status">{{ $row->employee->employment_status }}</span></td>
                            <td>
                                @if($row->jenis_lembur === 'libur')
                                    <span class="badge badge-libur">Lembur Libur</span>
                                @else
                                    <span class="badge badge-biasa">Lembur Biasa</span>
                                @endif
                            </td>
                            <td style="font-weight:800;">{{ (int) $row->jam_lembur }} jam</td>
                            <td>{{ $row->keterangan ?: '-' }}</td>
                            <td>{{ $row->creator->name ?? '-' }}</td>
                            <td>
                                <a href="{{ \App\Filament\Resources\ManualOvertimes\ManualOvertimeResource::getUrl('edit', ['record' => $row->id]) }}" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:2rem; color:#94a3b8; font-style:italic;">Belum ada data lembur manual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
