<x-filament-panels::page>
    <style>
        :root {
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
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
            margin-top: 1.1rem;
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .top-action-btn:hover { transform: translateY(-1px); }
        .btn-sync { background: #0ea5e9; }
        .btn-sync:hover { background: #0284c7; }
        .btn-create { background: #2563eb; }
        .btn-create:hover { background: #1d4ed8; }

        .custom-table-wrapper {
            background: var(--ui-bg-card);
            border-radius: 1rem;
            border: 1px solid var(--ui-border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            background-color: #f9fafb;
            padding: 0.95rem 1.25rem;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid var(--ui-border);
        }

        .custom-table td {
            padding: 0.9rem 1.25rem;
            font-size: 0.86rem;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-table tr:hover td {
            background-color: #f8fafc;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-red {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
        }

        .badge-indigo {
            background-color: #eef2ff;
            color: #4f46e5;
            border: 1px solid #e0e7ff;
        }

        .badge-slate {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .action-wrap {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 11px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-edit {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #dbeafe;
        }

        .action-delete {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fee2e2;
        }

        .action-edit:hover { background: #dbeafe; }
        .action-delete:hover { background: #fee2e2; }

        .date-text {
            font-family: monospace;
            font-weight: 700;
            color: #374151;
        }

        html.dark {
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
        }

        html.dark .custom-table-wrapper {
            border-color: #27272a;
        }

        html.dark .custom-table th {
            background: #27272a;
            color: #9ca3af;
        }

        html.dark .custom-table td {
            color: #e5e7eb;
            border-color: #27272a;
        }

        html.dark .custom-table tr:hover td {
            background: #1f1f23;
        }

        html.dark .date-text {
            color: #f3f4f6;
        }

        html.dark .page-kicker { color: #a1a1aa; }

        html.dark .action-edit {
            background: rgba(30, 64, 175, 0.35);
            color: #bfdbfe;
            border-color: rgba(147, 197, 253, 0.35);
        }

        html.dark .action-delete {
            background: rgba(127, 29, 29, 0.28);
            color: #fca5a5;
            border-color: rgba(248, 113, 113, 0.35);
        }
    </style>

    @php
        $holidays = \App\Models\Holiday::orderBy('tanggal', 'asc')->get();
    @endphp

    <div class="page-heading-wrap">
        <span class="page-kicker">Data Master > Kalender Libur</span>
        <h1 class="page-title">Kalender Libur</h1>

        <div class="top-action-row">
            <button type="button" class="top-action-btn btn-sync" wire:click="mountAction('syncHolidays')">
                Tarik Hari Minggu
            </button>
            <a href="{{ \App\Filament\Resources\Holidays\HolidayResource::getUrl('create') }}" class="top-action-btn btn-create">
                Tambah Libur Manual
            </a>
        </div>
    </div>

    <div class="custom-table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Hari</th>
                    <th>Nama Hari Libur / Keterangan</th>
                    <th width="15%">Tipe Libur</th>
                    <th width="16%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($holidays as $index => $holiday)
                    @php
                        $dt = \Carbon\Carbon::parse($holiday->tanggal);
                        $isSunday = $dt->isSunday();
                    @endphp
                    <tr>
                        <td style="color: #9ca3af;">{{ $index + 1 }}</td>
                        <td><span class="date-text">{{ $dt->format('d / m / Y') }}</span></td>
                        <td style="font-weight: 600;">{{ $dt->translatedFormat('l') }}</td>
                        <td style="font-weight: 500;">{{ $holiday->keterangan }}</td>
                        <td>
                            @if($isSunday)
                                <span class="badge-pill badge-red">Akhir Pekan</span>
                            @else
                                <span class="badge-pill badge-slate">Manual</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-wrap">
                                <a href="{{ \App\Filament\Resources\Holidays\HolidayResource::getUrl('edit', ['record' => $holiday->id]) }}" class="action-btn action-edit">
                                    Edit
                                </a>
                                <button
                                    type="button"
                                    class="action-btn action-delete"
                                    x-on:click="if (confirm('Hapus data libur ini?')) { $wire.deleteHoliday({{ $holiday->id }}) }"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #9ca3af;">
                            Belum ada data libur. Silakan gunakan tombol Tarik Data di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-filament-panels::page>