<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EFood - Produk Organik Segar')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --green-primary: #2d7a22;
            --green-light: #d6f0c2;
            --green-hero: #4caf50;
            --green-btn: #3a8c2e;
            --yellow-bg: #e8f5a3;
            --text-dark: #1a1a1a;
            --text-muted: #6b7280;
            --white: #ffffff;
            --footer-bg: #c8e89b;
            --footer-dark: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            background: var(--white);
        }

        h1, h2, h3, h4 {
            font-family: 'Lora', serif;
        }

        .navbar-efood {
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        .navbar-brand-efood {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
        }

        .brand-name {
            font-family: 'Lora', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }

        .brand-name span {
            color: var(--green-primary);
        }

        .nav-link-efood {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--text-dark) !important;
            padding: 6px 14px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .nav-link-efood:hover,
        .nav-link-efood.active {
            color: var(--green-primary) !important;
            background: rgba(45, 122, 34, 0.07);
        }

        .btn-cart {
            background: transparent;
            border: none;
            position: relative;
            padding: 6px 10px;
            color: var(--text-dark);
            font-size: 1.15rem;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .btn-cart:hover {
            background: rgba(45, 122, 34, 0.08);
            color: var(--green-primary);
        }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--green-primary);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-daftar {
            background: var(--text-dark);
            color: var(--white) !important;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 8px 20px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-daftar:hover {
            background: var(--green-primary);
            transform: translateY(-1px);
        }

        /* ========== FOOTER ========== */
        .footer-top {
            background: var(--footer-bg);
            padding: 56px 0 40px;
        }

        .footer-brand-name {
            font-family: 'Lora', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 8px;
        }

        .footer-brand-name span {
            color: var(--green-primary);
        }

        .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(0,0,0,0.08);
            color: var(--text-dark);
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.2s;
            margin-right: 8px;
        }

        .footer-social a:hover {
            background: var(--green-primary);
            color: white;
        }

        .footer-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-link {
            display: block;
            font-size: 0.75rem;
            color: #444;
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--green-primary);
        }

        .footer-subscribe-form {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
        }

        .footer-subscribe-input {
            flex: 1;
            padding: 9px 14px;
            border: 1px solid rgba(0,0,0,0.15);
            border-radius: 8px;
            font-size: 0.85rem;
            background: white;
            outline: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-subscribe-input:focus {
            border-color: var(--green-primary);
        }

        .btn-subscribe {
            background: var(--green-primary);
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.2s;
        }

        .btn-subscribe:hover {
            background: var(--green-btn);
        }

        .footer-spam-note {
            font-size: 0.75rem;
            color: #666;
        }

        .footer-spam-note a {
            color: var(--green-primary);
            text-decoration: underline;
        }

        .footer-tagline {
            font-size: 0.8rem;
            color: #555;
            margin-top: 10px;
        }

        .footer-bottom {
            background: var(--footer-dark);
            color: #aaa;
            padding: 14px 0;
            font-size: 0.8rem;
            text-align: center;
        }

        main {
            min-height: 60vh;
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('components.navbar')
    
    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
