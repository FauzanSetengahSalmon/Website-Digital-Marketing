<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tani Cibiru - Premium Quality</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #065f46;
            --accent: #f59e0b;
            --bg-gradient: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            border-radius: 30px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            animation: slideUp 0.6s ease-out;
        }

        .visual-side {
            flex: 1.2;
            background:
                linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.4)),
                url('https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&q=80&w=1200') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            color: white;
        }

        .badge {
            display: inline-block;
            background: var(--accent);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
            width: fit-content;
        }

        .visual-side h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .form-side {
            flex: 1;
            background: white;
            padding: 45px 50px;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 25px;
        }

        .form-header h2 {
            font-size: 30px;
            color: var(--text-dark);
        }

        .form-header p {
            margin-top: 5px;
            color: var(--text-light);
            font-size: 14px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .input-wrapper {
            margin-bottom: 16px;
        }

        .input-label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .input-field {
            position: relative;
        }

        .input-field i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }

        .input-field input,
        .input-field select {
            width: 100%;
            padding: 13px 16px 13px 48px;
            border-radius: 14px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            outline: none;
            transition: 0.3s;
            font-size: 14px;
        }

        .input-field input:focus,
        .input-field select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .error-text {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
            margin-left: 3px;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }

        .btn-main {
            width: 100%;
            border: none;
            background: var(--primary);
            color: white;
            padding: 14px;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-main:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-main:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 22px 0;
            color: #9ca3af;
            font-size: 11px;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider::before {
            margin-right: 10px;
        }

        .divider::after {
            margin-left: 10px;
        }

        .btn-google {
            width: 100%;
            background: white;
            border: 1.5px solid #e5e7eb;
            padding: 13px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .google-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 10px;
            text-align: center;
            line-height: 1.5;
        }

        .footer-text {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media(max-width:900px) {

            .visual-side {
                display: none;
            }

            .container {
                max-width: 500px;
            }

            .form-side {
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="visual-side">
            <span class="badge">Fresh & Healthy</span>
            <h1>Rasakan Manfaat <br>Hasil Alam.</h1>
            <p>Bergabunglah dengan komunitas pangan sehat kami sekarang juga.</p>
        </div>

        <div class="form-side">

            <div class="form-header">
                <h2>Daftar Akun</h2>
                <p>Mulai perjalanan sehat Anda bersama Tani Cibiru.</p>
            </div>

            @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="input-wrapper">
                    <label class="input-label">Nama Lengkap</label>

                    <div class="input-field">
                        <i class="fa-solid fa-signature"></i>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="John Doe"
                            autocomplete="name"
                            autofocus
                            required>
                    </div>

                    @error('name')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Alamat Email</label>

                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required>
                    </div>

                    @error('email')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Password</label>

                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Min. 8 Karakter"
                            autocomplete="new-password"
                            required>

                        <span class="password-toggle" onclick="togglePassword('password', this)">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>

                    @error('password')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Konfirmasi Password</label>

                    <div class="input-field">
                        <i class="fa-solid fa-solid fa-lock"></i>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="confirmPassword"
                            placeholder="Ulangi Password"
                            autocomplete="new-password"
                            required>

                        <span class="password-toggle" onclick="togglePassword('confirmPassword', this)">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="input-label">No. Telepon</label>

                    <div class="input-field">
                        <i class="fa-solid fa-phone"></i>

                        <input
                            type="text"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            placeholder="0812xxxxxxx"
                            required>
                    </div>

                    @error('phone_number')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-main" id="submitBtn">
                    Buat Akun
                </button>

                <div class="divider">
                    atau daftar dengan
                </div>

                <a href="{{ route('google.login') }}" class="btn-google">
                    <img src="{{ asset('image/google.png') }}" width="18">
                    Lanjutkan dengan Google
                </a>

                <div class="google-note">
                    Jika login menggunakan Google, data alamat dan nomor telepon
                    dapat dilengkapi setelah berhasil masuk.
                </div>
            </form>

            <div class="footer-text">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk</a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword(id, el) {

            const input = document.getElementById(id);

            if (input.type === 'password') {
                input.type = 'text';
                el.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                el.innerHTML = '<i class="fa-solid fa-eye"></i>';
            }
        }

        document.getElementById('registerForm')
            .addEventListener('submit', function() {

                const btn = document.getElementById('submitBtn');

                btn.disabled = true;

                btn.innerHTML = 'Memproses...';
            });
    </script>

</body>

</html>