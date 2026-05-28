<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { padding: 4px 0; }
        .right { text-align: right; }
        .section-title { font-weight: bold; border-bottom: 1px solid #000; margin-bottom: 5px; margin-top: 10px; }
        .total-row td { font-weight: bold; border-top: 1px solid #000; padding-top: 5px; }
        .col-half { width: 48%; float: left; }
        .col-space { width: 4%; float: left; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN NERACA</div>
        <div>Per Tanggal: {{ date('d/m/Y', strtotime($sampai)) }}</div>
    </div>
    
    <div>
        <!-- AKTIVA -->
        <div class="col-half">
            <div class="section-title">AKTIVA (ASET)</div>
            <table>
                @foreach($asetItems as $item)
                @php $saldo = $item->total_debit - $item->total_kredit; @endphp
                @if($saldo != 0)
                <tr>
                    <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                    <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
            </table>
        </div>

        <div class="col-space">&nbsp;</div>

        <!-- PASIVA -->
        <div class="col-half">
            <div class="section-title">PASIVA (KEWAJIBAN & EKUITAS)</div>
            
            <div style="font-style: italic; margin-bottom: 5px;">Kewajiban</div>
            <table>
                @foreach($kewajibanItems as $item)
                @php $saldo = $item->total_kredit - $item->total_debit; @endphp
                @if($saldo != 0)
                <tr>
                    <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                    <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                <tr class="total-row">
                    <td>Total Kewajiban</td>
                    <td class="right">{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div style="font-style: italic; margin-bottom: 5px; margin-top:10px;">Ekuitas</div>
            <table>
                @foreach($ekuitasItems as $item)
                @php $saldo = $item->total_kredit - $item->total_debit; @endphp
                @if($saldo != 0)
                <tr>
                    <td>{{ $item->coa->nomor }} - {{ $item->coa->nama }}</td>
                    <td class="right">{{ number_format($saldo, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                <tr class="total-row">
                    <td>Total Ekuitas</td>
                    <td class="right">{{ number_format($totalEkuitas, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div style="border-top: 2px solid #000; margin-top: 20px; padding-top: 10px;">
        <div class="col-half">
            <span style="font-weight:bold; font-size:14px;">TOTAL AKTIVA</span>
            <span class="right" style="font-weight:bold; font-size:14px; float:right;">{{ number_format($totalAset, 0, ',', '.') }}</span>
        </div>
        <div class="col-space">&nbsp;</div>
        <div class="col-half">
            <span style="font-weight:bold; font-size:14px;">TOTAL PASIVA</span>
            <span class="right" style="font-weight:bold; font-size:14px; float:right;">{{ number_format($totalKewajiban + $totalEkuitas, 0, ',', '.') }}</span>
        </div>
        <div style="clear:both;"></div>
    </div>

</body>
</html>
