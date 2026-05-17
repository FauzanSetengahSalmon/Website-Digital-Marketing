@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<style>
    body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }
    .checkout-card { background: #fff; border: 1px solid #edf2f7; border-radius: 20px; padding: 24px; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04); }
    .checkout-title { color: #16a34a; font-weight: 800; font-size: 1.3rem; }
    .img-checkout { width: 70px; height: 70px; object-fit: cover; border-radius: 14px; border: 1px solid #edf2f7; }
    .summary-side { background: #fff; border: 1px solid #edf2f7; border-radius: 20px; padding: 24px; position: sticky; top: 20px; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04); }
    .btn-confirm { background: linear-gradient(135deg, #16a34a, #22c55e); color: white; padding: 13px; border-radius: 12px; font-weight: 700; width: 100%; border: none; transition: .2s; }
    .btn-confirm:hover { transform: translateY(-1px); background: linear-gradient(135deg, #15803d, #16a34a); }
    .btn-confirm:disabled { background: #cbd5e1; cursor: not-allowed; }
    .address-user { width: 46px; height: 46px; border-radius: 14px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .address-name { font-size: .95rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
    #map-container { width: 100%; height: 320px; border-radius: 14px; margin-top: 15px; border: 1px solid #e2e8f0; z-index: 1; }
    .leaflet-routing-container { display: none !important; }
    .product-item { background: white; border: 1px solid #edf2f7; border-radius: 18px; padding: 16px; transition: .2s; }
    .product-name { font-size: .95rem; font-weight: 700; color: #0f172a; }
    .product-desc { font-size: .82rem; color: #64748b; }
    .product-price { font-weight: 700; color: #16a34a; }
    .summary-total { font-size: 1.4rem; font-weight: 800; color: #16a34a; }
    .btn-cek-ongkir { background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; transition: 0.2s; }
    .btn-cek-ongkir:hover { background: #1d4ed8; }
    .hint-map { font-size: 0.8rem; color: #2563eb; background: #eff6ff; padding: 8px 12px; border-radius: 8px; border: 1px dashed #bfdbfe; }
    @media(max-width: 992px) { .summary-side { position: static; } }
</style>

<div class="container py-5">
    <div class="row g-4">
        {{-- SEKTOR KIRI --}}
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="checkout-title">Detail Pembayaran</div>
                    <div class="text-muted small">Konfirmasi lokasi kirim Bandung area</div>
                </div>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Checkout</span>
            </div>

            <div class="checkout-card mb-4">
                <div class="d-flex gap-3 w-100">
                    <div class="address-user"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="flex-grow-1">
                        <div class="address-name">{{ Auth::user()->name }}</div>
                        
                        <div class="mt-3">
                            <div class="hint-map mb-3">
                                <i class="bi bi-info-circle-fill me-1"></i> <strong>Cara Pemesanan:</strong> Pilih Kota & Kecamatan, ketik RT/RW serta Kelurahan. Kemudian klik tombol biru di bawah agar peta otomatis mencari area Anda. Anda juga bisa klik langsung di peta untuk menyesuaikan titik rumah.
                            </div>

                            <label class="form-label small fw-bold text-muted mb-2">Konfirmasi Alamat Pengiriman:</label>
                            
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="text-muted mb-1" style="font-size: 0.8rem;">Kota / Kabupaten</label>
                                    <select id="select_kotakab" class="form-select" required>
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                        @foreach(array_keys($dataWilayah) as $wilayah)
                                            <option value="{{ $wilayah }}">{{ $wilayah }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted mb-1" style="font-size: 0.8rem;">Kecamatan</label>
                                    <select id="select_kecamatan" class="form-select" required disabled>
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 mt-2">
                                    <label class="text-muted mb-1" style="font-size: 0.8rem;">Kelurahan / Desa</label>
                                    <input type="text" id="input_kelurahan" class="form-control" placeholder="Ketik nama kelurahan/desa..." required>
                                </div>
                                <div class="col-sm-4 mt-2">
                                    <label class="text-muted mb-1" style="font-size: 0.8rem;">RT / RW</label>
                                    <input type="text" id="input_rtrw" class="form-control" placeholder="Contoh: RT 02 / RW 05" required>
                                </div>
                                <div class="col-sm-4 mt-2">
                                    <label class="text-muted mb-1" style="font-size: 0.8rem;">No. Rumah / Detail Jalan</label>
                                    <input type="text" id="input_detail_alamat" class="form-control" placeholder="Nama jalan / nomor rumah..." required>
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-muted" style="font-size: 0.75rem;">*Setiap mengubah form, klik tombol di kanan untuk memperbarui peta.</span>
                                <button type="button" id="btn_hitung_ulang" class="btn-cek-ongkir">
                                    <i class="bi bi-geo-fill me-1"></i> Cari Area & Hubungkan Peta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="map-container"></div>
            </div>

            <div class="mb-3"><h5 class="fw-bold">Produk yang Dibeli</h5></div>
            @foreach($cartItems as $item)
            <div class="product-item d-flex align-items-center gap-3 mb-3">
                <img src="{{ asset('storage/'.$item->product->foto_produk) }}" class="img-checkout">
                <div class="flex-grow-1">
                    <div class="product-name">{{ $item->product->nama_produk }}</div>
                    <div class="product-desc">{{ $item->jumlah }} {{ $item->product->satuan }} × Rp {{ number_format($item->product->harga, 0, ',', '.') }}</div>
                </div>
                <div class="product-price">Rp {{ number_format($item->jumlah * $item->product->harga, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        {{-- SEKTOR KANAN --}}
        <div class="col-lg-5">
            <div class="summary-side">
                <h5 class="fw-bold mb-4">Ringkasan Transaksi</h5>
                <div class="d-flex justify-content-between mb-3"><span class="text-muted">Total Harga Produk</span><span class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Ongkos Kirim (<span id="text_jarak" class="fw-bold text-dark">{{ $jarak }} km</span>)</span>
                    <span class="text-success fw-semibold" id="text_ongkir">+ Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-4"><span class="fw-bold">Total Bayar</span><div class="summary-total" id="text_total_bayar">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div></div>

                <form action="{{ route('checkout.process') }}" method="POST" id="form_checkout">
                    @csrf
                    <input type="hidden" name="item_ids" value="{{ implode(',', $cartItems->pluck('id')->toArray()) }}">
                    <input type="hidden" name="ongkir" id="input_ongkir" value="{{ $ongkir }}">
                    <input type="hidden" name="jarak" id="input_jarak" value="{{ $jarak }}">
                    
                    <input type="hidden" name="kota_kab" id="hidden_kotakab">
                    <input type="hidden" name="kecamatan" id="hidden_kecamatan">
                    <input type="hidden" name="kelurahan" id="hidden_kelurahan">
                    <input type="hidden" name="rtrw" id="hidden_rtrw">
                    <input type="hidden" name="detail_alamat" id="hidden_detail_alamat">
                    <input type="hidden" name="alamat_custom" id="alamat_custom_input" value="{{ $alamatCustomer }}">

                    <div class="mb-3">
                        <label class="small text-muted mb-2">Catatan Pesanan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Titip depan pagar"></textarea>
                    </div>
                    <button type="submit" class="btn-confirm" id="btn_submit_order" disabled>BUAT PESANAN</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const latKwt = parseFloat("{{ $latKWT }}");
        const lonKwt = parseFloat("{{ $lonKWT }}");
        const subtotal = parseFloat("{{ $subtotal }}");
        const tarifPerKm = 8000;
        const dataWilayah = @json($dataWilayah);

        let map = L.map('map-container').setView([latKwt, lonKwt], 13);
        let routingControl = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([latKwt, lonKwt]).addTo(map).bindPopup("<b>Toko KWT (Asal)</b>").openPopup();

        // 1. DROPDOWN DINAMIS KOTA -> KECAMATAN
        document.getElementById('select_kotakab').addEventListener('change', function() {
            const wilayahSelected = this.value;
            const kecSelect = document.getElementById('select_kecamatan');
            
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            if (wilayahSelected && dataWilayah[wilayahSelected]) {
                dataWilayah[wilayahSelected].forEach(function(kec) {
                    kecSelect.innerHTML += `<option value="${kec}">${kec}</option>`;
                });
                kecSelect.disabled = false;
            } else {
                kecSelect.disabled = true;
            }
        });

        // 2. LOGIKA KUNCI ROUTING VIA TOMBOL BIRU
        document.getElementById('btn_hitung_ulang').addEventListener('click', function() {
            const kabkota = document.getElementById('select_kotakab').value;
            const kec = document.getElementById('select_kecamatan').value;
            const kel = document.getElementById('input_kelurahan').value.trim();
            const rtrw = document.getElementById('input_rtrw').value.trim();
            const detail = document.getElementById('input_detail_alamat').value.trim();

            if (!kabkota || !kec || !kel || !detail || !rtrw) {
                alert("Mohon isi Kota, Kecamatan, Kelurahan, RT/RW dan Detail Jalan/No Rumah terlebih dahulu!");
                return;
            }

            this.innerHTML = 'Memproses...'; this.disabled = true;
            
            // 🟢 FIX UTAMA: Bersihkan imbuhan kata "Kota" atau "Kabupaten" agar pencarian text OSRM/Nominatim akurat dan mau pindah
            const namaDaerahBersih = kabkota.replace('Kota ', '').replace('Kabupaten ', '');
            
            // Format pencarian teks dibikin super matang agar peta langsung lompat ke area pembeli
            const queryPencarianPeta = `${detail}, ${kel}, ${kec}, ${namaDaerahBersih}, Jawa Barat`;
            prosesHitungJarak(queryPencarianPeta, kec, namaDaerahBersih);
        });

        // 3. EVENT MAP CLICK MANUAL
        map.on('click', function(e) {
            map.panTo(new L.LatLng(e.latlng.lat, e.latlng.lng));
            hitungRuteDanOngkir(e.latlng.lat, e.latlng.lng);
        });

        function prosesHitungJarak(alamatQuery, namaKecamatan, namaKabKota) {
            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(alamatQuery)}&format=json&limit=1`, {
                headers: { 'User-Agent': 'EFoodAppTracking' }
            })
            .then(res => res.json())
            .then(dataCust => {
                // Skenario 1: Pencarian detail jalan ketemu
                if (dataCust.length > 0) {
                    const latDitemukan = parseFloat(dataCust[0].lat);
                    const lonDitemukan = parseFloat(dataCust[0].lon);
                    map.setView([latDitemukan, lonDitemukan], 15);
                    hitungRuteDanOngkir(latDitemukan, lonDitemukan);
                } else {
                    // Skenario 2: Jika jalan / RT RW tidak terdaftar, cari cakupan wilayah Kecamatannya saja (Cadangan otomatis)
                    const fallbackQuery = `Kecamatan ${namaKecamatan}, ${namaKabKota}, Jawa Barat`;
                    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(fallbackQuery)}&format=json&limit=1`, {
                        headers: { 'User-Agent': 'EFoodAppTracking' }
                    })
                    .then(r => r.json())
                    .then(dataFallback => {
                        if(dataFallback.length > 0) {
                            const latFallback = parseFloat(dataFallback[0].lat);
                            const lonFallback = parseFloat(dataFallback[0].lon);
                            map.setView([latFallback, lonFallback], 14);
                            hitungRuteDanOngkir(latFallback, lonFallback);
                        } else {
                            alert("Titik wilayah belum dikunci. Silakan klik manual posisi rumah Anda langsung pada area peta!");
                            resetTombolCek();
                        }
                    });
                }
            }).catch(() => {
                alert("Gagal memuat otomatis, silakan klik langsung posisi rumah Anda pada peta.");
                resetTombolCek();
            });
        }

        function hitungRuteDanOngkir(latCust, lonCust) {
            if (routingControl !== null) { 
                map.removeControl(routingControl); 
                routingControl = null;
            }

            routingControl = L.Routing.control({
                waypoints: [ L.latLng(latKwt, lonKwt), L.latLng(latCust, lonCust) ],
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'driving',
                    useHints: false 
                }),
                lineOptions: { 
                    styles: [{ color: '#16a34a', weight: 5, opacity: 0.85 }],
                    addWaypoints: false 
                },
                createMarker: function(i, wp) {
                    if (i === 0) return L.marker(wp.latLng).bindPopup("Toko KWT");
                    
                    const customerMarker = L.marker([latCust, lonCust], { draggable: true }).bindPopup("Titik Rumah Anda (Bisa digeser)");
                    
                    customerMarker.on('dragend', function(event) {
                        const position = event.target.getLatLng();
                        hitungRuteDanOngkir(position.lat, position.lng);
                    });
                    return customerMarker;
                },
                addWaypoints: false
            }).on('routesfound', function(e) {
                const distanceInKm = (e.routes[0].summary.totalDistance / 1000).toFixed(1);
                const calculatedOngkir = Math.round(distanceInKm * tarifPerKm);
                
                document.getElementById('text_jarak').innerText = `${distanceInKm} km`;
                document.getElementById('text_ongkir').innerText = `+ Rp ${calculatedOngkir.toLocaleString('id-ID')}`;
                document.getElementById('text_total_bayar').innerText = `Rp ${(subtotal + calculatedOngkir).toLocaleString('id-ID')}`;

                document.getElementById('input_jarak').value = distanceInKm;
                document.getElementById('input_ongkir').value = calculatedOngkir;
                
                const kabkota = document.getElementById('select_kotakab').value;
                const kec = document.getElementById('select_kecamatan').value;
                const kel = document.getElementById('input_kelurahan').value;
                const rtrw = document.getElementById('input_rtrw').value;
                const detail = document.getElementById('input_detail_alamat').value;

                document.getElementById('hidden_kotakab').value = kabkota;
                document.getElementById('hidden_kecamatan').value = kec;
                document.getElementById('hidden_kelurahan').value = kel;
                document.getElementById('hidden_rtrw').value = rtrw;
                document.getElementById('hidden_detail_alamat').value = detail;
                document.getElementById('alamat_custom_input').value = `${detail}, ${rtrw}, Kel. ${kel}, Kec. ${kec}, ${kabkota}, Jawa Barat`;

                document.getElementById('btn_submit_order').disabled = false;
                resetTombolCek();
            }).addTo(map);
        }

        function resetTombolCek() {
            const btn = document.getElementById('btn_hitung_ulang');
            btn.innerHTML = '<i class="bi bi-geo-fill me-1"></i> Cari Area & Hubungkan Peta';
            btn.disabled = false;
        }
    });
</script>
@endsection