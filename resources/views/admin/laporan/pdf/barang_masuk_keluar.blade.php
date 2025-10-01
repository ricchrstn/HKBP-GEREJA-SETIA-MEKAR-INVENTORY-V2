<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk & Keluar</title>
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
            width: 30%;
        }
        .summary-label {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
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
        <div class="title">LAPORAN BARANG MASUK & KELUAR</div>
        <div class="subtitle">Gereja HKBP Setiak Mekar</div>
        <div class="date-range">
            Periode: {{ $request->tanggal_mulai ?? 'Semua' }} s/d {{ $request->tanggal_selesai ?? 'Semua' }}
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Barang Masuk</div>
            <div class="summary-value">{{ $data->where('jenis_laporan', 'barang_masuk')->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Barang Keluar</div>
            <div class="summary-value">{{ $data->where('jenis_laporan', 'barang_keluar')->count() }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Transaksi</div>
            <div class="summary-value">{{ $data->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        @if($item->jenis_laporan == 'barang_masuk')
                            <span>Barang Masuk</span>
                        @else
                            <span>Barang Keluar</span>
                        @endif
                    </td>
                    <td>{{ $item->barang->nama }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
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
