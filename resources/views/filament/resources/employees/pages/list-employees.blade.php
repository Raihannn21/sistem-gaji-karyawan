<x-filament-panels::page>
    <style>
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.50rem;
            margin-top: 1.5rem;
        }

        .employee-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.75rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .employee-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .avatar-circle {
            width: 56px;
            height: 56px;
            border-radius: 1rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .emp-name {
            font-size: 1.25rem;
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .emp-id {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #475569;
        }

        .info-icon {
            width: 16px;
            height: 16px;
            opacity: 0.4;
        }

        .status-pill {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-active {
            background: #ecfdf5;
            color: #059669;
        }

        .status-inactive {
            background: #fef2f2;
            color: #dc2626;
        }

        .card-actions {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            gap: 10px;
        }

        .action-link {
            flex: 1;
            padding: 8px;
            border-radius: 10px;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: white;
        }

        html.dark .employee-card {
            background: #1e1e2e;
            border-color: #2e2e3e;
        }

        html.dark .emp-name {
            color: #f8fafc;
        }

        html.dark .card-actions {
            border-color: rgba(255, 255, 255, 0.05);
        }

        .search-container {
            max-width: 500px;
            margin-bottom: 1rem;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 3rem;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            background: white;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 1rem center;
            background-size: 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #3b82f6;
            ring: 2px rgba(59, 130, 246, 0.1);
            outline: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        html.dark .search-input {
            background-color: #18181b;
            border-color: #27272a;
            color: white;
        }
    </style>

    {{-- Search Bar --}}
    <div class="search-container">
        <input type="text" wire:model.live.debounce.300ms="tableSearch" placeholder="Cari nama atau ID karyawan..."
            class="search-input">
    </div>

    @php
        $search = $this->tableSearch;
        $employees = \App\Models\Employee::when($search, function ($query) use ($search) {
            return $query->where('nama', 'ilike', "%{$search}%")
                ->orWhere('no_id', 'ilike', "%{$search}%")
                ->orWhere('emp_no', 'ilike', "%{$search}%");
        })
            ->orderBy('nama', 'asc')
            ->get();
    @endphp

    <div class="employee-grid">
        @foreach($employees as $employee)
            <div class="employee-card">
                {{-- Status --}}
                @if($employee->is_active)
                    <span class="status-pill status-active">Aktif</span>
                @else
                    <span class="status-pill status-inactive">Nonaktif</span>
                @endif

                {{-- Avatar --}}
                <div class="avatar-circle">
                    {{ substr($employee->nama, 0, 1) }}
                </div>

                <div>
                    <div class="emp-id">#ID {{ $employee->no_id ?? 'N/A' }} | NO {{ $employee->emp_no ?? '-' }}</div>
                    <h3 class="emp-name">{{ $employee->nama }}</h3>
                </div>

                <div class="info-row">
                    <span>📧 {{ $employee->email ?? '-' }}</span>
                </div>

                <div class="info-row">
                    <span>📞 {{ $employee->no_hp ?? '-' }}</span>
                </div>

                <div class="card-actions">
                    <a href="{{ \App\Filament\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $employee->id]) }}"
                        class="action-link btn-edit">
                        Ubah Profil
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>