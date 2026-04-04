<x-filament-panels::page>
    <style>
        .attendance-container {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }

        .search-row {
            padding: 1.5rem;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.75rem;
            border-radius: 0.75rem;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.85rem center;
            background-size: 1.1rem;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .custom-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .custom-table tr:hover td {
            background-color: #f8fafc;
        }

        .name-cell {
            display: flex;
            flex-direction: column;
        }

        .name-cell b {
            color: #111827;
            font-size: 0.9rem;
        }

        .name-cell span {
            font-size: 0.75rem;
            color: #9ca3af;
            font-family: monospace;
        }

        .time-badge {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: monospace;
        }

        .time-in {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #dcfce7;
        }

        .time-out {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }

        .holiday-row {
            background-color: #fff1f2 !important;
        }

        .holiday-badge {
            background: #fee2e2;
            color: #b91c1c;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 0.65rem;
            font-weight: 800;
        }

        .total-duration {
            font-weight: 800;
            color: #2563eb;
        }

        html.dark .attendance-container {
            background: #18181b;
            border-color: #27272a;
        }

        html.dark .search-row {
            background: #1f1f23;
            border-color: #27272a;
        }

        html.dark .search-box input {
            background-color: #18181b;
            border-color: #3f3f46;
            color: white;
        }

        html.dark .custom-table th {
            background: #27272a;
            color: #9ca3af;
            border-color: #27272a;
        }

        html.dark .custom-table td {
            color: #e5e7eb;
            border-color: #27272a;
        }

        html.dark .custom-table tr:hover td {
            background: #1f1f23;
        }

        html.dark .name-cell b {
            color: white;
        }

        html.dark .time-in {
            background: rgba(22, 101, 52, 0.2);
            color: #4ade80;
            border-color: rgba(74, 222, 128, 0.2);
        }

        html.dark .time-out {
            background: rgba(154, 52, 18, 0.2);
            color: #fb923c;
            border-color: rgba(251, 146, 60, 0.2);
        }

        html.dark .holiday-row {
            background-color: rgba(185, 28, 28, 0.1) !important;
        }

        .fi-header {
            display: none !important;
        }

        .page-heading-wrap {
            margin-bottom: 1rem;
        }

        .page-kicker {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.3rem;
        }

        .page-title {
            font-size: 2.35rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: #0f172a;
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
            padding: 0.72rem 1rem;
            font-size: 0.9rem;
            font-weight: 800;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .top-action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-import { background: #10b981; }
        .btn-import:hover { background: #059669; }

        .btn-create { background: #2563eb; }
        .btn-create:hover { background: #1d4ed8; }

        html.dark .page-title {
            color: #f8fafc;
        }

        html.dark .page-kicker {
            color: #a1a1aa;
        }
    </style>

    <div class="page-heading-wrap">
        <span class="page-kicker">Rekap Kehadiran > Daftar</span>
        <h1 class="page-title">Rekap Kehadiran</h1>

        <div class="top-action-row">
            <button type="button" class="top-action-btn btn-import" wire:click="mountAction('importCsv')">
                Import CSV / Excel Kehadiran
            </button>

            <a
                href="{{ \App\Filament\Resources\Attendances\AttendanceResource::getUrl('create') }}"
                class="top-action-btn btn-create"
                style="display: inline-flex; align-items: center; text-decoration: none;"
            >
                Buat Kehadiran
            </a>
        </div>
    </div>

    <div class="attendance-container">
        {{-- Search & Filter Bar --}}
        <div class="search-row flex-wrap justify-between">
            <div style="display: flex; gap: 1rem; flex: 1; align-items: center;">
                <div class="search-box">
                    <input type="text" wire:model.live.debounce.300ms="tableSearch" placeholder="Cari nama karyawan...">
                </div>

                <div class="flex items-center gap-2">
                    <select wire:model.live="tableFilters.bulan_tahun.bulan"
                        class="rounded-lg border-gray-300 text-xs font-bold py-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">Semua Bulan</option>
                        @foreach(['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'] as $v => $l)
                            <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="tableFilters.bulan_tahun.tahun"
                        class="rounded-lg border-gray-300 text-xs font-bold py-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Scan Masuk</th>
                        <th>Scan Pulang</th>
                        <th>Durasi Kerja</th>
                        <th>Lembur Disetujui</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $search = $this->tableSearch;
                        $bulan = $this->tableFilters['bulan_tahun']['bulan'] ?? null;
                        $tahun = $this->tableFilters['bulan_tahun']['tahun'] ?? null;

                        $attendances = \App\Models\Attendance::with('employee')
                            ->when($search, function ($query) use ($search) {
                                return $query->whereHas('employee', function ($q) use ($search) {
                                    $q->where('nama', 'ilike', "%{$search}%");
                                });
                            })
                            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
                            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
                            ->orderBy('tanggal', 'desc')
                            ->get();

                        $holidayDescriptions = \App\Models\Holiday::query()
                            ->whereIn('tanggal', $attendances->pluck('tanggal')->map(fn($date) => \Carbon\Carbon::parse($date)->toDateString())->all())
                            ->pluck('keterangan', 'tanggal')
                            ->all();
                    @endphp

                    @forelse($attendances as $attendance)
                        <tr class="{{ $attendance->is_holiday ? 'holiday-row' : '' }}">
                            <td>
                                <div class="name-cell">
                                    <b>{{ $attendance->employee->nama }}</b>
                                    <span>ID: {{ $attendance->employee->no_id }}</span>
                                </div>
                            </td>
                            <td class="font-medium">
                                {{ \Carbon\Carbon::parse($attendance->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                @if($attendance->scan_masuk)
                                    <span class="time-badge time-in">{{ $attendance->scan_masuk }}</span>
                                @else
                                    <span class="text-gray-300 italic text-xs">No Scan</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->scan_pulang)
                                    <span class="time-badge time-out">{{ $attendance->scan_pulang }}</span>
                                @else
                                    <span class="text-gray-300 italic text-xs">No Scan</span>
                                @endif
                            </td>
                            <td>
                                <span class="total-duration">{{ number_format($attendance->total_jam_kerja, 1) }} Jam</span>
                            </td>
                            <td>
                                <span class="text-pink-600 font-bold">{{ rtrim(rtrim(number_format((float) $attendance->approved_overtime_hours, 2, '.', ''), '0'), '.') }} Jam</span>
                            </td>
                            <td>
                                @if($attendance->is_holiday)
                                    @php
                                        $attendanceDate = \Carbon\Carbon::parse($attendance->tanggal);
                                        $dateKey = $attendanceDate->toDateString();
                                        $holidayDesc = $holidayDescriptions[$dateKey] ?? null;
                                    @endphp

                                    @if($holidayDesc)
                                        <span class="holiday-badge">{{ strtoupper($holidayDesc) }}</span>
                                    @elseif($attendanceDate->isSunday())
                                        <span class="holiday-badge">MINGGU</span>
                                    @else
                                        <span class="holiday-badge">LIBUR</span>
                                    @endif
                                @else
                                    <span class="text-emerald-500 font-bold text-[10px] uppercase">Normal Day</span>
                                @endif
                            </td>
                            <td>
                                <a
                                    href="{{ \App\Filament\Resources\Attendances\AttendanceResource::getUrl('edit', ['record' => $attendance->id]) }}"
                                    style="display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 8px; background: #eff6ff; color: #1d4ed8; font-size: 0.75rem; font-weight: 800; border: 1px solid #dbeafe; text-decoration: none;"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-400 font-medium italic">
                                Tidak ada data kehadiran yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <x-filament-actions::modals />
</x-filament-panels::page>