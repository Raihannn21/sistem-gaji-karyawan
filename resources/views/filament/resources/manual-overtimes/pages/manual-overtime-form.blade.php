<x-filament-panels::page>
    <style>
        :root {
            --ui-text-main: #0f172a;
            --ui-text-soft: #64748b;
            --ui-border: #e5e7eb;
            --ui-bg-card: #ffffff;
        }

        .form-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        @media (min-width: 1100px) {
            .form-wrapper {
                grid-template-columns: 1.3fr 0.85fr;
            }
        }

        .main-card {
            background: var(--ui-bg-card);
            border-radius: 1.25rem;
            border: 1px solid var(--ui-border);
            padding: 2rem;
            box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.12);
        }

        .side-card {
            background: linear-gradient(145deg, #0f172a, #1e293b);
            color: white;
            border-radius: 1.25rem;
            padding: 1.75rem;
            position: sticky;
            top: 1.75rem;
            height: fit-content;
            box-shadow: 0 20px 30px -15px rgba(15, 23, 42, 0.6);
        }

        .kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            font-weight: 800;
            color: #3b82f6;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
        }

        .title {
            margin-top: 1rem;
            font-size: 1.9rem;
            line-height: 1.1;
            font-weight: 900;
            color: var(--ui-text-main);
            letter-spacing: -0.03em;
        }

        .subtitle {
            margin-top: 0.5rem;
            color: var(--ui-text-soft);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .form-action-wrap {
            margin-top: 1.8rem;
            padding-top: 1.4rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .guide-title { font-size: 1.05rem; font-weight: 900; color: #f8fafc; }
        .guide-list { margin-top: 1rem; display: grid; gap: 0.85rem; font-size: 0.88rem; color: #cbd5e1; line-height: 1.5; }

        .guide-note {
            margin-top: 1.1rem;
            border-top: 1px solid rgba(148, 163, 184, 0.3);
            padding-top: 1rem;
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.55;
        }

        html.dark {
            --ui-text-main: #f8fafc;
            --ui-text-soft: #a1a1aa;
            --ui-border: #27272a;
            --ui-bg-card: #18181b;
        }

        html.dark .main-card { border-color: #27272a; }
        html.dark .kicker { background: #172554; border-color: #1d4ed8; color: #93c5fd; }
        html.dark .form-action-wrap { border-color: #27272a; }
        html.dark .side-card {
            background: linear-gradient(145deg, #0b1220, #131b2c);
            box-shadow: 0 20px 30px -15px rgba(2, 6, 23, 0.75);
        }
    </style>

    <div class="form-wrapper">
        <div class="main-card">
            <span class="kicker">Operasional > Lembur</span>

            <h1 class="title">
                {{ isset($this->record) ? 'Perbarui Lembur Manual' : 'Input Lembur Manual Baru' }}
            </h1>

            <p class="subtitle">
                Lembur dibayar hanya dari input manual admin. Jam lembur wajib bilangan bulat per jam dan otomatis masuk akumulasi periode payroll.
            </p>

            <form wire:submit.prevent="{{ isset($this->record) ? 'save' : 'create' }}" class="mt-8">
                {{ $this->form }}

                <div class="form-action-wrap">
                    @foreach ($this->getFormActions() as $action)
                        {{ $action }}
                    @endforeach
                </div>
            </form>
        </div>

        <aside class="side-card">
            <h3 class="guide-title">Aturan Lembur</h3>

            <div class="guide-list">
                <div>1. Pilih karyawan aktif dengan status PHL/PKWT.</div>
                <div>2. Tentukan jenis lembur: hari biasa atau hari libur.</div>
                <div>3. Jam lembur hanya bilangan bulat (1, 2, 3, dst).</div>
                <div>4. Saat payroll periode sudah final, data tidak dapat diubah.</div>
            </div>

            <p class="guide-note">
                Gunakan keterangan untuk catatan persetujuan atasan atau referensi dokumen pendukung lembur.
            </p>
        </aside>
    </div>
</x-filament-panels::page>
