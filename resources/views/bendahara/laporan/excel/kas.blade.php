<table>
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Sumber/Tujuan</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kasData as $kas)
            <tr>
                <td>{{ $kas->kode_transaksi }}</td>
                <td>{{ $kas->tanggal->format('d/m/Y') }}</td>
                <td>{{ $kas->keterangan }}</td>
                <td>{{ $kas->jenis == 'masuk' ? $kas->sumber : $kas->tujuan }}</td>
                <td>{{ ucfirst($kas->jenis) }}</td>
                <td>{{ $kas->jumlah }}</td>
                <td>{{ $kas->user->name ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
