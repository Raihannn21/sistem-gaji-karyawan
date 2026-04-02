<x-filament-panels::page class="fi-dashboard-page">
    <style>
        .custom-dash-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
            margin-bottom: 2rem;
        }

        .custom-dash-hero .blob1 {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(40px);
        }

        .custom-dash-hero .blob2 {
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(147, 197, 253, 0.2);
            border-radius: 50%;
            filter: blur(30px);
        }

        .hero-date-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            padding: 8px 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .custom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .custom-card {
            background-color: var(--fi-bg-content, white);
            border: 1px solid var(--fi-border-color, #e5e7eb);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .custom-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .icon-svg {
            width: 24px;
            height: 24px;
        }

        /* Dark mode overrides automatically provided by Filament's CSS variables */
        html.dark .custom-card {
            background-color: #18181b;
            border-color: #27272a;
        }

        html.dark .custom-dash-hero {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
    </style>

    {{-- Hero Banner Modern --}}
    <div class="custom-dash-hero">
        <div class="blob1"></div>
        <div class="blob2"></div>

        <div
            style="position: relative; z-index: 10; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800; margin: 0 0 10px 0; letter-spacing: -0.5px;">
                    Halo, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p
                    style="font-size: 1rem; color: rgba(255,255,255,0.9); margin: 0; max-width: 600px; line-height: 1.6;">
                    Selamat datang di <b>Sistem Gaji Cimol Bojot Aa</b>. Sistem siap membantu Anda merangkum data
                    kehadiran, kalkulasi gaji, hingga pembuatan slip.
                </p>
            </div>
            <div>
                <div class="hero-date-badge">
                    <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Stats Grid --}}
    <div class="custom-grid">
        {{-- Card 1 --}}
        <div class="custom-card">
            <div class="icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div style="font-size: 0.875rem; font-weight: 600; color: var(--fi-text-subtle);">Karyawan Aktif</div>
            <div style="font-size: 1.875rem; font-weight: 800; color: var(--fi-text-color);">
                {{ \App\Models\Employee::where('is_active', true)->count() }}
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="custom-card">
            <div class="icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="font-size: 0.875rem; font-weight: 600; color: var(--fi-text-subtle);">Total Log Kehadiran</div>
            <div style="font-size: 1.875rem; font-weight: 800; color: var(--fi-text-color);">
                {{ number_format(\App\Models\Attendance::count(), 0, ',', '.') }}
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="custom-card">
            <div class="icon-box" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div style="font-size: 0.875rem; font-weight: 600; color: var(--fi-text-subtle);">Riwayat Gaji Dicetak</div>
            <div style="font-size: 1.875rem; font-weight: 800; color: var(--fi-text-color);">
                {{ \App\Models\Payroll::count() }}
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="custom-card">
            <div class="icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <svg class="icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div style="font-size: 0.875rem; font-weight: 600; color: var(--fi-text-subtle);">Jadwal Hari Libur</div>
            <div style="font-size: 1.875rem; font-weight: 800; color: var(--fi-text-color);">
                {{ \App\Models\Holiday::count() }}
            </div>
        </div>
    </div>

    {{-- Widget Bawaan Standar Tetap Ditampilkan Di Bawah --}}
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="$this->getWidgetData()"
        :widgets="$this->getVisibleWidgets()" />
</x-filament-panels::page>