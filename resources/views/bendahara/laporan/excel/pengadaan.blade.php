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
                <td>{{ $pengadaan->estimasi_harga }}</td>
                <td>{{ $pengadaan->user->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($pengadaan->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
