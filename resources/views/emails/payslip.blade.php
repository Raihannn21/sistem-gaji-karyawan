<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $detail->employee->nama }}</title>
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 0; }
        .wrapper { padding: 40px; }
        .header-table { width: 100%; border-bottom: 3px solid #1d4ed8; padding-bottom: 10px; margin-bottom: 25px; }
        .kop-title { font-size: 26px; font-weight: bold; color: #1d4ed8; margin: 0; text-transform: uppercase; letter-spacing: 1px;}
        .kop-subtitle { font-size: 14px; color: #666; margin: 5px 0 0 0; }
        
        .title-slip { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 25px; text-transform: uppercase; }
        
        .info-table { width: 100%; margin-bottom: 30px; font-size: 13px;}
        .info-table td { padding: 5px 0; vertical-align: top; }
        .info-label { font-weight: bold; width: 130px; }
        .info-colon { width: 15px; text-align: center; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .data-table th, .data-table td { border: 1px solid #cbd5e1; padding: 10px; }
        .data-table th { background-color: #f1f5f9; font-weight: bold; text-align: left; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;}
        .data-table .text-right { text-align: right; }
        .data-table .text-center { text-align: center; }
        .data-table .total-row { font-weight: bold; background-color: #eff6ff; }
        .data-table .total-row td { border-top: 2px solid #1d4ed8; color: #1e3a8a; font-size: 15px;}
        
        .footer-table { width: 100%; margin-top: 50px; text-align: center; }
        .footer-table td { width: 50%; vertical-align: bottom; height: 120px; }
        .sign-holder { margin-bottom: 10px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="kop-title">{{ strtoupper(config('app.name', 'CIMOL BOJOT AA')) }}</h1>
                    <p class="kop-subtitle">Dokumen Resmi Rincian Pembayaran Slip Gaji</p>
                </td>
                <td style="text-align: right; vertical-align: bottom;">
                    <p style="margin: 0; font-size: 11px; color: #888;">Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
                </td>
            </tr>
        </table>

        <div class="title-slip">SLIP GAJI KARYAWAN</div>

        <table class="info-table">
            <tr>
                <td class="info-label">Nama Karyawan</td>
                <td class="info-colon">:</td>
                <td style="width: 35%"><strong>{{ strtoupper($detail->employee->nama) }}</strong></td>
                
                <td class="info-label">Periode Gaji</td>
                <td class="info-colon">:</td>
                <td>{{ \Carbon\Carbon::parse($detail->payroll->tanggal_mulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($detail->payroll->tanggal_selesai)->translatedFormat('d M Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">ID Karyawan</td>
                <td class="info-colon">:</td>
                <td>{{ $detail->employee->emp_no ?? '-' }}</td>
                
                <td class="info-label">Departemen</td>
                <td class="info-colon">:</td>
                <td>{{ $detail->employee->departemen ? strtoupper($detail->employee->departemen) : 'OPERASIONAL' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="45%">Deskripsi Pendapatan</th>
                    <th width="20%" class="text-center">Kuantitas</th>
                    <th width="30%" class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Gaji Pokok Kehadiran</td>
                    <td class="text-center">{{ $detail->total_hadir }} Hari</td>
                    <td class="text-right">{{ number_format($detail->total_gaji_kehadiran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Lembur Hari Biasa</td>
                    <td class="text-center">{{ $detail->jam_lembur_biasa }} Jam</td>
                    <td class="text-right">{{ number_format($detail->total_gaji_lembur_biasa, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Lembur Hari Libur</td>
                    <td class="text-center">{{ $detail->jam_lembur_libur }} Jam</td>
                    <td class="text-right">{{ number_format($detail->total_gaji_lembur_libur, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="text-right">TOTAL PENDAPATAN BERSIH (TAKE HOME PAY)</td>
                    <td class="text-right">Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 11px; font-style: italic; color: #64748b; margin-top: 5px;">
            * Slip gaji ini di-generate otomatis secara elektronik oleh sistem. Harap periksa kembali dengan seksama. 
            Jika terdapat perbedaan pada rincian perhitungan, silakan melapor ke pihak HRD maksimal 3 hari kerja setelah dokumen diterima. Dokumen ini sah meskipun tanpa tanda tangan fisik perusahaan.
        </p>

        <table class="footer-table">
            <tr>
                <td>
                    <div class="sign-holder">Diterima Oleh,</div>
                    <div style="height: 60px;"></div>
                    <div class="sign-name">{{ strtoupper($detail->employee->nama) }}</div>
                    <div style="font-size: 12px; margin-top: 4px; color: #666;">Karyawan</div>
                </td>
                <td>
                    <div class="sign-holder">Mengetahui,</div>
                    <div style="height: 60px;"></div>
                    <div class="sign-name">__________________________</div>
                    <div style="font-size: 12px; margin-top: 4px; color: #666;">Pihak Perusahaan / Keuangan</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>