<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Combined</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4F46E5;
            color: white;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-aktif { background-color: #dcfce7; color: #166534; }
        .status-diproses { background-color: #fef3c7; color: #92400e; }
        .status-selesai { background-color: #dbeafe; color: #1e40af; }
        .status-dipinjam { background-color: #e9d5ff; color: #6b21a8; }
        .status-dikembalikan { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SISTEM E-INVENTORY GEREJA HKBP SETIAK MEKAR</h1>
        <p>Periode: {{ $request->tanggal_mulai ?? 'Semua' }} s/d {{ $request->tanggal_selesai ?? 'Semua' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Jenis</th>
                <th>Nama Barang</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $item['jenis'])) }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['jumlah'] }} {{ $item['satuan'] ?? '' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($item['status']) }}">
                            {{ $item['status'] }}
                        </span>
                    </td>
                    <td>{{ $item['petugas'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
