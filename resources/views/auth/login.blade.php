<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - EFood Premium</title>

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
        }

        /* --- Left Side: Form --- */
        .form-side {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header {
            margin-bottom: 35px;
        }

        .header h2 {
            font-size: 32px;
            color: var(--text-dark);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header p {
            color: var(--text-light);
            font-size: 15px;
            margin-top: 8px;
        }

        /* Input Styling */
        .input-group {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            margin-left: 4px;
        }

        .input-box {
            position: relative;
        }

        .input-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
        }

        .input-box input {
            width: 100%;
            padding: 14px 16px 14px 50px;
            border: 1.5px solid #e5e7eb;
            border-radius: 16px;
            outline: none;
            font-size: 15px;
            background: #f9fafb;
            transition: all 0.3s;
        }

        .input-box input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .forgot-pw {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 25px;
        }

        .forgot-pw a {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* Buttons */
        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #9ca3af;
            font-size: 12px;
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
            padding: 14px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* --- Right Side: Image --- */
        .image-side {
            flex: 1.2;
            background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.3)),
                url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=1200') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            color: white;
        }

        .image-text h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: var(--text-light);
        }

        .footer a {
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

        @media (max-width: 850px) {
            .image-side {
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
        <div class="form-side">
            <div class="header">
                <h2>Selamat Datang!</h2>
                <p>Masuk untuk melanjutkan belanja sehat Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label class="label">Alamat Email</label>
                    <div class="input-box">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label">Password</label>
                    <div class="input-box">
                        <i class="fa-solid fa-lock-open"></i>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="forgot-pw">
                    <a href="#">Lupa Password?</a>
                </div>

                <button type="submit" class="btn-login">Masuk Sekarang</button>

                <div class="divider">Atau masuk dengan</div>

                <a href="/auth/google" class="btn-google">
                    <img src="image/google.png" width="20" alt="Google">
                    Akun Google
                </a>
            </form>

            <div class="footer">
                Belum bergabung? <a href="/register">Buat akun baru</a>
            </div>
        </div>

        <div class="image-side">
            <div class="image-text">
                <h3>Segar & Organik</h3>
                <p>Membawa hasil tani terbaik langsung ke pintu rumah Anda dengan kasih sayang.</p>
            </div>
        </div>
    </div>

</body>

</html>