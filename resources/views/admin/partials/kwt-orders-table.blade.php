<div class="table-responsive custom-scrollbar">
    <table class="table table-hover align-middle text-nowrap mb-0">
        <thead class="bg-light">
            <tr>
                <th class="small py-3 ps-3">INVOICE ID</th>
                <th class="small py-3">PRODUK TERJUAL</th>
                <th class="small text-end py-3 pe-3">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="fw-bold text-primary py-3 ps-3">
                    #INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                </td>

                {{-- Tambahan white-space normal & min-width agar badge produk tetap rapi dan bisa ke bawah (wrap) --}}
                <td class="py-3" style="white-space: normal; min-width: 250px;">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($order->details as $d)
                        <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle">
                            {{ $d->product->nama_produk ?? 'Produk' }} (x{{ $d->jumlah }})
                        </span>
                        @endforeach
                    </div>
                </td>

                <td class="text-end fw-bold py-3 pe-3 text-success">
                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox opacity-50 d-block fs-3 mb-2"></i>
                    Belum ada transaksi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    /* CSS untuk scrollbar horizontal yang tipis dan rapi di layar HP */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
</style>