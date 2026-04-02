<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <p>Halo, <strong>{{ $detail->employee->nama }}</strong>.</p>
    <p>Berikut adalah slip gaji elektronik Anda untuk periode <strong>{{ \Carbon\Carbon::parse($detail->payroll->tanggal_mulai)->translatedFormat('d F Y') }}</strong> hingga <strong>{{ \Carbon\Carbon::parse($detail->payroll->tanggal_selesai)->translatedFormat('d F Y') }}</strong>.</p>
    <p>Silakan unduh dokumen PDF yang terlampir pada email ini untuk melihat rincian gaji Anda secara lengkap.</p>
    <p>Harap simpan dokumen tersebut sebagai bukti penghasilan yang sah. Jika ada perbedaan dalam perhitungan, silakan langsung menghubungi Bagian HR atau Admin.</p>
    <br>
    <p>Terima kasih,<br><strong>{{ config('app.name') }}</strong></p>
</body>
</html>
