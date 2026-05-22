<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi rahasia SDK Midtrans dari file .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true' || env('MIDTRANS_IS_PRODUCTION') === true;
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED') === 'true' || env('MIDTRANS_IS_SANITIZED') === true;
        Config::$is3ds = env('MIDTRANS_IS_3DS') === 'true' || env('MIDTRANS_IS_3DS') === true;
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
        $user = Auth::user();

        $alamatCustomer = trim(($user->address ?? '') . ', ' . ($user->district ?? '') . ', ' . ($user->city ?? '') . ', ' . ($user->province ?? ''));

        $latAsal = -6.921186;
        $lonAsal = 107.729420;

        $jarak = 0;
        $ongkir = 0;

        // HITUNG JARAK BERDASARKAN LATITUDE & LONGITUDE USER (Rumus Haversine)
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

            // TARIF: 1 KM = Rp 4.000
            $tarifPerKm = 2000;
            $ongkir = $jarak * $tarifPerKm;

            // Pembulatan ke atas agar tidak ada angka receh ke kelipatan 500
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
            'latKWT' => $latAsal,
            'lonKWT' => $lonAsal
        ]);
    }

    /**
     * Memproses pesanan ke database dan memicu pembuatan Token Snap Midtrans ( AJAX JSON )
     */
    public function process(Request $request)
    {
        $user = Auth::user();

        if (!$user->phone_number) {
            return response()->json(['success' => false, 'message' => 'Lengkapi nomor HP di profil Anda!'], 400);
        }

        // Ambil payload dari form AJAX
        $ids = explode(',', $request->item_ids);
        $cartItems = Cart::with('product')->whereIn('id', $ids)->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang belanja Anda kosong!'], 400);
        }

        // Hitung akumulasi belanjaan murni barang
        $subtotal = $cartItems->sum(fn($i) => $i->jumlah * $i->product->harga);
        $ongkir = (int) $request->input('ongkir', 0);
        $totalPembayaran = $subtotal + $ongkir;

        // 🌟 PERBAIKAN MUTAKHIR: LANGSUNG AMBIL DARI TABLE USER (Anti Kosong & Akurat) 🌟
        $alamatFinal = $user->address;

        // Satukan komponen wilayah pendukung dari table users jika kolomnya terisi
        $rtRw = (!empty($user->rt) && !empty($user->rw)) ? ' RT.' . $user->rt . ' / RW.' . $user->rw : '';
        $kecamatan = !empty($user->district) ? ', Kecamatan ' . $user->district : '';
        $kota = !empty($user->city) ? ', ' . $user->city : '';
        $provinsi = !empty($user->province) ? ', ' . $user->province : ', Jawa Barat';

        // Hasil alamat gabungan final untuk disimpan ke table orders
        $alamatFinal = $alamatFinal . $rtRw . $kecamatan . $kota . $provinsi;

        DB::beginTransaction();
        try {
            // 1. Simpan Transaksi Utama ke database induk 'orders'
            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $totalPembayaran,
                'ongkir' => $ongkir,
                'status' => 'menunggu', // default berstatus menunggu bayar
                'catatan' => $request->catatan,
                'alamat' => $alamatFinal,
                'nomor_hp' => $user->phone_number,
                'kurir' => 'Menunggu penugasan',
                'no_hp_kurir' => '-'
            ]);

            // 2. Simpan ke data order detail & kurangi stok & susun data struk Midtrans
            $itemDetailsMidtrans = [];
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga_saat_ini' => $item->product->harga,
                ]);

                // Kurangi stok barang
                $item->product->decrement('stok', $item->jumlah);

                // Format item untuk struk invoice Midtrans
                $itemDetailsMidtrans[] = [
                    'id' => 'PROD-' . $item->product_id,
                    'price' => (int) $item->product->harga,
                    'quantity' => (int) $item->jumlah,
                    'name' => substr($item->product->nama_produk, 0, 50),
                ];
            }

            // Masukkan komponen biaya kirim ke invoice Midtrans
            if ($ongkir > 0) {
                $itemDetailsMidtrans[] = [
                    'id' => 'ONGKIR',
                    'price' => $ongkir,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim Kurir KWT',
                ];
            }

            // 3. Susun Parameter Data Sesuai Regulasi API Midtrans Snap
            $midtransParams = [
                'transaction_details' => [
                    'order_id' => 'EF-ORD-' . $order->id . '-' . time(), // ID unik anti bentrok
                    'gross_amount' => (int) $totalPembayaran,
                ],
                'item_details' => $itemDetailsMidtrans,
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                ]
            ];

            // 4. Tembak ke server Midtrans untuk meminta Token Pembayaran Snap
            $snapToken = Snap::getSnapToken($midtransParams);

            // Simpan token yang berhasil digenerate ke kolom database
            $order->update(['snap_token' => $snapToken]);

            // 5. Bersihkan keranjang belanja item yang sudah sukses checkout
            Cart::whereIn('id', $ids)->where('user_id', $user->id)->delete();

            DB::commit();

            // Kembalikan respons sukses berupa JSON untuk dibaca oleh JavaScript di frontend
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

    /**
     * Webhook Callback otomatis dari server Midtrans untuk sinkronisasi status lunas (Lewat Ngrok)
     */
    public function callback(Request $request)
    {
        // Validasi keaslian signature key kiriman server Midtrans
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // Pecah string unik order ID 'EF-ORD-{id}-{timestamp}' untuk mendapat ID baris asli
        $parts = explode('-', $request->order_id);
        $orderId = $parts[2] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order data tidak ditemukan'], 404);
        }

        $transactionStatus = $request->transaction_status;

        // Proses sinkronisasi otomatis status order di DB lokal kamu
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->update(['status' => 'menunggu']); // Lunas! status diatur menunggu konfirmasi/packing pengurus KWT
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update(['status' => 'batal']);
        }

        return response()->json(['message' => 'Callback diproses sukses!']);
    }
}
