<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kwt;
use Illuminate\Http\Request;

class KwtController extends Controller
{
    public function index()
    {
        $kwts = Kwt::with('user')->get();
        return response()->json($kwts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kwt'  => 'required|string|max:255',
            'alamat'  => 'required|string',
            'kontak'  => 'required|string',
            'user_id'  => 'required|exists:users,id',
        ]);

        $kwt = Kwt::create($validated);
        return response()->json(['message' => 'KWT berhasil didaftarkan', 'data' => $kwt], 201);
    }
}