<x-filament-panels::page>
    <style>
        .form-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .form-wrapper { grid-template-columns: 1fr 340px; }
        }

        .main-form-card {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid #e5e7eb;
            padding: 2.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .side-info-card {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: white;
            border-radius: 1.5rem;
            padding: 2rem;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .profile-avatar-preview {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.1);
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .helper-text {
            font-size: 0.85rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-top: 1rem;
        }

        .action-container {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #f1f5f9;
        }

        html.dark .main-form-card { background: #18181b; border-color: #27272a; }
    </style>

    <div class="form-wrapper">
        {{-- Kolom Kiri: Formulir --}}
        <div class="main-form-card">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white">Informasi Personal</h2>
                <p class="text-slate-500 text-sm">Lengkapi data identitas karyawan sesuai dengan dokumen resmi.</p>
            </div>

            <form wire:submit.prevent="{{ isset($this->record) ? 'save' : 'create' }}">
                {{ $this->form }}

                <div class="action-container">
                    <div class="flex items-center gap-3">
                        @foreach ($this->getFormActions() as $action)
                            {{ $action }}
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        {{-- Kolom Kanan: Panduan --}}
        <div class="side-info-card">
            <div class="profile-avatar-preview">
                {{ isset($this->record) ? substr($this->record->nama, 0, 1) : '?' }}
            </div>
            
            <h3 class="text-lg font-bold mb-2">
                {{ isset($this->record) ? 'Edit Profil' : 'Karyawan Baru' }}
            </h3>
            
            <p class="helper-text">
                Pastikan <b>No. ID</b> dan <b>Emp No</b> sesuai dengan yang terdaftar di mesin Fingerprint agar data absensi dapat tersinkronisasi otomatis dengan sistem payroll.
            </p>

            <div class="mt-6 pt-6 border-t border-white/10">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-400 uppercase tracking-widest">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    Sistem Terkoneksi
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
