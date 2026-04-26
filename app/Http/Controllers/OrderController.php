<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        return view('customer.riwayat-pesanan');
    }

    public function show($id)
    {
        return "Halaman detail pesanan untuk ID: " . $id;
    }
}