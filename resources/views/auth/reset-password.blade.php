<x-guest-layout>
    <style>
        .efood-input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .efood-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 18px;
            pointer-events: none;
        }

        .efood-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 15px;
            color: #374151;
            background-color: #f9fafb;
            transition: all 0.3s ease;
            outline: none !important;
            box-sizing: border-box;
        }

        .efood-input:focus {
            border-color: #10b981 !important;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }

        .efood-btn {
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

        .efood-btn:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
        }
    </style>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">Buat Password Baru 🔑</h2>
        <p style="font-size: 14px; color: #6b7280; line-height: 1.6;">
            Silakan masukkan email dan password baru kamu di bawah ini untuk mereset keamanan akunmu.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; margin-left: 4px;">Alamat Email</label>

            <div class="efood-input-group">
                <i class="fa-regular fa-envelope efood-icon"></i>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    class="efood-input"
                    required
                    autofocus
                    autocomplete="username" />
            </div>

            @error('email')
            <div style="margin-top: -10px; margin-bottom: 20px; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 12px 14px; border-radius: 12px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div>
            <label for="password" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; margin-left: 4px;">Password Baru</label>

            <div class="efood-input-group">
                <i class="fa-solid fa-lock efood-icon"></i>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="efood-input"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password" />
            </div>

            @error('password')
            <div style="margin-top: -10px; margin-bottom: 20px; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 12px 14px; border-radius: 12px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px; margin-left: 4px;">Konfirmasi Password Baru</label>

            <div class="efood-input-group">
                <i class="fa-solid fa-circle-check efood-icon"></i>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="efood-input"
                    placeholder="Ulangi password baru"
                    required
                    autocomplete="new-password" />
            </div>

            @error('password_confirmation')
            <div style="margin-top: -10px; margin-bottom: 20px; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 12px 14px; border-radius: 12px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 16px;"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div style="margin-top: 10px;">
            <button type="submit" class="efood-btn">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Password Baru
            </button>
        </div>
    </form>
</x-guest-layout>