<div class="inv-body">

    <div class="body-address">
        <h1>From</h1>
        <div class="body-company">
            @if($invoice->usaha)
            <p>{{ $invoice->usaha->nama }}</p>
            <p>{{ $invoice->usaha->alamat }}</p>
            @if($invoice->usaha->telepon)
            <p>Tel: {{ $invoice->usaha->telepon }}</p>
            @endif
            @endif
        </div>

        <h1 class="client">To</h1>
        <div class="body-client">
            @if($invoice->transaksi)
            @if($invoice->transaksi->pelanggan)
            <p>{{ $invoice->transaksi->pelanggan->nama }}</p>
            @elseif($invoice->transaksi->supplier)
            <p>{{ $invoice->transaksi->supplier->nama }}</p>
            @endif
            @else
            <p>{{ $invoice->to_client_name }}</p>
            @endif

            <div class="due-box">
                <p class="number due">
                    Tanggal : {{ date('d F Y', strtotime($invoice->created_at)) }}
                </p>
            </div>
        </div>
    </div>

    <table class="body-content">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->invoiceItems as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
                <td>Rp {{ number_format($item->total,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>