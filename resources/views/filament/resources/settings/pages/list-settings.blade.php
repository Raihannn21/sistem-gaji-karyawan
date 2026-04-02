<x-filament-panels::page>
    <style>
        .settings-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .settings-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
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
            font-size: 0.7rem;
            font-weight: 800;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .setting-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
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
            text-decoration: none;
            transition: all 0.2s;
        }

        .edit-btn:hover {
            background-color: #2563eb;
            color: white;
        }

        html.dark .settings-card {
            background: #18181b;
            border-color: #27272a;
        }

        html.dark .setting-value {
            color: white;
        }

        html.dark .edit-btn {
            background-color: #1e3a8a;
            color: #93c5fd;
        }
    </style>

    @php
        $settings = \App\Models\Setting::all();
    @endphp

    <div class="settings-container">
        @foreach($settings as $setting)
            <div class="settings-card">
                <h3 class="setting-label">{{ str_replace('_', ' ', $setting->key) }}</h3>

                <div class="setting-value">
                    <span class="currency-symbol">Rp</span>
                    {{ number_format((float) $setting->value, 0, ',', '.') }}
                </div>

                <a href="{{ \App\Filament\Resources\Settings\SettingResource::getUrl('edit', ['record' => $setting->id]) }}"
                    class="edit-btn">
                    UBAH NILAI
                </a>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>