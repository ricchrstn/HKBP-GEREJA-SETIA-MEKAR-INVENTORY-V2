<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Tanggal Audit</th>
            <th>Kondisi</th>
            <th>Status</th>
            <th>Jumlah</th>
            <th>Petugas</th>
            <th>Keterangan</th>
            <th>Tindak Lanjut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($audits as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->barang->kode_barang }}</td>
                <td>{{ $item->tanggal_audit->format('d/m/Y') }}</td>
                <td>{{ $item->kondisi }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->jumlah }} {{ $item->barang->satuan }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ $item->tindak_lanjut ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
