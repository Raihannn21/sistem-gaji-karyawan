<x-filament-panels::page>
    <style>
        :root {
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
        }

        .fi-header {
            display: none !important;
        }

        .top-action-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .top-action-btn {
            border: none;
            border-radius: 0.75rem;
            padding: 0.66rem 1rem;
            font-size: 0.78rem;
            font-weight: 800;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .top-action-btn:hover {
            transform: translateY(-1px);
        }

        .btn-import { background: #16a34a; }
        .btn-import:hover { background: #15803d; }

        .btn-export { background: #0ea5e9; }
        .btn-export:hover { background: #0284c7; }

        .btn-create { background: #2563eb; }
        .btn-create:hover { background: #1d4ed8; }

        .page-heading-wrap {
            margin-bottom: 1rem;
        }

        .page-kicker {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.35rem;
        }

        .page-title {
            font-size: 2.18rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: var(--ui-text-main);
            line-height: 1.05;
        }

        .page-subtitle {
            margin-top: 0.45rem;
            font-size: 0.92rem;
            color: var(--ui-text-soft);
            max-width: 680px;
        }

        .employee-container {
            background: var(--ui-bg-card);
            border-radius: 1.25rem;
            border: 1px solid var(--ui-border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-top: 1rem;
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

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

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
            vertical-align: middle;
        }

        .name-main {
            font-weight: 700;
            color: #0f172a;
        }

        .name-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .badge {
            display: inline-flex;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid transparent;
        }

        .badge-status {
            background: #eef2ff;
            color: #3730a3;
            border-color: #e0e7ff;
        }

        .badge-active {
            background: #ecfdf5;
            color: #166534;
            border-color: #bbf7d0;
        }

        .badge-inactive {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

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

        .btn-edit:hover {
            background: #dbeafe;
        }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        html.dark .page-kicker,
        html.dark .page-subtitle {
            color: #a1a1aa;
        }

        html.dark .search-row {
            background: #1f1f23;
            border-color: #27272a;
        }

        html.dark .search-input {
            border-color: #3f3f46;
            color: #f8fafc;
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

        html.dark .name-main {
            color: #f8fafc;
        }

        html.dark .badge-status {
            background: rgba(55, 48, 163, 0.25);
            color: #c7d2fe;
            border-color: rgba(165, 180, 252, 0.35);
        }

        html.dark .badge-active {
            background: rgba(22, 163, 74, 0.2);
            color: #86efac;
            border-color: rgba(74, 222, 128, 0.35);
        }

        html.dark .badge-inactive {
            background: rgba(220, 38, 38, 0.2);
            color: #fca5a5;
            border-color: rgba(248, 113, 113, 0.35);
        }

        html.dark .btn-edit {
            background: rgba(30, 64, 175, 0.35);
            color: #bfdbfe;
            border-color: rgba(147, 197, 253, 0.35);
        }
    </style>

    <div class="page-heading-wrap">
        <span class="page-kicker">Data Master</span>
        <h1 class="page-title">Data Karyawan</h1>
        <p class="page-subtitle">Kelola profil karyawan, import data massal, dan export data untuk kebutuhan administrasi.</p>
    </div>

    <div class="top-action-row">
        <button type="button" class="top-action-btn btn-import" wire:click="mountAction('importEmployees')">
            Import Karyawan
        </button>

        <button type="button" class="top-action-btn btn-export" wire:click="mountAction('exportEmployees')">
            Export Karyawan
        </button>

        <button type="button" class="top-action-btn btn-create" wire:click="mountAction('create')">
            Tambah Karyawan
        </button>
    </div>

    @php
        $search = $this->tableSearch;
        $status = $this->tableFilters['employment_status']['value'] ?? null;

        $employees = \App\Models\Employee::when($search, function ($query) use ($search) {
            return $query->where('nama', 'ilike', "%{$search}%")
                ->orWhere('no_id', 'ilike', "%{$search}%")
                ->orWhere('emp_no', 'ilike', "%{$search}%");
        })
            ->when($status, fn ($query) => $query->where('employment_status', $status))
            ->orderBy('nama', 'asc')
            ->get();
    @endphp

    <div class="employee-container">
        <div class="search-row">
            <input type="text" wire:model.live.debounce.300ms="tableSearch" class="search-input" placeholder="Cari nama, No ID, atau Emp No...">

            <select wire:model.live="tableFilters.employment_status.value" class="search-input" style="max-width: 180px;">
                <option value="">Semua Status</option>
                <option value="PHL">PHL</option>
                <option value="PKWT">PKWT</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Emp No</th>
                        <th>No ID</th>
                        <th>Nama Karyawan</th>
                        <th>Status Kerja</th>
                        <th>Departemen</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td style="font-weight: 700;">{{ $employee->emp_no }}</td>
                            <td style="font-weight: 700;">{{ $employee->no_id }}</td>
                            <td>
                                <div class="name-main">{{ $employee->nama }}</div>
                                <div class="name-sub">NIK: {{ $employee->nik ?: '-' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-status">{{ $employee->employment_status }}</span>
                            </td>
                            <td>{{ $employee->departemen ?: '-' }}</td>
                            <td>
                                <div>{{ $employee->email ?: '-' }}</div>
                                <div class="name-sub">{{ $employee->no_hp ?: '-' }}</div>
                            </td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ \App\Filament\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $employee->id]) }}" class="btn-edit">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:2rem; color:#94a3b8; font-style:italic;">
                                Belum ada data karyawan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>