<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        // Ambil alamat dasar dari profile jika ada
        $alamatCustomer = trim(($user->address ?? '') . ', ' . ($user->district ?? '') . ', ' . ($user->city ?? '') . ', ' . ($user->province ?? ''));

        // 🟢 FIX PERMANEN: Koordinat Akurat Toko KWT (Cileunyi, Bandung)
        $latKwt = -6.921186; 
        $lonKwt = 107.729420;

        // Set nilai default awal agar peta tidak memproses koordinat sampah/salah dari background
        $jarak = 0;
        $ongkir = 0;

        // 👑 DAFTAR RESMI KECAMATAN DI BANDUNG RAYA (Tanpa Sumedang)
        $dataWilayah = [
            'Kota Bandung' => [
                'Andir', 'Astana Anyar', 'Antapani', 'Arcamanik', 'Babakan Ciparay', 
                'Bandung Kidul', 'Bandung Kulon', 'Bandung Wetan', 'Batununggal', 'Bojongloa Kaler', 
                'Bojongloa Kidul', 'Buahbatu', 'Cibeunying Kaler', 'Cibeunying Kidul', 'Cibiru', 
                'Cicendo', 'Cidadap', 'Cinambo', 'Coblong', 'Gedebage', 
                'Kiaracondong', 'Lengkong', 'Mandalajati', 'Panyileukan', 'Rancasari', 
                'Regol', 'Sukajadi', 'Sukasari', 'Sumur Bandung', 'Ujungberung'
            ],
            'Kabupaten Bandung' => [
                'Arjasari', 'Baleendah', 'Banjaran', 'Bojongsoang', 'Cangkuang', 
                'Cicalengka', 'Cimenyan', 'Cileunyi', 'Cilengkrang', 'Cimaung', 
                'Ciparay', 'Ciwidey', 'Dayeuhkolot', 'Ibun', 'Katapang', 
                'Kertasari', 'Kutawaringin', 'Majalaya', 'Margaasih', 'Margahayu', 
                'Nagreg', 'Pacet', 'Pameungpeuk', 'Pangalengan', 'Paseh', 
                'Pasirjambu', 'Rancaekek', 'Solokanjeruk', 'Soreang', 'Rancabali', 'Tegalluar'
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
            'latKWT' => $latKwt,
            'lonKWT' => $lonKwt
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

            // Validasi ketat penangkapan data kiriman dari form HTML
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
            
            // Penggabungan alamat final terstruktur untuk disimpan ke database
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