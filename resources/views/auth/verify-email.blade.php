<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - EFood</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #065f46;
            --bg: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 45px;
            border-radius: 28px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.08);
            animation: fade 0.5s ease;
        }

        .icon {
            width: 90px;
            height: 90px;
            background: #d1fae5;
            margin: auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 30px;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        p {
            color: var(--text-light);
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 14px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .logout {
            margin-top: 18px;
        }

        .logout button {
            border: none;
            background: none;
            color: #ef4444;
            font-weight: 600;
            cursor: pointer;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="icon">
            📧
        </div>

        <h1>Verifikasi Email</h1>

        <p>
            Kami sudah mengirim link verifikasi ke email Anda.
            Silakan cek inbox atau folder spam lalu klik link tersebut.
        </p>

        @if (session('status') == 'verification-link-sent')
            <p style="color: green; margin-bottom: 20px;">
                Link verifikasi baru berhasil dikirim.
            </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    Logout
                </button>
            </form>
        </div>

    </div>

</body>

</html>