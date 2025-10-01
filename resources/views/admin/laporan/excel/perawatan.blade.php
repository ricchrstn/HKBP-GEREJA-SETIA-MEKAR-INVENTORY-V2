<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Tanggal Perawatan</th>
            <th>Jenis Perawatan</th>
            <th>Biaya</th>
            <th>Status</th>
            <th>Petugas</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($perawatan as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->barang->kode_barang }}</td>
                <td>{{ $item->tanggal_perawatan->format('d/m/Y') }}</td>
                <td>{{ $item->jenis_perawatan }}</td>
                <td>{{ 'Rp ' . number_format($item->biaya, 0, ',', '.') }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
