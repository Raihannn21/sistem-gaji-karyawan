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
        .page-subtitle {
            margin-top: 0.45rem;
            font-size: 0.92rem;
            color: var(--ui-text-soft);
            max-width: 720px;
        }

        .settings-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .settings-card {
            background: var(--ui-bg-card);
            border-radius: 1rem;
            border: 1px solid var(--ui-border);
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            position: relative;
        }

        .settings-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .setting-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--ui-text-soft);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .setting-desc {
            margin-top: 0.45rem;
            font-size: 0.78rem;
            color: var(--ui-text-soft);
            line-height: 1.45;
            min-height: 36px;
        }

        .setting-value {
            font-size: 1.42rem;
            font-weight: 800;
            color: var(--ui-text-main);
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .currency-symbol {
            font-size: 0.875rem;
            color: #9ca3af;
            font-weight: 600;
        }

        .edit-btn {
            margin-top: 1.25rem;
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.8rem;
            background-color: #eff6ff;
            color: #2563eb;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid #bfdbfe;
            text-decoration: none;
            transition: all 0.2s;
        }

        .edit-btn:hover {
            background-color: #2563eb;
            color: white;
        }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        html.dark .settings-card {
            border-color: #27272a;
        }

        html.dark .edit-btn {
            background-color: rgba(30, 58, 138, 0.4);
            color: #93c5fd;
            border-color: rgba(147, 197, 253, 0.35);
        }
    </style>

    <div class="page-heading-wrap">
        <span class="page-kicker">Data Master > Konfigurasi</span>
        <h1 class="page-title">Pengaturan Penggajian</h1>
        <p class="page-subtitle">Semua nilai parameter gaji disesuaikan dari sini dan langsung mempengaruhi proses generate payroll.</p>
    </div>

    @php
        $settings = \App\Models\Setting::all();

        $prettyLabels = [
            \App\Models\Setting::KEY_GAJI_HARIAN_PHL => 'Gaji Harian PHL',
            \App\Models\Setting::KEY_GAJI_HARIAN_PKWT => 'Gaji Harian PKWT',
            \App\Models\Setting::KEY_RATE_LEMBUR_BIASA_PHL => 'Rate Lembur Biasa PHL',
            \App\Models\Setting::KEY_RATE_LEMBUR_BIASA_PKWT => 'Rate Lembur Biasa PKWT',
            \App\Models\Setting::KEY_RATE_LEMBUR_LIBUR_PHL => 'Rate Lembur Libur PHL',
            \App\Models\Setting::KEY_RATE_LEMBUR_LIBUR_PKWT => 'Rate Lembur Libur PKWT',
            \App\Models\Setting::KEY_JAM_KERJA_NORMAL => 'Jam Kerja Normal',
        ];
    @endphp

    <div class="settings-container">
        @foreach($settings as $setting)
            <div class="settings-card">
                <h3 class="setting-label">{{ $prettyLabels[$setting->key] ?? str_replace('_', ' ', $setting->key) }}</h3>

                <p class="setting-desc">{{ $setting->description ?: 'Parameter sistem penggajian.' }}</p>

                <div class="setting-value">
                    @if($setting->key === \App\Models\Setting::KEY_JAM_KERJA_NORMAL)
                        {{ number_format((float) $setting->value, 0, ',', '.') }} Jam
                    @else
                        <span class="currency-symbol">Rp</span>
                        {{ number_format((float) $setting->value, 0, ',', '.') }}
                    @endif
                </div>

                <a href="{{ \App\Filament\Resources\Settings\SettingResource::getUrl('edit', ['record' => $setting->id]) }}"
                    class="edit-btn">
                    UBAH NILAI
                </a>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>