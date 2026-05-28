<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laba Rugi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { padding: 4px 0; }
        .right { text-align: right; }
        .section-title { font-weight: bold; text-decoration: underline; margin-bottom: 5px; margin-top: 15px; }
        .total-row td { font-weight: bold; border-top: 1px solid #000; border-bottom: 1px double #000; padding-top: 8px; }
        .grand-total { font-weight: bold; font-size: 14px; margin-top: 20px; border: 2px solid #000; padding: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN LABA RUGI</div>
        <div>Periode: {{ date('d/m/Y', strtotime($dari)) }} - {{ date('d/m/Y', strtotime($sampai)) }}</div>
    </div>
    
    <div class="section-title">PENDAPATAN</div>
    <table>
        @foreach($pendapatanItems as $item)
        @php $saldo = $item->total_kredit - $item->total_debit; @endphp
        @if($saldo != 0)
        <tr>
            <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
            <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach
        <tr class="total-row">
            <td>Total Pendapatan</td>
            <td class="right">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">HARGA POKOK PENJUALAN</div>
    <table>
        @foreach($hppItems as $item)
        @php $saldo = $item->total_debit - $item->total_kredit; @endphp
        @if($saldo != 0)
        <tr>
            <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
            <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach
        <tr class="total-row">
            <td>Total HPP</td>
            <td class="right">{{ number_format($totalHPP, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="grand-total">
        <span style="float:left;">Laba (Rugi) Kotor</span>
        <span style="float:right;">{{ number_format($labaKotor, 0, ',', '.') }}</span>
        <div style="clear:both;"></div>
    </div>

    <div class="section-title">BEBAN OPERASIONAL</div>
    <table>
        @foreach($bebanItems as $item)
        @php $saldo = $item->total_debit - $item->total_kredit; @endphp
        @if($saldo != 0)
        <tr>
            <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
            <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
        @endif
        @endforeach
        <tr class="total-row">
            <td>Total Beban Operasional</td>
            <td class="right">{{ number_format($totalBeban, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="grand-total">
        <span style="float:left;">Laba (Rugi) Bersih</span>
        <span style="float:right;">{{ number_format($labaBersih, 0, ',', '.') }}</span>
        <div style="clear:both;"></div>
    </div>
</body>
</html>
