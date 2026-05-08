@extends('layouts.admin')

@section('content')

<h2 class="fw-bold mb-4">
    Kelola Akun KWT
</h2>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <form method="POST" action="{{ route('admin.kwt.store') }}">

            @csrf

            <div class="mb-3">
                <label>Nama KWT</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button class="btn btn-success">
                Tambah Akun
            </button>

        </form>

    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            Daftar Akun KWT
        </h5>

        <table class="table">

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>

                @foreach($kwts as $kwt)

                <tr>
                    <td>{{ $kwt->name }}</td>
                    <td>{{ $kwt->email }}</td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection