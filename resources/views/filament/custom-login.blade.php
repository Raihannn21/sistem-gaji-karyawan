<div id="custom-modern-login-overlay" style="
    position: fixed; inset: 0; z-index: 2147483647;
    display: flex; align-items: center; justify-content: center;
    background: #f8fafc; font-family: 'Inter', sans-serif;
">
    {{-- Toast Notification (FORCED FRONT) --}}
    <div id="custom-toast" style="
        position: fixed; top: 15px; left: 50%; transform: translateX(-50%) translateY(-20px);
        background: #ef4444; color: white; padding: 12px 25px; border-radius: 99px;
        font-weight: 800; font-size: 0.9rem; box-shadow: 0 15px 30px rgba(239, 68, 68, 0.5);
        display: flex; align-items: center; gap: 10px; z-index: 2147483649;
        opacity: 0; pointer-events: none; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    ">
        <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span id="toast-message">Mohon isi semua bidang.</span>
    </div>

    {{-- Background Decor --}}
    <div style="position: absolute; top: -10%; left: -5%; width: 500px; height: 500px; background: rgba(37, 99, 235, 0.05); border-radius: 50%; filter: blur(100px);"></div>

    <div style="
        width: 1000px; max-width: 95%; height: 600px;
        background: white; border-radius: 24px; display: flex; 
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); overflow: hidden;
        position: relative; z-index: 2147483647;
    ">
        {{-- Sisi Kiri: Branding --}}
        <div style="flex: 1; background: linear-gradient(135deg, #1d4ed8, #2563eb); padding: 50px; color: white; display: flex; flex-direction: column; justify-content: center;">
            <div style="background: rgba(255,255,255,0.1); width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.5rem; margin-bottom: 25px;">KC</div>
            <h1 style="font-size: 2.5rem; font-weight: 900; line-height: 1.1; margin-bottom: 20px;">Konveksi Celana Payroll.</h1>
            <p style="font-size: 1.1rem; opacity: 0.8; line-height: 1.6;">Sistem manajemen penggajian karyawan pintar dengan kalkulasi transparan dan akurat.</p>
        </div>

        {{-- Sisi Kanan: Form Bridge --}}
        <div style="flex: 1.2; padding: 50px; display: flex; flex-direction: column; justify-content: center;">
            <div style="margin-bottom: 40px;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Selamat Datang Kembali</h2>
                <p style="color: #64748b; font-size: 0.95rem;">Gunakan akun admin untuk mengelola sistem konveksi.</p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #475569;">ALAMAT EMAIL</label>
                    <input type="email" placeholder="admin@konveksi.com" 
                        oninput="let el = document.querySelector('form input[type=email]'); if(el) { el.value = this.value; el.dispatchEvent(new Event('input')); }"
                        style="width: 100%; padding: 15px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; font-weight: 600; outline: none; transition: 0.3s;"
                        onfocus="this.style.borderColor='#2563eb';">
                </div>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 0.85rem; font-weight: 700; color: #475569;">KATA SANDI</label>
                    <input type="password" placeholder="••••••••" 
                        oninput="let el = document.querySelector('form input[type=password]'); if(el) { el.value = this.value; el.dispatchEvent(new Event('input')); }"
                        style="width: 100%; padding: 15px; border-radius: 12px; border: 2px solid #f1f5f9; background: #f8fafc; font-weight: 600; outline: none; transition: 0.3s;"
                        onfocus="this.style.borderColor='#2563eb';">
                </div>

                <button type="button" 
                    onclick="
                        let email = document.querySelector('form input[type=email]');
                        let pass = document.querySelector('form input[type=password]');
                        let btn = document.querySelector('form button[type=submit]');
                        let toast = document.getElementById('custom-toast');
                        
                        function showToast(msg) {
                            document.getElementById('toast-message').innerText = msg;
                            toast.style.opacity = '1';
                            toast.style.transform = 'translateX(-50%) translateY(0)';
                            setTimeout(() => {
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateX(-50%) translateY(-20px)';
                            }, 3000);
                        }

                        if(!email.value || !pass.value) {
                            showToast('Mohon masukkan email dan password anda.');
                            return;
                        }
                        
                        document.querySelector('form').setAttribute('novalidate', true);
                        if(btn) btn.click();
                    "
                    style="margin-top: 10px; width: 100%; padding: 16px; background: #2563eb; color: white; border: none; border-radius: 12px; font-weight: 800; font-size: 1rem; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);"
                    onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'">
                    MASUK SEKARANG
                </button>
            </div>

            <div style="margin-top: 40px; text-align: center; font-size: 0.85rem; color: #94a3b8;">
                &copy; {{ date('Y') }} Sistem Gaji Konveksi Celana Enterprise.
            </div>
        </div>
    </div>
</div>

<style>
    /* AGGRESSIVE CLEANING */
    .fi-layout, .fi-main, .fi-auth-card, .fi-logo, .fi-header, .fi-auth-header { 
        visibility: hidden !important; opacity: 0 !important; pointer-events: none !important; 
    }
    main.fi-auth { background: #f8fafc !important; }
    [x-cloak] { display: none !important; }
    body { background: #f8fafc !important; }
</style>