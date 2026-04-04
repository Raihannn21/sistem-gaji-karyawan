<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@php
    $roleLabel = data_get(auth()->user(), 'role')
        ?? data_get(auth()->user(), 'jabatan')
        ?? data_get(auth()->user(), 'position')
        ?? 'Admin';
@endphp

<div id="custom-modern-header" style="
    position: absolute; inset: 0; z-index: 9999;
    width: 100%; height: 100%; min-height: 65px;
    display: flex; align-items: center; justify-content: space-between; 
    padding: 0 25px; font-family: 'Inter', sans-serif;
    background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);
    border-bottom: 1px solid #f1f5f9;
">
    {{-- Sisi Kiri: Brand --}}
    <div style="display: flex; align-items: center; gap: 15px;">
        <div
            style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 1.25rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4); transform: skewX(-5deg);">
            KC
        </div>
        <div style="display: flex; flex-direction: column;">
            <span
                style="font-weight: 950; font-size: 0.9rem; color: #1e293b; letter-spacing: -0.5px; text-transform: uppercase;">Konveksi Celana</span>
            <span
                style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase;">Sistem Manajemen Penggajian</span>
        </div>
    </div>

    {{-- Sisi Kanan: Profile & Logout --}}
    <div x-data="{ open: false }" style="position: relative; display: flex; align-items: center; gap: 20px;">
        <div style="text-align: right; display: flex; flex-direction: column;">
            <span class="custom-user-name" style="font-size: 0.8rem; font-weight: 900; color: #334155;">{{ auth()->user()->name }}</span>
            <span class="custom-user-status"
                style="font-size: 0.6rem; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px;">{{ $roleLabel }}
                • Online Status</span>
        </div>

        {{-- Trigger: Profile Circle --}}
        <div @click="open = !open" style="position: relative; cursor: pointer;">
            <div
                style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #e2e8f0; padding: 2px; overflow: hidden; background: white;">
                <div
                    style="width: 100%; height: 100%; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #3b82f6;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
            <div
                style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; border-radius: 50%; background: #10b981; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            </div>
        </div>

        {{-- Dropdown Menu (Fixing the Blink Bug with x-cloak) --}}
        <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            style="position: absolute; top: 55px; right: 0; width: 180px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); padding: 8px; z-index: 10000;">

            <form action="{{ filament()->getLogoutUrl() }}" method="POST">
                @csrf
                <button type="submit"
                    style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 8px; color: #ef4444; font-size: 0.8rem; font-weight: 800; text-align: left; transition: 0.2s; cursor: pointer; background: transparent; border: none;"
                    onmouseover="this.style.backgroundColor='#fef2f2'"
                    onmouseout="this.style.backgroundColor='transparent'">
                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout System
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    aside.fi-sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
        border-right: 1px solid #e2e8f0 !important;
        box-shadow: 8px 0 26px -24px rgba(15, 23, 42, 0.35) !important;
    }

    .fi-sidebar-header {
        border-bottom: 1px dashed #dbeafe !important;
        padding: 22px 18px !important;
        margin-bottom: 6px !important;
    }

    /* MODERNIZE SIDEBAR FILAMENT (Original Filament Sidebar) */
    nav.fi-sidebar-nav {
        padding: 16px 12px !important;
    }

    .fi-sidebar-group {
        margin-bottom: 10px !important;
    }

    .fi-sidebar-group-label {
        font-size: 0.65rem !important;
        font-weight: 850 !important;
        color: #94a3b8 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        margin-bottom: 6px !important;
        padding-left: 20px !important;
    }

    .fi-sidebar-item {
        margin-bottom: 4px !important;
        border: none !important;
    }

    .fi-sidebar-item-button {
        border-radius: 14px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 11px 14px !important;
        border: 1px solid transparent !important;
        background: #ffffff !important;
    }

    .fi-sidebar-item-button:hover {
        box-shadow: 0 12px 22px -18px rgba(37, 99, 235, 0.5) !important;
        background-color: #f8fbff !important;
        transform: translateX(6px);
        border-color: #bfdbfe !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(135deg, #1d4ed8, #2563eb) !important;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.35) !important;
        border: none !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button * {
        color: white !important;
        font-weight: 800 !important;
    }

    /* Ganti Logo/Icon di Sidebar agar selaras dengan brand baru kita */
    .fi-sidebar-header>a>div {
        display: none !important;
    }

    .fi-sidebar-header::before {
        content: 'Konveksi Celana';
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 900;
        color: #2563eb;
        background: #eff6ff;
        padding: 7px 12px;
        border-radius: 10px;
        border: 1px solid #dbeafe;
    }

    /* AGGRESSIVE CLEANING TOPBAR FILAMENT */
    header.fi-topbar {
        overflow: visible !important;
        position: relative !important;
    }

    .fi-topbar-header {
        visibility: hidden !important;
    }

    .fi-topbar-header>* {
        display: none !important;
    }

    .fi-topbar-header button[aria-label*="sidebar"] {
        visibility: visible !important;
        display: flex !important;
        position: absolute !important;
        left: 20px !important;
        z-index: 10000 !important;
        opacity: 0.7 !important;
    }

    /* Dark Mode Support */
    html.dark #custom-modern-header {
        background: rgba(15, 23, 42, 0.95) !important;
        border-color: #1e293b !important;
    }

    html.dark aside.fi-sidebar {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%) !important;
        border-color: #1f2937 !important;
        box-shadow: 8px 0 26px -24px rgba(0, 0, 0, 0.8) !important;
    }

    html.dark .fi-sidebar-header {
        border-color: rgba(59, 130, 246, 0.25) !important;
    }

    html.dark .fi-sidebar-item-button {
        background: #111827 !important;
        border-color: #1f2937 !important;
    }

    html.dark .fi-sidebar-item-button:hover {
        background: #172554 !important;
        border-color: #1d4ed8 !important;
    }

    html.dark .fi-sidebar-group-label {
        color: #93c5fd !important;
    }

    html.dark .custom-user-name {
        color: #f1f5f9 !important;
    }

    html.dark .custom-user-status {
        color: #34d399 !important;
    }

    html.dark #custom-modern-header div[style*="background: white"] {
        background: #0f172a !important;
        border-color: #1e293b !important;
    }
</style>