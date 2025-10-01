<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Peminjam</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Petugas</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($peminjaman as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->barang->kode_barang }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
