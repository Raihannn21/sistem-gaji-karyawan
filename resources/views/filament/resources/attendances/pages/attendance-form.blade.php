<x-filament-panels::page>
    <style>
        .attendance-form-wrapper {
            max-width: 1150px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
        }

        @media (min-width: 1100px) {
            .attendance-form-wrapper {
                grid-template-columns: 1.35fr 0.9fr;
            }
        }

        .attendance-main-card {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid #e5e7eb;
            padding: 2rem;
            box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.12);
        }

        .attendance-side-card {
            background: linear-gradient(145deg, #0f172a, #1e293b);
            color: white;
            border-radius: 1.25rem;
            padding: 1.75rem;
            position: sticky;
            top: 1.75rem;
            height: fit-content;
            box-shadow: 0 20px 30px -15px rgba(15, 23, 42, 0.6);
        }

        .page-kicker {
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

        .main-title {
            margin-top: 1rem;
            font-size: 2rem;
            line-height: 1.1;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .main-subtitle {
            margin-top: 0.5rem;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.55;
            max-width: 720px;
        }

        .form-action-wrap {
            margin-top: 1.8rem;
            padding-top: 1.4rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .guide-title {
            font-size: 1.05rem;
            font-weight: 900;
            color: #f8fafc;
            letter-spacing: -0.02em;
        }

        .guide-list {
            margin-top: 1rem;
            display: grid;
            gap: 0.85rem;
            font-size: 0.88rem;
            color: #cbd5e1;
            line-height: 1.5;
        }

        .guide-item {
            display: grid;
            grid-template-columns: 22px 1fr;
            align-items: start;
            gap: 0.55rem;
        }

        .guide-pill {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.22);
            color: #7dd3fc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
        }

        .guide-note {
            margin-top: 1.1rem;
            border-top: 1px solid rgba(148, 163, 184, 0.3);
            padding-top: 1rem;
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.55;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 1rem;
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid rgba(110, 231, 183, 0.35);
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #34d399;
            box-shadow: 0 0 0 6px rgba(52, 211, 153, 0.2);
        }

        html.dark .attendance-main-card {
            background: #18181b;
            border-color: #27272a;
        }

        html.dark .main-title {
            color: #f8fafc;
        }

        html.dark .main-subtitle {
            color: #a1a1aa;
        }

        html.dark .page-kicker {
            background: #172554;
            border-color: #1d4ed8;
            color: #93c5fd;
        }

        html.dark .form-action-wrap {
            border-color: #27272a;
        }
    </style>

    <div class="attendance-form-wrapper">
        <div class="attendance-main-card">
            <span class="page-kicker">Rekap Kehadiran</span>

            <h1 class="main-title">
                {{ isset($this->record) ? 'Perbarui Data Kehadiran' : 'Buat Kehadiran Baru' }}
            </h1>

            <p class="main-subtitle">
                Form ini terhubung dengan aturan otomatis sistem: status hari libur dihitung dari tanggal dan kalender libur, total jam kerja dihitung dari scan masuk-pulang, dan lembur mengikuti ketentuan jam penuh.
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

        <aside class="attendance-side-card">
            <h3 class="guide-title">Panduan Input Cepat</h3>

            <div class="guide-list">
                <div class="guide-item">
                    <span class="guide-pill">1</span>
                    <span>Pilih karyawan langsung dari dropdown nama agar tidak salah ID.</span>
                </div>

                <div class="guide-item">
                    <span class="guide-pill">2</span>
                    <span>Isi scan masuk dan scan pulang. Jika salah satu kosong di hari kerja biasa, sistem tetap memakai jam kerja normal.</span>
                </div>

                <div class="guide-item">
                    <span class="guide-pill">3</span>
                    <span>Lembur otomatis terisi, tapi tetap bisa dikoreksi jika ada instruksi atasan.</span>
                </div>

                <div class="guide-item">
                    <span class="guide-pill">4</span>
                    <span>Hari libur dihitung otomatis dari Minggu + kalender libur nasional yang sudah disinkronkan.</span>
                </div>
            </div>

            <span class="status-chip">
                <span class="status-dot"></span>
                Auto Calculation Active
            </span>

            <p class="guide-note">
                Jika data fingerprint tidak lengkap, admin tetap bisa simpan lalu koreksi di tahap verifikasi sebelum generate payroll.
            </p>
        </aside>
    </div>
</x-filament-panels::page>
