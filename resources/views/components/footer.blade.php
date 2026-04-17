<footer>
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">

                <div class="col-12 col-md-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="19" fill="#d6f0c2" stroke="#2d7a22" stroke-width="1.5"/>
                            <path d="M14 28c0-5 3-10 8-12" stroke="#2d7a22" stroke-width="2" stroke-linecap="round"/>
                            <path d="M22 16c2-3 5-4 7-3-1 3-4 5-7 3z" fill="#2d7a22"/>
                            <path d="M18 20c-2-3-5-3-7-2 1 3 4 5 7 2z" fill="#4caf50"/>
                            <circle cx="25" cy="13" r="2" fill="#2d7a22" opacity="0.6"/>
                        </svg>
                        <span class="footer-brand-name">E<span>Food</span></span>
                    </div>
                    <div class="footer-social mt-3">
                        <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <p class="footer-heading">Laporkan jika ada bug</p>
                    <div class="footer-subscribe-form">
                        <input type="email" class="footer-subscribe-input" placeholder="your email@gmail.com">
                        <button class="btn-subscribe">Subscribe</button>
                    </div>
                    <p class="footer-spam-note">
                        kami tidak akan melakukan spam, baca <a href="#">kebijakan email kami</a>
                    </p>
                    <p class="footer-tagline">Lebih Cepat, Lebih Mudah, Lebih Nikmat – Makanan Pilihan Kini Sampai di Rumahmu!</p>
                </div>

                <div class="col-6 col-md-2 offset-md-1">
                    <p class="footer-heading">Tentang Aplikasi</p>
                    <a href="#" class="footer-link">Tentang Kami</a>
                </div>

                <div class="col-6 col-md-2">
                    <p class="footer-heading">Aksi Cepat</p>
                    <a href="#" class="footer-link">Katalog Produk</a>
                    <a href="#" class="footer-link">Tentang Kami</a>
                    @guest
                        <a href="#" class="footer-link">Buat Akun?</a>
                        <a href="#" class="footer-link">Belanja Produk</a>
                    @endguest
                    @auth
                        <a href="#" class="footer-link">Keranjang</a>
                        <a href="#" class="footer-link">Riwayat Belanja</a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            © KWT | Kelompok Wanita Tani Desa Cibiru
        </div>
    </div>
</footer>