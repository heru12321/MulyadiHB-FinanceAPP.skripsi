<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Besar - {{ $selectedCoa->nomor }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BUKU BESAR</div>
        <div>Akun: {{ $selectedCoa->nomor }} - {{ $selectedCoa->nama }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Jurnal</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo Berjalan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="right"><strong>Saldo Awal</strong></td>
                <td class="right"><strong>{{ number_format($saldoAwal, 0, ',', '.') }}</strong></td>
            </tr>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->tanggal->format('d/m/Y') }}</td>
                <td>{{ $log->jurnal->kode }}</td>
                <td>{{ $log->keterangan }}</td>
                <td class="right">{{ $log->debit ? number_format($log->debit, 0, ',', '.') : '-' }}</td>
                <td class="right">{{ $log->kredit ? number_format($log->kredit, 0, ',', '.') : '-' }}</td>
                <td class="right">{{ number_format($log->saldo_berjalan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
