<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Report Payroll</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .subtitle { font-size: 11px; color: #4b5563; margin-bottom: 14px; }
        .meta { margin-bottom: 14px; }
        .meta td { padding: 2px 8px 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px; }
        th { background: #f3f4f6; font-size: 10px; text-transform: uppercase; }
        .right { text-align: right; }
        .summary { margin-top: 14px; width: 50%; margin-left: auto; }
        .summary td { border: 1px solid #d1d5db; padding: 6px; }
        .summary .label { background: #f9fafb; font-weight: 700; }
        .summary .value { text-align: right; font-weight: 700; }
    </style>
</head>

<body>
    <div class="title">Report Payroll</div>
    <div class="subtitle">Periode {{ $payroll->periode }} @if($statusFilter) | Status: {{ $statusFilter }} @endif</div>

    <table class="meta">
        <tr>
            <td><strong>Tanggal Mulai</strong></td>
            <td>{{ \Carbon\Carbon::parse($payroll->tanggal_mulai)->translatedFormat('d M Y') }}</td>
            <td><strong>Tanggal Selesai</strong></td>
            <td>{{ \Carbon\Carbon::parse($payroll->tanggal_selesai)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status</th>
                <th>Hadir</th>
                <th>Jam LB</th>
                <th>Jam LL</th>
                <th>Gaji Pokok</th>
                <th>Uang Lembur</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
                <tr>
                    <td>{{ $detail->employee->nama }}</td>
                    <td>{{ $detail->employee->employment_status }}</td>
                    <td class="right">{{ $detail->total_hadir }}</td>
                    <td class="right">{{ $detail->jam_lembur_biasa }}</td>
                    <td class="right">{{ $detail->jam_lembur_libur }}</td>
                    <td class="right">Rp {{ number_format($detail->total_gaji_kehadiran, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($detail->total_gaji_lembur_biasa + $detail->total_gaji_lembur_libur, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $totalPokok = $details->sum('total_gaji_kehadiran');
        $totalLembur = $details->sum('total_gaji_lembur_biasa') + $details->sum('total_gaji_lembur_libur');
        $grandTotal = $details->sum('total_gaji');
    @endphp

    <table class="summary">
        <tr>
            <td class="label">Total Gaji Pokok</td>
            <td class="value">Rp {{ number_format($totalPokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Uang Lembur</td>
            <td class="value">Rp {{ number_format($totalLembur, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Grand Total</td>
            <td class="value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
