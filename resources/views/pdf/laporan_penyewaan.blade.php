<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyewaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 10px;
        }
        .header h2 {
            color: #2563EB;
            margin: 0;
        }
        .period {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background: #2563EB;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .total-row {
            background: #f3f4f6;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENYEWAAN</h2>
        <p>PT Camping Rental Indonesia</p>
    </div>

    <div class="period">
        Periode: {{ $startDate }} s/d {{ $endDate }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Barang</th>
                <th>Lama</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sewas as $index => $sewa)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->format('d/m/Y') }}</td>
                <td>{{ $sewa->pelanggan->user->nama ?? '-' }}</td>
                <td>
                    @foreach($sewa->detailSewa as $detail)
                        {{ $detail->barang->nama_barang }} ({{ $detail->qty }})<br>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->diffInDays(\Carbon\Carbon::parse($sewa->tanggal_kembali)) }} hari</td>
                <td>Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</td>
                <td>{{ strtoupper($sewa->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL PENDAPATAN:</td>
                <td colspan="2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Digenerate pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
