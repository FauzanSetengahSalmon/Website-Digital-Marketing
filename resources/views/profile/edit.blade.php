@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<!-- Muat CSS Pendukung -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --green-dark: #065f46;
        --green-light: #10b981;
        --green-hover: #059669;
        --bg-soft: #f8fafc;
        --border-color: #e2e8f0;
    }

    body {
        background-color: var(--bg-soft);
        color: #334155;
    }

    .profile-container {
        max-width: 1200px;
        margin: auto;
    }

    .custom-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-header-green {
        background: linear-gradient(135deg, var(--green-dark), #047857);
        padding: 24px 32px;
        color: white;
    }

    .header-profile-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        border: 3px solid rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    .header-profile-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .header-profile-letter {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green-dark);
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .input-group-text {
        background-color: #f1f5f9;
        border-color: var(--border-color);
        color: #64748b;
        border-radius: 10px 0 0 10px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid var(--border-color);
        padding: 10px 14px;
        font-size: 0.92rem;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--green-light);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .btn-green {
        background-color: var(--green-light);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .btn-green:hover {
        background-color: var(--green-hover);
        color: white;
        transform: translateY(-1px);
    }

    /* Avatar Side Box */
    .avatar-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 0 auto;
    }

    .avatar-big {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        background: #e2e8f0;
        cursor: pointer;
    }

    .avatar-big img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-big-letter {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: var(--green-dark);
    }

    .camera-overlay-btn {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--green-light);
        color: white;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: 0.2s;
    }

    .camera-overlay-btn:hover {
        background-color: var(--green-hover);
    }

    /* Info Item Sidebar */
    .info-box-wrapper {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 12px;
    }

    .info-box-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: white;
        color: var(--green-light);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        font-size: 1rem;
    }

    /* Map Design */
    #map {
        width: 100%;
        height: 320px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .search-wrapper {
        position: relative;
    }

    .autocomplete-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 0 0 12px 12px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .suggestion-item {
        padding: 10px 16px;
        cursor: pointer;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .suggestion-item:hover {
        background-color: #f1f5f9;
    }
</style>

<div class="container py-5">
    <div class="profile-container">

        {{-- TOP BANNER/TITLE --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold m-0 text-dark">Profil <span class="text-success">Customer</span></h2>
                <p class="text-muted m-0 small">Kelola data informasi akun, alamat utama, dan keamanan password Anda.</p>
            </div>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success') || session('status') === 'profile-updated')
        <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') ?: 'Profil Anda berhasil diperbarui dengan aman.' }}</div>
        </div>
        @endif

        @if ($errors->any() || $errors->updatePassword->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Periksa Kembali Isian Anda:</div>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                @foreach ($errors->updatePassword->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row g-4">

            {{-- SISI KIRI (FORM UTAMA) --}}
            <div class="col-xl-8 col-lg-7">

                {{-- CARD UTAMA DATA PROFIL --}}
                <div class="custom-card">
                    <div class="card-header-green">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-profile-wrapper">
                                @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto Profil">
                                @else
                                <div class="header-profile-letter">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-bold m-0 mb-1 text-white">Informasi Pengiriman & Akun</h5>
                                <p class="m-0 text-white-50 small">Pastikan koordinat lokasi dan data alamat sudah akurat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', Auth::user()->latitude) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', Auth::user()->longitude) }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Akun <span class="text-muted small">(Read-Only)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" class="form-control bg-light" value="{{ old('email', Auth::user()->email) }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number) }}" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" id="district" name="district" class="form-control" value="{{ old('district', Auth::user()->district) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kota / Kabupaten</label>
                                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city', Auth::user()->city) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" id="province" name="province" class="form-control" value="{{ old('province', Auth::user()->province) }}">
                                </div>

                                {{-- MAPS INTEGRATION --}}
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                        <label class="form-label fw-bold text-dark m-0">Alamat Berdasarkan Peta Fisik</label>
                                        <button type="button" class="btn btn-xs btn-outline-success py-1 px-2 rounded-3 small" onclick="getLocationUser()" style="font-size: 0.78rem;">
                                            <i class="bi bi-geo-alt-fill me-1"></i> Gunakan GPS Perangkat
                                        </button>
                                    </div>

                                    <div class="search-wrapper mb-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                                            <input type="text" id="search-address" class="form-control text-sm" placeholder="Ketik cepat nama jalan / desa / wilayah di Indonesia..." autocomplete="off">
                                        </div>
                                        <div id="suggestions-box" class="autocomplete-suggestions d-none"></div>
                                    </div>

                                    <div id="map" class="shadow-sm mb-3"></div>

                                    <label class="form-label text-secondary small">Alamat Jalan / Blok Hasil Peta</label>
                                    <textarea id="address-textarea" name="address" rows="2" class="form-control mb-3" placeholder="Pilih titik pada peta diatas untuk mengisi otomatis data alamat..." required>{{ old('address', Auth::user()->address) }}</textarea>

                                    {{-- BOX INPUT MANDIRI: RT, RW, DETAIL --}}
                                    <div class="p-3 rounded-3 border bg-light">
                                        <div class="row g-2">
                                            <div class="col-6 col-sm-3">
                                                <label class="form-label text-dark small fw-bold">Rukun Tetangga (RT)</label>
                                                <input type="text" name="rt" class="form-control" value="{{ old('rt', Auth::user()->rt) }}" placeholder="Ex: 002" maxlength="5">
                                            </div>
                                            <div class="col-6 col-sm-3">
                                                <label class="form-label text-dark small fw-bold">Rukun Warga (RW)</label>
                                                <input type="text" name="rw" class="form-control" value="{{ old('rw', Auth::user()->rw) }}" placeholder="Ex: 011" maxlength="5">
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <label class="form-label text-dark small fw-bold">Detail Tambahan Bangunan</label>
                                                <input type="text" name="address_detail" class="form-control" value="{{ old('address_detail', Auth::user()->address_detail) }}" placeholder="Nomor rumah, warna pagar, nama toko patokan...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-text text-muted d-flex align-items-start gap-2 mt-2" style="font-size: 0.8rem; line-height: 1.4;">
                                        <i class="bi bi-info-circle-fill text-warning flex-shrink-0 fs-6"></i>
                                        <span>
                                            <strong>Catatan Kurir Ekspedisi:</strong> Alamat otomatis dari peta satelit terkadang kurang spesifik. Mengisi data **RT, RW, dan Detail Tambahan** secara benar mencegah paket Anda tertukar atau telat sampai.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 border-top pt-3 text-end">
                                <button type="submit" class="btn-green shadow-sm">
                                    <i class="bi bi-shield-check me-1"></i> Perbarui Data Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- CARD KEAMANAN AKUN (PASSWORD) --}}
                <div class="custom-card">
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h5 class="security-title fw-bold m-0 text-dark">
                                <i class="bi bi-shield-lock-fill text-danger me-1"></i> Proteksi Kredensial Akun
                            </h5>
                        </div>
                        <p class="text-muted small mb-4">Ganti berkala kata sandi Anda agar akun terhindar dari akses yang tidak sah.</p>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Password Baru Anda</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Konfirmasi Ulang Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="mt-4 pt-2 border-top text-start">
                                <button type="submit" class="btn btn-dark rounded-3 px-4 py-2 text-white font-weight-bold" style="font-size: 0.85rem;">
                                    <i class="bi bi-key-fill me-1"></i> Enkripsi Password Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- SISI KANAN (PREVIEW DATA USER) --}}
            <div class="col-xl-4 col-lg-5">
                <div class="custom-card p-4 text-center">

                    {{-- MANAGE FOTO PROFIL --}}
                    <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <div class="avatar-container">
                            <div class="avatar-big" onclick="document.getElementById('photoInput').click()">
                                @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto">
                                @else
                                <div class="avatar-big-letter">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                </div>
                                @endif
                            </div>

                            <button type="button" class="camera-overlay-btn" onclick="document.getElementById('photoInput').click()">
                                <i class="bi bi-camera-fill text-white fs-6"></i>
                            </button>

                            <input type="file" name="profile_photo" id="photoInput" class="d-none" onchange="document.getElementById('photoForm').submit()">
                        </div>
                    </form>

                    <h4 class="fw-bold text-dark mt-3 mb-1">{{ Auth::user()->name }}</h4>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-patch-check-fill me-1"></i> Customer Terverifikasi
                    </span>

                    <hr class="my-4 text-muted opacity-25">

                    {{-- LIVE PREVIEW ALAMAT & DATA PENGIRIMAN --}}
                    <div class="text-start">
                        <div class="info-box-wrapper d-flex align-items-center gap-3">
                            <div class="info-box-icon"><i class="bi bi-envelope-open-fill"></i></div>
                            <div class="overflow-hidden">
                                <div class="text-muted small" style="font-size: 0.72rem;">Alamat Email Aktif</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <div class="info-box-wrapper d-flex align-items-center gap-3">
                            <div class="info-box-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <div class="text-muted small" style="font-size: 0.72rem;">Nomor Kontak WhatsApp</div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->phone_number ?: 'Belum terikat' }}</div>
                            </div>
                        </div>

                        <div class="info-box-wrapper d-flex align-items-start gap-3">
                            <div class="info-box-icon mt-1"><i class="bi bi-geo-alt-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="text-muted small mb-1" style="font-size: 0.72rem;">Lokasi Pengiriman Paket</div>
                                <div class="text-dark fw-normal" style="font-size: 0.88rem; line-height: 1.4;">
                                    @if(Auth::user()->address || Auth::user()->district || Auth::user()->city || Auth::user()->province)
                                    <!-- Alamat Utama -->
                                    <span class="d-block fw-bold mb-1">{{ Auth::user()->address }}</span>

                                    <!-- Menampilkan RT/RW & Detail Tambahan Secara Transparan dan Pasti Muncul Jika Terisi -->
                                    <div class="bg-white p-2 rounded border my-2 small">
                                        <span class="d-block text-secondary">
                                            <strong>RT/RW:</strong> {{ Auth::user()->rt ?? '-' }} / {{ Auth::user()->rw ?? '-' }}
                                        </span>
                                        <span class="d-block text-secondary mt-1">
                                            <strong>Detail Rumah:</strong> {{ Auth::user()->address_detail ?? 'Tidak ada detail tambahan' }}
                                        </span>
                                    </div>

                                    <span class="text-success fw-bold small">
                                        {{ Auth::user()->district ? Auth::user()->district.', ' : '' }}
                                        {{ Auth::user()->city ? Auth::user()->city.', ' : '' }}
                                        {{ Auth::user()->province ?? '' }}
                                    </span>
                                    @else
                                    <span class="text-muted small italic">Belum mengisi rincian alamat pengiriman.</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-box-wrapper d-flex align-items-center gap-3 mb-0">
                            <div class="info-box-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <div class="text-muted small" style="font-size: 0.72rem;">Tanggal Registrasi</div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC (LEAFLET & AUTOCOMPLETE) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let saveLat = "{{ Auth::user()->latitude }}";
    let saveLng = "{{ Auth::user()->longitude }}";

    let defaultLat = saveLat ? parseFloat(saveLat) : -2.5489;
    let defaultLng = saveLng ? parseFloat(saveLng) : 118.0149;
    let zoomLevel = saveLat? 16 : 5;

    const map = L.map('map').setView([defaultLat, defaultLng], zoomLevel);

    L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    // Tambahkan pengisian value latitude dan longitude di sini
    function parseAddressData(addressObj, displayName, lat, lng) {
        document.getElementById('address-textarea').value = displayName;
        document.getElementById('district').value = addressObj.village || addressObj.suburb || addressObj.subdistrict || addressObj.municipality || '';
        document.getElementById('city').value = addressObj.city || addressObj.regency || addressObj.county || addressObj.town || '';
        document.getElementById('province').value = addressObj.state || '';

        if (lat && lng) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }
    }

    function updateAddressInputs(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    parseAddressData(data.address, data.display_name, lat, lng);
                }
            })
            .catch(error => console.error('Error memuat data peta:', error));
    }

    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateAddressInputs(lat, lng);
    });

    marker.on('dragend', function(e) {
        const lat = marker.getLatLng().lat;
        const lng = marker.getLatLng().lng;
        updateAddressInputs(lat, lng);
    });

    function getLocationUser() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                updateAddressInputs(lat, lng);
            }, function() {
                alert('Gagal mendeteksi lokasi otomatis. Silakan klik manual di peta.');
            });
        } else {
            alert('Browser Anda tidak mendukung deteksi GPS.');
        }
    }

    const searchInput = document.getElementById('search-address');
    const suggestionsBox = document.getElementById('suggestions-box');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;

        if (query.length < 3) {
            suggestionsBox.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&countrycodes=id&q=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(results => {
                    suggestionsBox.innerHTML = '';
                    if (results.length > 0) {
                        suggestionsBox.classList.remove('d-none');
                        results.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'suggestion-item';
                            div.innerText = item.display_name;

                            div.addEventListener('click', function() {
                                searchInput.value = item.display_name;
                                suggestionsBox.classList.add('d-none');

                                const lat = item.lat;
                                const lng = item.lon;

                                map.setView([lat, lng], 16);
                                marker.setLatLng([lat, lng]);

                                parseAddressData(item.address, item.display_name, lat, lng);
                            });
                            suggestionsBox.appendChild(div);
                        });
                    } else {
                        suggestionsBox.classList.add('d-none');
                    }
                });
        }, 500);
    });

    document.addEventListener('click', function(e) {
        if (e.target !== searchInput && e.target !== suggestionsBox) {
            suggestionsBox.classList.add('d-none');
        }
    });
</script>
@endsection