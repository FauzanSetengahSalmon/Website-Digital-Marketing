<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = trim(env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') == 'true' || env('MIDTRANS_IS_PRODUCTION') == true;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Menampilkan halaman checkout beserta kalkulasi jarak Haversine otomatis
     */
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
        $totalQtyBarang = $cartItems->sum('jumlah'); // Hitung total item fisik
        $user = Auth::user();

        $alamatCustomer = trim(($user->address ?? '') . ', ' . ($user->district ?? '') . ', ' . ($user->city ?? '') . ', ' . ($user->province ?? ''));

        $latAsal = -6.921186;
        $lonAsal = 107.729420;

        $jarak = 0;
        $ongkir = 0;

        $setting = Setting::first() ?? new Setting([
            'tarif_per_km' => 2000,
            'minimal_km' => 1,
            'maksimal_km' => 15,
            'biaya_layanan' => 2000,
            'batas_jumlah_barang' => 0,
            'biaya_tambahan_per_barang' => 0
        ]);

        $biayaLayanan = $setting->biaya_layanan; // Murni Biaya Admin

        // HITUNG JARAK BERDASARKAN LATITUDE & LONGITUDE USER
        if (!empty($user->latitude) && !empty($user->longitude)) {
            $earthRadius = 6371;

            $latDelta = deg2rad($user->latitude - $latAsal);
            $lonDelta = deg2rad($user->longitude - $lonAsal);

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                cos(deg2rad($latAsal)) * cos(deg2rad($user->latitude)) *
                sin($lonDelta / 2) * sin($lonDelta / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $jarak = round($earthRadius * $c, 1);

            if ($jarak > $setting->maksimal_km) {
                return redirect()->route('cart.index')->with('error', 'Maaf, lokasi pengiriman Anda terlalu jauh (Maksimal ' . $setting->maksimal_km . ' KM dari Pusat KWT).');
            }

            if ($jarak < $setting->minimal_km) {
                $jarak = $setting->minimal_km;
            }

            $ongkir = $jarak * $setting->tarif_per_km;
            $ongkir = ceil($ongkir / 500) * 500;

            // 🌟 LOGIKA BIAYA TAMBAHAN VOLUME BARANG (MASUK KE ONGKIR KURIR) 🌟
            if ($setting->batas_jumlah_barang > 0 && $totalQtyBarang > $setting->batas_jumlah_barang) {
                $kelipatan = ceil($totalQtyBarang / $setting->batas_jumlah_barang) - 1;
                $biayaEkstraKurir = $kelipatan * $setting->biaya_tambahan_per_barang;
                $ongkir += $biayaEkstraKurir;
            }
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
            'ongkir' => $ongkir, // Ongkir sudah termasuk biaya ekstra volume
            'biayaLayanan' => $biayaLayanan,
            'totalBayar' => $subtotal + $ongkir + $biayaLayanan,
            'jarak' => $jarak,
            'alamatCustomer' => $alamatCustomer,
            'dataWilayah' => $dataWilayah,
            'latKWT' => $latAsal,
            'lonKWT' => $lonAsal
        ]);
    }

    /**
     * Memproses pesanan ke database dan memicu pembuatan Token Snap Midtrans
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        if (!$user->phone_number) {
            return response()->json(['success' => false, 'message' => 'Lengkapi nomor HP di profil Anda!'], 400);
        }

        $ids = explode(',', $request->item_ids);
        $cartItems = Cart::with('product')->whereIn('id', $ids)->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang belanja Anda kosong!'], 400);
        }

        $setting = Setting::first() ?? new Setting([
            'biaya_layanan' => 2000,
            'batas_jumlah_barang' => 0,
            'biaya_tambahan_per_barang' => 0
        ]);

        $biaya_layanan = $setting->biaya_layanan; // Murni milik admin
        $subtotal = $cartItems->sum(fn($i) => $i->jumlah * $i->product->harga);

        // Ongkir yang dikirim dari form frontend sudah termasuk biaya tambahan volume kurir
        $ongkir = (int) $request->input('ongkir', 0);

        $totalPembayaran = $subtotal + $ongkir + $biaya_layanan;

        $alamatFinal = $user->address;
        $rtRw = (!empty($user->rt) && !empty($user->rw)) ? ' RT.' . $user->rt . ' / RW.' . $user->rw : '';
        $kecamatan = !empty($user->district) ? ', Kecamatan ' . $user->district : '';
        $kota = !empty($user->city) ? ', ' . $user->city : '';
        $provinsi = !empty($user->province) ? ', ' . $user->province : ', Jawa Barat';

        $alamatFinal = $alamatFinal . $rtRw . $kecamatan . $kota . $provinsi;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $totalPembayaran,
                'ongkir' => $ongkir, // Uang ekstra barang tersimpan di sini (hak kurir)
                'biaya_layanan' => $biaya_layanan,
                'status' => 'menunggu',
                'catatan' => $request->catatan,
                'alamat' => $alamatFinal,
                'nomor_hp' => $user->phone_number,
                'kurir' => 'Menunggu penugasan',
                'no_hp_kurir' => '-'
            ]);

            $itemDetailsMidtrans = [];
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga,
                ]);

                $item->product->decrement('stok', $item->jumlah);

                $itemDetailsMidtrans[] = [
                    'id' => 'PROD-' . $item->product_id,
                    'price' => (int) $item->product->harga,
                    'quantity' => (int) $item->jumlah,
                    'name' => substr($item->product->nama_produk, 0, 50),
                ];
            }

            if ($ongkir > 0) {
                $itemDetailsMidtrans[] = [
                    'id' => 'ONGKIR',
                    'price' => $ongkir,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim Kurir (Termasuk Vol)', // Diperjelas di struk Midtrans
                ];
            }

            if ($biaya_layanan > 0) {
                $itemDetailsMidtrans[] = [
                    'id' => 'LAYANAN',
                    'price' => $biaya_layanan,
                    'quantity' => 1,
                    'name' => 'Biaya Layanan Aplikasi',
                ];
            }

            $midtransParams = [
                'transaction_details' => [
                    'order_id' => 'EF-ORD-' . $order->id . '-' . time(),
                    'gross_amount' => (int) $totalPembayaran,
                ],
                'item_details' => $itemDetailsMidtrans,
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ]
            ];

            $snapToken = Snap::getSnapToken($midtransParams);
            $order->update(['snap_token' => $snapToken]);
            Cart::whereIn('id', $ids)->where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $parts = explode('-', $request->order_id);
        $orderId = $parts[2] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order data tidak ditemukan'], 404);
        }

        $transactionStatus = $request->transaction_status;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['status' => 'menunggu']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'batal']);
        }

        return response()->json(['message' => 'Callback diproses sukses!']);
    }
}
