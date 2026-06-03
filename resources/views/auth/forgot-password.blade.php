<x-guest-layout>
    <style>
        .taniCibiru-input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .taniCibiru-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 18px;
            pointer-events: none;
            /* Biar ikonnya gak bisa diklik, tembus ke input */
        }

        .taniCibiru-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            /* 48px ini yang bikin jarak supaya teks gak nabrak ikon! */
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 15px;
            color: #374151;
            background-color: #f9fafb;
            transition: all 0.3s ease;
            outline: none !important;
            /* MATIKAN GARIS BIRU BAWAAN BROWSER! */
            box-sizing: border-box;
        }

        .taniCibiru-input:focus {
            border-color: #10b981 !important;
            /* Paksa jadi hijau */
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }

        .taniCibiru-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            background-color: #10b981;
            color: white;
            padding: 16px;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .taniCibiru-btn:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }

        .taniCibiru-link {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .taniCibiru-link:hover {
            color: #10b981;
        }
    </style>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">Lupa Password? 🔐</h2>
        <p style="font-size: 14px; color: #6b7280; line-height: 1.6;">
            Tenang saja! Masukkan email kamu, dan kami akan mengirimkan link untuk mengatur ulang password.
        </p>
    </div>

    @if (session('status'))
    <div style="margin-bottom: 24px; padding: 16px; border-radius: 12px; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 12px;">
        <i class="fa-solid fa-circle-check" style="font-size: 20px; color: #10b981;"></i>
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; margin-left: 4px;">Alamat Email</label>

            <div class="taniCibiru-input-group">
                <i class="fa-regular fa-envelope taniCibiru-icon"></i>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="taniCibiru-input"
                    placeholder="nama@email.com"
                    required
                    autofocus />
            </div>

            @error('email')
            <div style="margin-top: -10px; margin-bottom: 20px; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 12px 14px; border-radius: 12px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div style="margin-top: 10px;">
            <button type="submit" class="taniCibiru-btn">
                <i class="fa-regular fa-paper-plane"></i>
                Kirim Link Reset
            </button>

            <a href="{{ route('login') }}" class="taniCibiru-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>