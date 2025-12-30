<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Sewa #{{ $sewa->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2563EB;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px 0;
        }
        .invoice-info .label {
            font-weight: bold;
            width: 150px;
        }
        .customer-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .customer-info h3 {
            margin-top: 0;
            color: #2563EB;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #2563EB;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section table {
            float: right;
            width: 300px;
        }
        .total-section td {
            padding: 8px;
        }
        .total-section .grand-total {
            font-size: 16px;
            font-weight: bold;
            background: #f3f4f6;
            border-top: 2px solid #2563EB;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-dibayar { background: #dbeafe; color: #1e40af; }
        .status-dipinjam { background: #d1fae5; color: #065f46; }
        .status-dikembalikan { background: #e5e7eb; color: #374151; }
        .status-batal { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE SEWA</h1>
        <p>PT Camping Rental Indonesia</p>
        <p>Jl. Camping No. 123, Jakarta | Telp: (021) 1234-5678</p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td class="label">No. Invoice:</td>
                <td><strong>#INV-{{ str_pad($sewa->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                <td class="label">Tanggal:</td>
                <td>{{ \Carbon\Carbon::parse($sewa->created_at)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td colspan="3">
                    <span class="status-badge status-{{ $sewa->status }}">
                        {{ strtoupper($sewa->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="customer-info">
        <h3>Informasi Pelanggan</h3>
        <table>
            <tr>
                <td class="label">Nama:</td>
                <td>{{ $sewa->pelanggan->user->nama }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $sewa->pelanggan->user->email }}</td>
            </tr>
            <tr>
                <td class="label">Telepon:</td>
                <td>{{ $sewa->pelanggan->telp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat:</td>
                <td>{{ $sewa->pelanggan->alamat ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="rental-period">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td class="label">Tanggal Sewa:</td>
                <td><strong>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->format('d F Y') }}</strong></td>
                <td class="label">Tanggal Kembali:</td>
                <td><strong>{{ \Carbon\Carbon::parse($sewa->tanggal_kembali)->format('d F Y') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Lama Sewa:</td>
                <td colspan="3">
                    <strong>{{ \Carbon\Carbon::parse($sewa->tanggal_sewa)->diffInDays(\Carbon\Carbon::parse($sewa->tanggal_kembali)) }} Hari</strong>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga/Hari</th>
                <th>Qty</th>
                <th>Lama</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $lamaSewa = \Carbon\Carbon::parse($sewa->tanggal_sewa)->diffInDays(\Carbon\Carbon::parse($sewa->tanggal_kembali));
                $lamaSewa = max(1, $lamaSewa);
            @endphp
            @foreach($sewa->detailSewa as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $lamaSewa }} hari</td>
                <td>Rp {{ number_format($detail->harga * $detail->qty * $lamaSewa, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td><strong>TOTAL:</strong></td>
                <td style="text-align: right;"><strong>Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($sewa->catatan)
    <div style="clear: both; margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b;">
        <strong>Catatan:</strong><br>
        {{ $sewa->catatan }}
    </div>
    @endif

    <div class="footer">
        <p>Terima kasih telah menggunakan layanan kami!</p>
        <p>Invoice ini digenerate otomatis pada {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
