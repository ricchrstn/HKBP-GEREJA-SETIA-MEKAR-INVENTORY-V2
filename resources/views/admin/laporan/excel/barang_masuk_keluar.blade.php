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
