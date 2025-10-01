<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @if(isset($item->jenis))
                        {{ ucfirst($item->jenis) }}
                    @else
                        Pengadaan
                    @endif
                </td>
                <td>
                    @if(isset($item->keterangan))
                        {{ $item->keterangan }}
                    @elseif(isset($item->nama_barang))
                        {{ $item->nama_barang }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($item->tanggal))
                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                    @elseif(isset($item->created_at))
                        {{ $item->created_at->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($item->jumlah))
                        {{ $item->jumlah }}
                    @elseif(isset($item->estimasi_harga))
                        {{ $item->estimasi_harga }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($item->jenis))
                        {{ ucfirst($item->jenis) }}
                    @elseif(isset($item->status))
                        {{ ucfirst($item->status) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
