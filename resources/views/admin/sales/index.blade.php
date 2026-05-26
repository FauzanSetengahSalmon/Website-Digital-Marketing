@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 bg-light min-vh-100">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Penjualan Global</h4>
            <p class="text-muted small mb-0">Pantau transaksi masuk dari customer dan distribusikan penugasan armada kurir.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" id="btn-batch-kwt" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" disabled>
                <i class="bi bi-shop me-1"></i> Cetak Invoice KWT Terpilih
            </button>
            <button type="button" id="btn-batch-kurir" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" disabled>
                <i class="bi bi-truck me-1"></i> Cetak Surat Jalan Terpilih
            </button>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold fs-7 d-flex align-items-center">
                <i class="bi bi-cart-check-fill me-1"></i> {{ $sales->count() }} Total Pesanan
            </span>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light border-bottom text-uppercase tracking-wider fs-7 fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 40px;"><input type="checkbox" id="check-all" class="form-check-input"></th>
                        <th class="py-3">Order ID</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3 text-end">Total Harga</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3">Jadwal Kirim</th>
                        <th class="py-3">Tanggal Transaksi</th>
                        <th class="py-3 text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="align-middle border-bottom border-light">
                        <td class="ps-4 py-3.5">
                            <input type="checkbox" class="form-check-input order-checkbox" value="{{ $sale->id }}">
                        </td>
                        <td class="py-3.5">
                            <span class="fw-bold text-success font-monospace">#{{ $sale->id }}</span>
                        </td>
                        <td class="py-3.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    {{ substr($sale->user->name ?? 'M', 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-semibold text-dark d-block lh-sm">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                                    <small class="text-muted fs-8 font-monospace">{{ $sale->nomor_hp ?? $sale->user->phone_number ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 text-end">
                            <span class="fw-bold text-dark">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3.5 text-center">
                            @if($sale->status == 'menunggu')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">⚡ Menunggu</span>
                            @elseif($sale->status == 'diproses')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">📦 Diproses</span>
                            @elseif($sale->status == 'diantar')
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">🚚 Diantar</span>
                            @elseif($sale->status == 'batal')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">❌ Batal</span>
                            @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold text-uppercase fs-8">✅ {{ $sale->status }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 fs-7 fw-medium text-dark">
                            @if($sale->jadwal_pengiriman)
                            <span class="text-success bg-success bg-opacity-10 px-2.5 py-1 rounded-pill fw-bold"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d M Y') }}</span>
                            @else
                            <span class="text-muted small italic"><i class="bi bi-hourglass-split me-1"></i>Belum Dijadwalkan</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-secondary fs-7">
                            <i class="bi bi-calendar3 me-1 text-muted"></i> {{ $sale->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="py-3.5 text-center pe-4">
                            <div class="d-flex gap-2 justify-content-center">
                                {{-- Tombol Kelola (Bawaan) --}}
                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-dark transition-all shadow-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalProsesAdmin{{ $sale->id }}">
                                    <i class="bi bi-sliders2 me-1 text-success"></i> Kelola
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50"></i>
                                <span class="d-block fw-medium">Belum ada pesanan masuk.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($sales as $sale)
<div class="modal fade" id="modalProsesAdmin{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.order.status', $sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" class="input-status-handler" value="diproses">

                <div class="modal-header border-0 py-3 px-4 bg-light align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Manajemen & Verifikasi Pesanan</h5>
                        <small class="text-muted fs-7">Order ID: <span class="text-success fw-bold font-monospace">#{{ $sale->id }}</span></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">

                    <div class="p-3 border rounded-4 bg-light bg-opacity-50 mb-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Customer</small>
                                <span class="fw-bold text-dark">{{ $sale->user->name ?? 'Masyarakat' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Kontak</small>
                                <span class="fw-semibold text-dark font-monospace">{{ $sale->nomor_hp ?? '-' }}</span>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5">Total Pembayaran</small>
                                <span class="fw-bold text-success fs-5">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-12 border-top pt-2 mt-2">
                                <small class="text-muted d-block text-uppercase fs-8 fw-semibold mb-0.5"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Alamat Pengiriman</small>
                                <span class="small text-dark fw-medium lh-base">{{ $sale->alamat ?? 'Alamat tidak terisi lengkap' }}</span>
                            </div>

                            @if($sale->jadwal_pengiriman)
                            <div class="col-12 border-top pt-2 mt-2">
                                <small class="text-success d-block text-uppercase fs-8 fw-bold mb-0.5"><i class="bi bi-calendar-check-fill me-1"></i>Jadwal Pengiriman Terpilih</small>
                                <span class="small text-success fw-bold bg-success bg-opacity-10 px-2 py-1.5 rounded d-inline-block">
                                    <i class="bi bi-clock-history me-1"></i>Armada meluncur pada: {{ \Carbon\Carbon::parse($sale->jadwal_pengiriman)->format('d F Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2 fs-7 text-uppercase tracking-wider text-secondary"><i class="bi bi-basket3-fill text-success me-2"></i>Komoditas Hasil Panen KWT</h6>
                        <div class="table-responsive rounded-3 border bg-white">
                            <table class="table align-middle mb-0 sm-table">
                                <thead class="table-light text-secondary fs-8 fw-bold text-uppercase">
                                    <tr>
                                        <th class="ps-3 py-2">Nama Produk</th>
                                        <th class="py-2">Pemilik / Nama KWT</th>
                                        <th class="py-2 text-center">Kuantitas</th>
                                        <th class="py-2 text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($sale->details)
                                    @foreach($sale->details as $detail)
                                    <tr>
                                        <td class="ps-3 py-2.5 fw-semibold text-dark">
                                            {{ $detail->product->nama_produk ?? 'Produk Terhapus' }}
                                        </td>
                                        <td class="py-2.5">
                                            <span class="badge bg-success-subtle text-success rounded-1 fw-semibold fs-8 px-2 py-1">
                                                <i class="bi bi-person-badge me-1"></i>{{ $detail->product->user->name ?? 'KWT Umum' }}
                                            </span>
                                        </td>
                                        <td class="text-center py-2.5 text-muted small fw-medium">
                                            {{ $detail->jumlah }} {{ $detail->product->satuan ?? 'Ikat' }}
                                        </td>
                                        <td class="text-end py-2.5 pe-3 fw-bold text-dark">
                                            Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 🌟 FIX KONDISI: Selama kolom tanggal di database kosong, FORM WAJIB TERBUKA (Abaikan Status) 🌟 --}}
                    @if(empty($sale->jadwal_pengiriman))
                    <div class="border-top pt-3 section-kurir-wrapper">
                        <h6 class="fw-bold text-dark mb-3 fs-7 text-uppercase tracking-wider text-secondary"><i class="bi bi-truck text-success me-2"></i>Penugasan Armada & Logistik</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">Pilih Personel Armada</label>
                                <select name="kurir" class="form-select select-kurir-admin rounded-3 py-2 fs-7 border-secondary-subtle" required>
                                    <option value="">-- Hubungkan Kurir --</option>
                                    @foreach($list_kurir as $k)
                                    <option value="{{ $k->nama }}" data-phone="{{ $k->no_hp }}" {{ $sale->kurir == $k->nama ? 'selected' : '' }}>
                                        {{ $k->nama }} {{ $k->kendaraan ? '['.$k->kendaraan.']' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">Nomor HP Aktif Kurir</label>
                                <input type="text" name="no_hp_kurir" class="form-control input-phone-admin rounded-3 py-2 fs-7 bg-light border-secondary-subtle font-monospace" value="{{ $sale->no_hp_kurir ?? '' }}" placeholder="Terisi otomatis..." readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">Jadwal Pengiriman</label>
                                <input type="date" name="jadwal_pengiriman" class="form-control rounded-3 py-2 fs-7 border-secondary-subtle" min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="border-top pt-3 p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-2 fs-7 text-uppercase"><i class="bi bi-shield-check text-success me-2"></i>Status Logistik Terkunci</h6>
                        <div class="row small g-2">
                            <div class="col-md-6"><strong>Kurir Pengantar:</strong> {{ $sale->kurir ?? '-' }}</div>
                            <div class="col-md-6"><strong>Kontak Kurir:</strong> {{ $sale->no_hp_kurir ?? '-' }}</div>
                        </div>
                    </div>
                    @endif

                </div>

                <div class="modal-footer border-0 px-4 pb-4 bg-light bg-opacity-25 justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Kembali</button>

                    @if($sale->status === 'diproses')
                    <button type="submit" onclick="this.form.querySelector('.input-status-handler').value='diantar';" class="btn btn-sm btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-truck me-1"></i> Tandai Pesanan Diantar
                    </button>
                    @endif

                    {{-- 🌟 FIX TOMBOL: Tombol Verifikasi Tetap Muncul Selama Tanggal Kosong 🌟 --}}
                    @if(empty($sale->jadwal_pengiriman))
                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-send-check-fill me-1"></i> Verifikasi & Lepas Kurir
                    </button>
                    @else
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" disabled><i class="bi bi-lock-fill me-1"></i>Sudah Dijadwalkan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    .fs-7 {
        font-size: 0.78rem !important;
    }

    .fs-8 {
        font-size: 0.72rem !important;
    }

    .tracking-wider {
        letter-spacing: 0.06em;
    }

    .sm-table th,
    .sm-table td {
        padding: 0.6rem 0.5rem !important;
        font-size: 0.82rem;
    }

    .bg-warning-subtle {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .border-warning-subtle {
        border-color: #ffeeba !important;
    }

    .bg-primary-subtle {
        background-color: #cce5ff !important;
        color: #004085 !important;
    }

    .border-primary-subtle {
        border-color: #b8daff !important;
    }

    .bg-success-subtle {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .border-success-subtle {
        border-color: #c3e6cb !important;
    }

    .bg-info-subtle {
        background-color: #e0f7fa !important;
        color: #00838f !important;
    }

    .border-info-subtle {
        border-color: #b2ebf2 !important;
    }

    .bg-danger-subtle {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    .border-danger-subtle {
        border-color: #f5c6cb !important;
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(25, 135, 84, 0.02) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-kurir-admin').forEach(selectElement => {
            selectElement.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const phone = selectedOption.getAttribute('data-phone');

                const wrapper = this.closest('.section-kurir-wrapper');
                const phoneInput = wrapper.querySelector('.input-phone-admin');

                if (phoneInput) {
                    phoneInput.value = phone ? phone : '';
                }
            });
        });

        // Batch Action handler
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const btnBatchKwt = document.getElementById('btn-batch-kwt');
        const btnBatchKurir = document.getElementById('btn-batch-kurir');

        function updateBatchButtons() {
            const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
            if (checkedCount > 0) {
                btnBatchKwt.removeAttribute('disabled');
                btnBatchKurir.removeAttribute('disabled');
            } else {
                btnBatchKwt.setAttribute('disabled', 'true');
                btnBatchKurir.setAttribute('disabled', 'true');
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
                updateBatchButtons();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBatchButtons);
        });

        // Print batch KWT
        if (btnBatchKwt) {
            btnBatchKwt.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length > 0) {
                    window.open("{{ route('admin.invoice.kwt.batch') }}?ids=" + selectedIds.join(','), '_blank');
                }
            });
        }

        // Print batch Kurir
        if (btnBatchKurir) {
            btnBatchKurir.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length > 0) {
                    window.open("{{ route('admin.invoice.kurir.batch') }}?ids=" + selectedIds.join(','), '_blank');
                }
            });
        }
    });
</script>
@endsection