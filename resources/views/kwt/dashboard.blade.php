@extends('layouts.kwt')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Ringkasan Bisnis KWT</h2>
        <span class="badge bg-success px-3 py-2 rounded-pill">Status: Aktif</span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-5">
                <small class="text-muted fw-bold text-uppercase">Hasil Pendapatan</small>
                <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($stats['total_received'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-5">
                <small class="text-muted fw-bold text-uppercase">Produk Terjual</small>
                <h3 class="fw-bold mb-0">{{ $stats['sold_count'] ?? 0 }} <span class="fs-6 fw-normal text-muted">Item</span></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-5">
                <small class="text-muted fw-bold text-uppercase">Total Produk</small>
                <h3 class="fw-bold mb-0">{{ $stats['total_products'] ?? 0 }} <span class="fs-6 fw-normal text-muted">Jenis</span></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-5">
                <small class="text-muted fw-bold text-uppercase">Pesanan Baru</small>
                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_orders'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center mt-5">
            <img src="https://illustrations.popsy.co/green/data-analysis.svg" style="height: 200px;" alt="illustration">
            <p class="text-muted mt-3">Selamat bekerja! Pantau pesanan masuk secara berkala.</p>
        </div>
    </div>
</div>
@endsection