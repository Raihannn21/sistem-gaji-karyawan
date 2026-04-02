<x-filament-panels::page>
    <style>
        .edit-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        @media (min-width: 1024px) {
            .edit-wrapper { grid-template-columns: 1.6fr 1fr; }
        }
        
        .form-panel {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid #e5e7eb;
            padding: 2.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .preview-sticky {
            position: sticky;
            top: 2rem;
        }

        .premium-card {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            border-radius: 1.5rem;
            padding: 2.25rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
        }
        
        /* Glass Decoration - Pengganti Icon agar tidak eror */
        .glass-orb {
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
            border-radius: 50%;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .tag-status {
            display: inline-flex;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
        }

        .card-label {
            font-size: 1.25rem;
            font-weight: 800;
            margin-top: 1.5rem;
            color: white;
            line-height: 1.2;
        }

        .card-price {
            font-size: 2.75rem;
            font-weight: 950;
            letter-spacing: -0.05em;
            margin-top: 1rem;
            display: flex;
            align-items: baseline;
            gap: 6px;
        }
        .card-price .rp { font-size: 1.25rem; color: #64748b; font-weight: 600; }

        .hint-container {
            background: #f8fafc;
            border-radius: 1.25rem;
            padding: 1.5rem;
            border-left: 5px solid #3b82f6;
            font-size: 0.85rem;
            color: #475569;
            margin-top: 1.5rem;
            line-height: 1.6;
        }

        .action-flex {
            margin-top: 2.5rem;
            display: flex;
            gap: 12px;
            border-top: 1px solid #f1f5f9;
            padding-top: 2rem;
        }

        html.dark .form-panel { background: #18181b; border-color: #27272a; }
        html.dark .hint-container { background: #1f1f23; color: #9ca3af; border-color: #1e3a8a; }
    </style>

    <div class="edit-wrapper">
        {{-- KIRI: FORM --}}
        <div class="form-panel shadow-sm">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white leading-tight">Konfigurasi Sistem</h2>
                <p class="text-slate-500 text-sm mt-1">Lakukan pembaruan nilai parameter finansial di bawah ini.</p>
            </div>

            <form wire:submit.prevent="save">
                {{ $this->form }}

                <div class="action-flex">
                    @foreach ($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </form>
        </div>

        {{-- KANAN: PREVIEW PREMUM --}}
        <div class="preview-sticky">
            <div class="premium-card">
                <div class="glass-orb"></div>
                
                <div>
                    <span class="tag-status">Live Configuration</span>
                    <h3 class="card-label">{{ $record->label }}</h3>
                </div>

                @php
                    $isDuration = str_contains(strtolower($record->key), 'jam') || str_contains(strtolower($record->label), 'jam');
                @endphp

                <div class="card-price">
                    @if(!$isDuration)
                        <span class="rp">Rp</span>{{ number_format((float)$record->value, 0, ',', '.') }}
                    @else
                        {{ $record->value }} <span class="rp">Jam / Shift</span>
                    @endif
                </div>

                <div style="font-size: 0.8rem; font-weight: 700; color: #10b981; display: flex; align-items: center; gap: 8px;">
                    <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                    Parameter Sistem Aktif
                </div>
            </div>

            <div class="hint-container shadow-sm">
                @if($isDuration)
                    <p style="color: #4f46e5;"><b>⚠️ Satuan Waktu:</b> Nilai ini menentukan batas jam kerja normal sebelum sistem mulai menghitung lembur karyawan.</p>
                @else
                    <p><b>💡 Penting:</b> Perubahan nilai Rp ini akan langsung berdampak pada kalkulasi total gaji yang terbit di laporan payroll.</p>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
