<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengadaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .date-range {
            font-size: 12px;
            margin-bottom: 20px;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 22%;
        }
        .summary-label {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }
        .total-value {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PENGAJUAN PENGAADAAN</div>
        <div class="subtitle">Gereja HKBP Setiak Mekar</div>
        <div class="date-range">
            Periode: {{ $request->tanggal_mulai ?? 'Semua' }} s/d {{ $request->tanggal_selesai ?? 'Semua' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Pengajuan</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Estimasi Harga</th>
                <th>Pengaju</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengadaanData as $pengadaan)
                <tr>
                    <td>{{ $pengadaan->kode_pengajuan }}</td>
                    <td>{{ $pengadaan->created_at->format('d/m/Y') }}</td>
                    <td>{{ $pengadaan->nama_barang }}</td>
                    <td>{{ $pengadaan->jumlah }} {{ $pengadaan->satuan }}</td>
                    <td>Rp {{ number_format($pengadaan->estimasi_harga, 0, ',', '.') }}</td>
                    <td>{{ $pengadaan->user->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($pengadaan->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
