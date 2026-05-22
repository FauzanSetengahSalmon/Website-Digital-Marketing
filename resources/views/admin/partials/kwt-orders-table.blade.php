<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="bg-light">
            <tr>
                <th class="small">INVOICE ID</th>
                <th class="small">PRODUK TERJUAL</th>
                <th class="small text-end">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="fw-bold text-primary">#INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>
                    @foreach($order->details as $d)
                    <span class="badge bg-secondary-subtle text-dark mb-1">{{ $d->product->nama_produk ?? 'Produk' }} (x{{ $d->jumlah }})</span>
                    @endforeach
                </td>
                <td class="text-end fw-bold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-3 text-muted">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>