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
        $totalAbsensiBulanIni = \App\Models\Attendance::whereBetween('tanggal', [$monthStart, $monthEnd])->count();
        $scanParsialBulanIni = \App\Models\Attendance::whereBetween('tanggal', [$monthStart, $monthEnd])
            ->where(function ($q) {
                $q->whereNull('scan_masuk')->orWhereNull('scan_pulang');
            })
            ->count();
        $persentaseKelengkapanAbsensi = $totalAbsensiBulanIni > 0
            ? (1 - ($scanParsialBulanIni / $totalAbsensiBulanIni)) * 100
            : 100;

        $totalPayrollBulanIni = \App\Models\Payroll::whereBetween('tanggal_selesai', [$monthStart, $monthEnd])->sum('grand_total');
        $rataRataGaji = $karyawanAktif > 0 ? $totalPayrollBulanIni / $karyawanAktif : 0;
        $payrollTerakhir = \App\Models\Payroll::latest('tanggal_selesai')->first();

        $upcomingHolidays = \App\Models\Holiday::query()
            ->whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

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

        $monthlyPartialRaw = \App\Models\Attendance::selectRaw('EXTRACT(MONTH FROM tanggal) as month_num, COUNT(*) as total')
            ->whereBetween('tanggal', [now()->startOfMonth()->subMonths(5)->toDateString(), $monthEnd])
            ->where(function ($q) {
                $q->whereNull('scan_masuk')->orWhereNull('scan_pulang');
            })
            ->groupByRaw('EXTRACT(MONTH FROM tanggal)')
            ->pluck('total', 'month_num')
            ->toArray();

        $monthlyPayrollCompositionRaw = \App\Models\Payroll::selectRaw('EXTRACT(MONTH FROM tanggal_selesai) as month_num, SUM(total_gaji_pokok) as pokok, SUM(total_uang_lembur) as lembur')
            ->whereBetween('tanggal_selesai', [now()->startOfMonth()->subMonths(5)->toDateString(), $monthEnd])
            ->groupByRaw('EXTRACT(MONTH FROM tanggal_selesai)')
            ->get()
            ->keyBy(fn ($row) => (int) $row->month_num);

        $monthlyPayrollSeries = [];
        $monthlyPartialSeries = [];
        $monthlyCompositionSeries = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->copy()->subMonths($i);
            $monthNum = (int) $m->format('n');
            $monthlyPayrollSeries[] = [
                'label' => $m->translatedFormat('M'),
                'value' => (float) ($monthlyPayrollRaw[$monthNum] ?? 0),
            ];

            $monthlyPartialSeries[] = [
                'label' => $m->translatedFormat('M'),
                'value' => (int) ($monthlyPartialRaw[$monthNum] ?? 0),
            ];

            $composition = $monthlyPayrollCompositionRaw->get($monthNum);
            $pokok = (float) ($composition->pokok ?? 0);
            $lembur = (float) ($composition->lembur ?? 0);

            $monthlyCompositionSeries[] = [
                'label' => $m->translatedFormat('M'),
                'pokok' => $pokok,
                'lembur' => $lembur,
                'total' => $pokok + $lembur,
            ];
        }

        $maxPayroll = max(array_map(fn($item) => $item['value'], $monthlyPayrollSeries)) ?: 1;
        $maxPartial = max(array_map(fn($item) => $item['value'], $monthlyPartialSeries)) ?: 1;
        $maxComposition = max(array_map(fn($item) => $item['total'], $monthlyCompositionSeries)) ?: 1;

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

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
        }

        .quick-link {
            display: block;
            border-radius: 0.85rem;
            padding: 0.75rem 0.85rem;
            text-decoration: none;
            color: #1e293b;
            background: #ffffff;
            border: 1px solid #dbeafe;
            box-shadow: 0 8px 20px -18px rgba(37, 99, 235, 0.7);
            transition: all 0.18s ease;
        }

        .quick-link:hover {
            transform: translateY(-2px);
            border-color: #93c5fd;
            background: #f8fbff;
        }

        .quick-label {
            font-size: 0.84rem;
            font-weight: 900;
            color: #1d4ed8;
        }

        .quick-sub {
            margin-top: 0.2rem;
            font-size: 0.74rem;
            color: #64748b;
            line-height: 1.45;
        }

        .health-meter {
            margin-top: 0.6rem;
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .health-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #22c55e, #16a34a);
        }

        .list-mini {
            display: grid;
            gap: 0.55rem;
        }

        .list-mini-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0.7rem;
            padding: 0.62rem 0.72rem;
        }

        .list-mini-label {
            font-size: 0.76rem;
            font-weight: 800;
            color: #334155;
        }

        .list-mini-date {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 700;
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

        .bar.warning {
            background: linear-gradient(180deg, #f59e0b, #d97706);
        }

        .stack-wrap {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            min-height: 8px;
            border-radius: 0.55rem;
            overflow: hidden;
            background: #e2e8f0;
        }

        .stack-part {
            width: 100%;
        }

        .stack-pokok {
            background: linear-gradient(180deg, #1d4ed8, #1e40af);
        }

        .stack-lembur {
            background: linear-gradient(180deg, #22c55e, #16a34a);
        }

        .legend-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.7rem;
            font-size: 0.73rem;
            color: #64748b;
            font-weight: 700;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-flex;
            margin-right: 0.35rem;
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

        html.dark .stack-wrap {
            background: #2c2c31;
        }

        html.dark .quick-link {
            background: #1a1a1f;
            border-color: #24324a;
            color: #f8fafc;
        }

        html.dark .quick-link:hover {
            background: #16213a;
            border-color: #1d4ed8;
        }

        html.dark .quick-label {
            color: #93c5fd;
        }

        html.dark .quick-sub,
        html.dark .list-mini-date {
            color: #a1a1aa;
        }

        html.dark .list-mini-item {
            background: #1f1f23;
            border-color: #2c2c31;
        }

        html.dark .list-mini-label {
            color: #e5e7eb;
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
                <h3 class="panel-title">Aksi Cepat</h3>
                <p class="panel-sub">Shortcut pekerjaan harian tanpa cari menu satu per satu.</p>

                <div class="quick-grid">
                    <a href="{{ \App\Filament\Resources\Attendances\AttendanceResource::getUrl('create') }}" class="quick-link">
                        <div class="quick-label">Input Kehadiran</div>
                        <div class="quick-sub">Tambah data absensi manual.</div>
                    </a>

                    <a href="{{ \App\Filament\Resources\Attendances\AttendanceResource::getUrl('index') }}" class="quick-link">
                        <div class="quick-label">Verifikasi Absensi</div>
                        <div class="quick-sub">Cek scan parsial sebelum payroll.</div>
                    </a>

                    <a href="{{ \App\Filament\Resources\Payrolls\PayrollResource::getUrl('index') }}" class="quick-link">
                        <div class="quick-label">Generate Payroll</div>
                        <div class="quick-sub">Buat rekap gaji periode berjalan.</div>
                    </a>

                    <a href="{{ \App\Filament\Resources\Settings\SettingResource::getUrl('index') }}" class="quick-link">
                        <div class="quick-label">Atur Parameter</div>
                        <div class="quick-sub">Sesuaikan rate lembur/gaji harian.</div>
                    </a>
                </div>
            </article>

            <article class="panel">
                <h3 class="panel-title">Kesehatan Data Absensi</h3>
                <p class="panel-sub">Indikator kualitas data pada bulan aktif.</p>

                <div class="plain-list">
                    <div class="plain-item">
                        <span class="plain-label">Total absensi bulan ini</span>
                        <span class="plain-value">{{ number_format($totalAbsensiBulanIni, 0, ',', '.') }}</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">Scan parsial bulan ini</span>
                        <span class="plain-value">{{ number_format($scanParsialBulanIni, 0, ',', '.') }}</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">Kelengkapan data</span>
                        <span class="plain-value">{{ number_format($persentaseKelengkapanAbsensi, 1, ',', '.') }}%</span>
                    </div>
                </div>

                <div class="health-meter">
                    <div class="health-fill" style="width: {{ max(min($persentaseKelengkapanAbsensi, 100), 0) }}%"></div>
                </div>

                <p class="panel-sub" style="margin-top: 0.6rem; margin-bottom: 0;">
                    Payroll terakhir: {{ $payrollTerakhir ? \Carbon\Carbon::parse($payrollTerakhir->tanggal_selesai)->translatedFormat('d M Y') : 'Belum ada' }}
                </p>
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

        <section class="section-grid">
            <article class="panel">
                <h3 class="panel-title">Tren Scan Parsial 6 Bulan</h3>
                <p class="panel-sub">Jumlah data absensi yang scan masuk/pulang tidak lengkap per bulan.</p>

                <div class="mini-chart monthly">
                    @foreach ($monthlyPartialSeries as $point)
                        @php
                            $height = max(($point['value'] / $maxPartial) * 120, 8);
                        @endphp
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $point['value'] }}</div>
                            <div class="bar warning" style="height: {{ $height }}px"></div>
                            <div class="bar-label">{{ $point['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="panel">
                <h3 class="panel-title">Komposisi Gaji per Bulan</h3>
                <p class="panel-sub">Perbandingan gaji pokok dan uang lembur selama 6 bulan terakhir.</p>

                <div class="mini-chart monthly">
                    @foreach ($monthlyCompositionSeries as $point)
                        @php
                            $stackHeight = max(($point['total'] / $maxComposition) * 120, 8);
                            $pokokRatio = $point['total'] > 0 ? ($point['pokok'] / $point['total']) : 0;
                            $lemburRatio = $point['total'] > 0 ? ($point['lembur'] / $point['total']) : 0;
                        @endphp
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $point['total'] > 0 ? number_format($point['total'] / 1000000, 1, ',', '.') . ' jt' : '0' }}</div>
                            <div class="stack-wrap" style="height: {{ $stackHeight }}px">
                                <div class="stack-part stack-lembur" style="height: {{ max($lemburRatio * 100, 0) }}%"></div>
                                <div class="stack-part stack-pokok" style="height: {{ max($pokokRatio * 100, 0) }}%"></div>
                            </div>
                            <div class="bar-label">{{ $point['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="legend-row">
                    <span><span class="legend-dot" style="background:#1d4ed8;"></span>Gaji Pokok</span>
                    <span><span class="legend-dot" style="background:#16a34a;"></span>Uang Lembur</span>
                </div>
            </article>
        </section>

        <section class="section-grid">
            <article class="panel">
                <h3 class="panel-title">Agenda Libur Mendatang</h3>
                <p class="panel-sub">5 tanggal libur terdekat dari hari ini.</p>

                <div class="list-mini">
                    @forelse ($upcomingHolidays as $holiday)
                        <div class="list-mini-item">
                            <div>
                                <div class="list-mini-label">{{ $holiday->keterangan }}</div>
                                <div class="list-mini-date">{{ \Carbon\Carbon::parse($holiday->tanggal)->translatedFormat('l') }}</div>
                            </div>
                            <div class="list-mini-date">{{ \Carbon\Carbon::parse($holiday->tanggal)->translatedFormat('d M Y') }}</div>
                        </div>
                    @empty
                        <div class="list-mini-item">
                            <div class="list-mini-label">Belum ada agenda libur.</div>
                            <div class="list-mini-date">-</div>
                        </div>
                    @endforelse
                </div>
            </article>

            <article class="panel">
                <h3 class="panel-title">Catatan Operasional</h3>
                <p class="panel-sub">Checklist cepat sebelum proses payroll.</p>

                <div class="plain-list">
                    <div class="plain-item">
                        <span class="plain-label">1. Verifikasi scan parsial</span>
                        <span class="plain-value">{{ $scanParsialBulanIni > 0 ? 'Perlu cek' : 'Aman' }}</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">2. Update kalender libur</span>
                        <span class="plain-value">Rutin bulanan</span>
                    </div>

                    <div class="plain-item">
                        <span class="plain-label">3. Validasi rate gaji/lembur</span>
                        <span class="plain-value">Sebelum generate</span>
                    </div>
                </div>
            </article>
        </section>
    </div>
</x-filament-panels::page>