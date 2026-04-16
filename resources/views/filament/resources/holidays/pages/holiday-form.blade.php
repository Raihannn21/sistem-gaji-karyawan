<x-filament-panels::page>
    <style>
        .form-wrapper {
            max-width: 980px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        @media (min-width: 1024px) {
            .form-wrapper {
                grid-template-columns: 1.25fr 0.85fr;
            }
        }

        .main-card {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid #e5e7eb;
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
            font-size: 2rem;
            line-height: 1.1;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .subtitle {
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 0.95rem;
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

        html.dark .main-card { background: #18181b; border-color: #27272a; }
        html.dark .title { color: #f8fafc; }
        html.dark .subtitle { color: #a1a1aa; }
        html.dark .kicker { background: #172554; border-color: #1d4ed8; color: #93c5fd; }
        html.dark .form-action-wrap { border-color: #27272a; }
    </style>

    <div class="form-wrapper">
        <div class="main-card">
            <span class="kicker">Data Master > Kalender Libur</span>

            <h1 class="title">
                {{ isset($this->record) ? 'Perbarui Hari Libur' : 'Tambah Hari Libur Baru' }}
            </h1>

            <p class="subtitle">
                Data kalender libur digunakan untuk menandai hari kerja/libur pada absensi dan membantu validasi proses penggajian.
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
            <h3 class="guide-title">Catatan Operasional</h3>

            <div class="guide-list">
                <div>1. Sinkronisasi otomatis tetap bisa dipakai dari halaman daftar libur.</div>
                <div>2. Input manual dipakai untuk hari libur khusus perusahaan.</div>
                <div>3. Pastikan keterangan jelas agar tim HR mudah audit.</div>
                <div>4. Perubahan kalender akan mempengaruhi penandaan hari libur pada absensi.</div>
            </div>

            <p class="guide-note">
                Contoh keterangan: Cuti Bersama, Libur Nasional, atau Libur Akhir Pekan Pengganti.
            </p>
        </aside>
    </div>
</x-filament-panels::page>
