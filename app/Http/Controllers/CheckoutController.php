<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        if (!$request->has('items')) {
            return redirect()->route('cart.index')->with('error', 'Pilih produk!');
        }

        $ids = explode(',', $request->query('items'));

        $cartItems = Cart::with('product.user')
            ->whereIn('id', $ids)
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);
        $user = Auth::user();

        $alamatCustomer = trim(($user->address ?? '') . ', ' . ($user->district ?? '') . ', ' . ($user->city ?? '') . ', ' . ($user->province ?? ''));

        $latAsal = -6.921186;
        $lonAsal = 107.729420;

        $jarak = 0;
        $ongkir = 0;

        // 2. HITUNG JARAK BERDASARKAN LATITUDE & LONGITUDE USER
        if (!empty($user->latitude) && !empty($user->longitude)) {
            $earthRadius = 6371; // Radius bumi dalam kilometer

            $latDelta = deg2rad($user->latitude - $latAsal);
            $lonDelta = deg2rad($user->longitude - $lonAsal);

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                cos(deg2rad($latAsal)) * cos(deg2rad($user->latitude)) *
                sin($lonDelta / 2) * sin($lonDelta / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            // Jarak dalam KM
            $jarak = round($earthRadius * $c, 1);

            // 3. TARIF: 1 KM = Rp 4.000
            $tarifPerKm = 4000;
            $ongkir = $jarak * $tarifPerKm;

            // Opsional: Pembulatan ke atas agar tidak ada angka receh (misal ke kelipatan 500)
            $ongkir = ceil($ongkir / 500) * 500;
        }

        $dataWilayah = [
            'Kota Bandung' => [
                'Andir',
                'Astana Anyar',
                'Antapani',
                'Arcamanik',
                'Babakan Ciparay',
                'Bandung Kidul',
                'Bandung Kulon',
                'Bandung Wetan',
                'Batununggal',
                'Bojongloa Kaler',
                'Bojongloa Kidul',
                'Buahbatu',
                'Cibeunying Kaler',
                'Cibeunying Kidul',
                'Cibiru',
                'Cicendo',
                'Cidadap',
                'Cinambo',
                'Coblong',
                'Gedebage',
                'Kiaracondong',
                'Lengkong',
                'Mandalajati',
                'Panyileukan',
                'Rancasari',
                'Regol',
                'Sukajadi',
                'Sukasari',
                'Sumur Bandung',
                'Ujungberung'
            ],
            'Kabupaten Bandung' => [
                'Arjasari',
                'Baleendah',
                'Banjaran',
                'Bojongsoang',
                'Cangkuang',
                'Cicalengka',
                'Cimenyan',
                'Cileunyi',
                'Cilengkrang',
                'Cimaung',
                'Ciparay',
                'Ciwidey',
                'Dayeuhkolot',
                'Ibun',
                'Katapang',
                'Kertasari',
                'Kutawaringin',
                'Majalaya',
                'Margaasih',
                'Margahayu',
                'Nagreg',
                'Pacet',
                'Pameungpeuk',
                'Pangalengan',
                'Paseh',
                'Pasirjambu',
                'Rancaekek',
                'Solokanjeruk',
                'Soreang',
                'Rancabali',
                'Tegalluar'
            ]
        ];

        return view('customer.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'ongkir' => $ongkir,
            'totalBayar' => $subtotal + $ongkir,
            'jarak' => $jarak,
            'alamatCustomer' => $alamatCustomer,
            'dataWilayah' => $dataWilayah,
            'latKWT' => $latAsal, // Mengirim koordinat kantor desa sebagai acuan peta
            'lonKWT' => $lonAsal
        ]);
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            if (!$user->phone_number) {
                return back()->with('error', 'Lengkapi nomor HP di profil!');
            }

            $ids = explode(',', $request->item_ids);
            $cartItems = Cart::with('product')->whereIn('id', $ids)->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception("Keranjang belanja kosong!");
            }

            $request->validate([
                'ongkir' => 'required|numeric',
                'jarak' => 'required|numeric',
                'kota_kab' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'rtrw' => 'required',
                'detail_alamat' => 'required',
            ]);

            $subtotal = $cartItems->sum(fn($i) => $i->jumlah * $i->product->harga);
            $ongkir = $request->input('ongkir', 0);

            $alamatFinal = $request->input('detail_alamat') . ', ' .
                $request->input('rtrw') . ', Kel/Desa ' .
                $request->input('kelurahan') . ', Kecamatan ' .
                $request->input('kecamatan') . ', ' .
                $request->input('kota_kab') . ', Jawa Barat';

            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $subtotal + $ongkir,
                'ongkir' => $ongkir,
                'status' => 'menunggu',
                'catatan' => $request->catatan,
                'alamat' => $alamatFinal,
                'nomor_hp' => $user->phone_number,
                'kurir' => 'Menunggu penugasan',
                'no_hp_kurir' => '-'
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga,
                ]);
                $item->product->decrement('stok', $item->jumlah);
                $item->delete();
            }

            DB::commit();
            return redirect()->route('orders.history')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
