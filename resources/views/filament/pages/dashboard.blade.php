<x-filament-panels::page class="fi-dashboard-page">
    @php
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $karyawanAktif = \App\Models\Employee::where('is_active', true)->count();
        $hadirHariIni = \App\Models\Attendance::whereDate('tanggal', $today)->where('is_holiday', false)->count();
        $scanParsialHariIni = \App\Models\Attendance::whereDate('tanggal', $today)
            ->where(function ($q) {
                $q->whereNull('scan_masuk')->orWhereNull('scan_pulang');
            })
            ->count();
        $totalPayrollBulanIni = \App\Models\Payroll::whereBetween('tanggal_selesai', [$monthStart, $monthEnd])->sum('grand_total');
        $rataRataGaji = $karyawanAktif > 0 ? $totalPayrollBulanIni / $karyawanAktif : 0;

        $dailyAttendanceRaw = \App\Models\Attendance::selectRaw('tanggal, COUNT(*) as total')
            ->whereBetween('tanggal', [now()->subDays(6)->toDateString(), $today])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

        $dailyAttendanceSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $key = $d->toDateString();
            $dailyAttendanceSeries[] = [
                'label' => $d->translatedFormat('D'),
                'value' => (int) ($dailyAttendanceRaw[$key] ?? 0),
            ];
        }

        $maxDailyAttendance = max(array_map(fn($item) => $item['value'], $dailyAttendanceSeries)) ?: 1;

        $monthlyPayrollRaw = \App\Models\Payroll::selectRaw('EXTRACT(MONTH FROM tanggal_selesai) as month_num, SUM(grand_total) as total')
            ->whereBetween('tanggal_selesai', [now()->startOfMonth()->subMonths(5)->toDateString(), $monthEnd])
            ->groupByRaw('EXTRACT(MONTH FROM tanggal_selesai)')
            ->pluck('total', 'month_num')
            ->toArray();

        $monthlyPayrollSeries = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->copy()->subMonths($i);
            $monthNum = (int) $m->format('n');
            $monthlyPayrollSeries[] = [
                'label' => $m->translatedFormat('M'),
                'value' => (float) ($monthlyPayrollRaw[$monthNum] ?? 0),
            ];
        }

        $maxPayroll = max(array_map(fn($item) => $item['value'], $monthlyPayrollSeries)) ?: 1;

        $topLembur = \App\Models\Attendance::with('employee')
            ->whereBetween('tanggal', [$monthStart, $monthEnd])
            ->orderByDesc('approved_overtime_hours')
            ->limit(5)
            ->get();
    @endphp

    <style>
        .fi-header {
            display: none !important;
        }

        .dash-shell {
            display: grid;
            gap: 1.25rem;
        }

        .dash-hero {
            background: linear-gradient(120deg, #0f172a 0%, #1d4ed8 60%, #0891b2 100%);
            color: white;
            border-radius: 1.2rem;
            padding: 1.6rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dash-hero h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .dash-hero p {
            margin-top: 0.45rem;
            max-width: 640px;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.55;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.12);
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 0.9rem;
        }

        .kpi-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 1rem 1.1rem;
            box-shadow: 0 8px 18px -14px rgba(15, 23, 42, 0.4);
        }

        .kpi-label {
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.66rem;
            letter-spacing: 0.11em;
            font-weight: 900;
        }

        .kpi-value {
            margin-top: 0.38rem;
            font-size: 1.85rem;
            line-height: 1;
            font-weight: 900;
            color: #0f172a;
        }

        .kpi-note {
            margin-top: 0.35rem;
            font-size: 0.78rem;
            color: #64748b;
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 1rem;
        }

        .panel {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 1rem;
        }

        .panel-title {
            font-size: 0.95rem;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .panel-sub {
            font-size: 0.78rem;
            color: #64748b;
            margin-bottom: 0.9rem;
        }

        .mini-chart {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 0.55rem;
            align-items: end;
            height: 160px;
        }

        .mini-chart.monthly {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }

        .bar-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
        }

        .bar {
            width: 100%;
            border-radius: 0.55rem;
            min-height: 8px;
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
        }

        .bar.secondary {
            background: linear-gradient(180deg, #0ea5e9, #0284c7);
        }

        .bar-value {
            font-size: 0.74rem;
            font-weight: 800;
            color: #1e293b;
        }

        .bar-label {
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 700;
        }

        .plain-list {
            display: grid;
            gap: 0.7rem;
        }

        .plain-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.6rem;
        }

        .plain-label {
            font-size: 0.8rem;
            color: #475569;
            font-weight: 700;
        }

        .plain-value {
            font-size: 0.86rem;
            color: #0f172a;
            font-weight: 900;
        }

        .table-wrap {
            margin-top: 0.35rem;
            overflow-x: auto;
        }

        .compact-table {
            width: 100%;
            border-collapse: collapse;
        }

        .compact-table th,
        .compact-table td {
            padding: 0.6rem 0.55rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.77rem;
            text-align: left;
        }

        .compact-table th {
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 800;
            font-size: 0.66rem;
        }

        .compact-table td:last-child,
        .compact-table th:last-child {
            text-align: right;
        }

        @media (max-width: 980px) {
            .section-grid {
                grid-template-columns: 1fr;
            }
        }

        html.dark .kpi-card,
        html.dark .panel {
            background: #18181b;
            border-color: #27272a;
        }

        html.dark .kpi-value,
        html.dark .panel-title,
        html.dark .bar-value,
        html.dark .plain-value {
            color: #f8fafc;
        }

        html.dark .kpi-label,
        html.dark .kpi-note,
        html.dark .panel-sub,
        html.dark .bar-label,
        html.dark .plain-label,
        html.dark .compact-table th {
            color: #a1a1aa;
        }

        html.dark .plain-item {
            background: #1f1f23;
            border-color: #2c2c31;
        }

        html.dark .compact-table td,
        html.dark .compact-table th {
            border-color: #2c2c31;
        }
    </style>

    <div class="dash-shell">
        <section class="dash-hero">
            <div>
                <h1>Dashboard Operasional Gaji</h1>
                <p>
                    Ringkasan hari ini untuk absensi, lembur, dan pengeluaran payroll. Halaman ini dibuat agar admin bisa cepat ambil keputusan tanpa perlu buka banyak menu.
                </p>
            </div>
            <span class="hero-badge">{{ now()->translatedFormat('d F Y') }}</span>
        </section>

        <section class="kpi-grid">
            <article class="kpi-card">
                <div class="kpi-label">Karyawan Aktif</div>
                <div class="kpi-value">{{ number_format($karyawanAktif, 0, ',', '.') }}</div>
                <div class="kpi-note">Basis data karyawan aktif saat ini.</div>
            </article>

            <article class="kpi-card">
                <div class="kpi-label">Hadir Hari Ini</div>
                <div class="kpi-value">{{ number_format($hadirHariIni, 0, ',', '.') }}</div>
                <div class="kpi-note">Total log hadir non-libur pada hari ini.</div>
            </article>

            <article class="kpi-card">
                <div class="kpi-label">Payroll Bulan Ini</div>
                <div class="kpi-value">Rp {{ number_format($totalPayrollBulanIni, 0, ',', '.') }}</div>
                <div class="kpi-note">Total pengeluaran gaji periode bulan berjalan.</div>
            </article>

            <article class="kpi-card">
                <div class="kpi-label">Rata-rata per Karyawan</div>
                <div class="kpi-value">Rp {{ number_format($rataRataGaji, 0, ',', '.') }}</div>
                <div class="kpi-note">Estimasi rata-rata gaji per karyawan aktif.</div>
            </article>
        </section>

        <section class="section-grid">
            <article class="panel">
                <h3 class="panel-title">Tren Kehadiran 7 Hari Terakhir</h3>
                <p class="panel-sub">Visual cepat jumlah log kehadiran per hari.</p>

                <div class="mini-chart">
                    @foreach ($dailyAttendanceSeries as $point)
                        @php
                            $height = max(($point['value'] / $maxDailyAttendance) * 120, 8);
                        @endphp
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $point['value'] }}</div>
                            <div class="bar" style="height: {{ $height }}px"></div>
                            <div class="bar-label">{{ $point['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="panel">
                <h3 class="panel-title">Ringkasan Tanpa Grafik</h3>
                <p class="panel-sub">Panel cepat untuk kondisi yang perlu tindakan admin.</p>

                <div class="plain-list">
                    <div class="plain-item">
                        <span class="plain-label">Log scan parsial hari ini</span>
                        <span class="plain-value">{{ number_format($scanParsialHariIni, 0, ',', '.') }}</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">Jumlah data libur terdaftar</span>
                        <span class="plain-value">{{ number_format(\App\Models\Holiday::count(), 0, ',', '.') }}</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">Total payroll diterbitkan</span>
                        <span class="plain-value">{{ number_format(\App\Models\Payroll::count(), 0, ',', '.') }} laporan</span>
                    </div>
                </div>
            </article>
        </section>

        <section class="section-grid">
            <article class="panel">
                <h3 class="panel-title">Tren Nominal Payroll 6 Bulan</h3>
                <p class="panel-sub">Perbandingan nominal pengeluaran payroll bulanan.</p>

                <div class="mini-chart monthly">
                    @foreach ($monthlyPayrollSeries as $point)
                        @php
                            $height = max(($point['value'] / $maxPayroll) * 120, 8);
                        @endphp
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $point['value'] > 0 ? number_format($point['value'] / 1000000, 1, ',', '.') . ' jt' : '0' }}</div>
                            <div class="bar secondary" style="height: {{ $height }}px"></div>
                            <div class="bar-label">{{ $point['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="panel">
                <h3 class="panel-title">Top Lembur Bulan Ini</h3>
                <p class="panel-sub">5 data kehadiran dengan lembur disetujui tertinggi.</p>

                <div class="table-wrap">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topLembur as $item)
                                <tr>
                                    <td>{{ $item->employee->nama ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td>{{ rtrim(rtrim(number_format((float) $item->approved_overtime_hours, 2, '.', ''), '0'), '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Belum ada data lembur pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </div>
</x-filament-panels::page>