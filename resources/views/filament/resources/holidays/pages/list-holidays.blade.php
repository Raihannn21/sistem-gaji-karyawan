<x-filament-panels::page>
    <style>
        .custom-table-wrapper {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
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
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }

        .custom-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
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

        .date-text {
            font-family: monospace;
            font-weight: 700;
            color: #374151;
        }

        html.dark .custom-table-wrapper {
            background: #18181b;
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
    </style>

    @php
        $holidays = \App\Models\Holiday::orderBy('tanggal', 'asc')->get();
    @endphp

    <div class="custom-table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Hari</th>
                    <th>Nama Hari Libur / Keterangan</th>
                    <th width="15%">Tipe Libur</th>
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
                                <span class="badge-pill badge-indigo">Libur Nasional</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: #9ca3af;">
                            Belum ada data libur. Silakan gunakan tombol Tarik Data di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-filament-panels::page>