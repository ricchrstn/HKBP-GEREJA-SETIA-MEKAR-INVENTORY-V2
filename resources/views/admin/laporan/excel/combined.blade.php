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
                <td>{{ $item['status'] }}</td>
                <td>{{ $item['petugas'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
