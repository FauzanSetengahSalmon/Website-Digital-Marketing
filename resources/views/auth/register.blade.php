<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar EFood - Premium Quality</title>

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

        /* ANIMASI IDENTIK DENGAN LOGIN */
        .container {
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            border-radius: 30px;
            display: flex;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.6s ease-out;
            /* Animasi hanya di sini */
        }

        /* --- Left Side: Visual (Gambar Tetap di Kiri) --- */
        .visual-side {
            flex: 1.2;
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.4)),
                url('https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&q=80&w=1200') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            color: var(--white);
        }

        .badge {
            display: inline-block;
            background: var(--accent);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            align-self: flex-start;
        }

        .visual-side h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        /* --- Right Side: Form (Dibuat Rapat) --- */
        .form-side {
            flex: 1;
            padding: 50px 60px;
            background: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 25px;
        }

        .form-header h2 {
            font-size: 28px;
            color: var(--text-dark);
            font-weight: 700;
        }

        .form-header p {
            color: var(--text-light);
            font-size: 14px;
            margin-top: 5px;
        }

        .input-wrapper {
            margin-bottom: 15px;
            /* Lebih rapat */
        }

        .input-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            margin-left: 4px;
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
            font-size: 16px;
        }

        .input-field input,
        .input-field select {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            outline: none;
            font-size: 14px;
            background: #f9fafb;
            transition: 0.3s;
        }

        .input-field input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-main {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-main:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #9ca3af;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f3f4f6;
        }

        .divider:not(:empty)::before {
            margin-right: 1.5em;
        }

        .divider:not(:empty)::after {
            margin-left: 1.5em;
        }

        .btn-google {
            width: 100%;
            background: white;
            border: 1.5px solid #e5e7eb;
            padding: 12px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
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
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .visual-side {
                display: none;
            }

            .container {
                max-width: 450px;
            }

            .form-side {
                padding: 40px 30px;
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
                @if ($errors->any())
                <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <p>Mulai perjalanan sehat Anda bersama EFood.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-wrapper">
                    <label class="input-label">Nama Lengkap</label>
                    <div class="input-field">
                        <i class="fa-solid fa-signature"></i>
                        <input type="text" name="name" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Alamat Email</label>
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Password</label>
                    <div class="input-field">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" placeholder="Min. 8 Karakter" required>
                    </div>
                </div>

                <div class="input-wrapper">
                    <div class="input-wrapper">
                        <label class="input-label">No. Telepon</label>
                        <div class="input-field">
                            <i class="fa-solid fa-phone"></i>
                            <input type="text" name="phone_number" placeholder="0812xxxxxxx">
                        </div>
                    </div>
                    <input type="hidden" name="role" value="customer">
                </div>

                <button type="submit" class="btn-main">Buat Akun</button>

                <div class="divider">Atau daftar dengan</div>

                <a href="/auth/google" class="btn-google">
                    <img src="image/google.png" width="18" alt="Google">
                    Akun Google
                </a>
            </form>

            <div class="footer-text">
                Sudah punya akun? <a href="/login">Masuk</a>
            </div>
        </div>
    </div>

</body>

</html>