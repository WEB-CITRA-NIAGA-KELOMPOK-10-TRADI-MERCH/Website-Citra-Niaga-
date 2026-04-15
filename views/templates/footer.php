<?php
if (isset($_GET['notif'])) {
    if ($_GET['notif'] == 'login_sukses') {
        echo "<script>alert('Login berhasil! Selamat datang di Citra Niaga.');</script>";
    } elseif ($_GET['notif'] == 'register_sukses') {
        echo "<script>alert('Registrasi berhasil! Anda langsung login secara otomatis.');</script>";
    } elseif ($_GET['notif'] == 'logout_sukses') {
        echo "<script>alert('Anda telah berhasil logout. Sampai jumpa kembali!');</script>";
    } elseif ($_GET['notif'] == 'sudah_review') {
        echo "<script>alert('Maaf, Anda hanya bisa memberikan ulasan sebanyak 1x.');</script>";
    } elseif ($_GET['notif'] == 'review_sukses') {
        echo "<script>alert('Terima kasih! Ulasan Anda berhasil dikirim.');</script>";
    }
}
?>

</div> 
<footer class="bg-[#111827] text-white pt-16 pb-8 mt-auto border-t-[4px] border-[#254794]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <h2 class="font-cinzel text-2xl font-bold mb-4 tracking-wider text-brand-blue" style="color: #60a5fa;">CITRA NIAGA</h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Pusat UMKM, kuliner, dan budaya di Samarinda. Temukan pengalaman lokal terbaik dan rasakan detak jantung kebudayaan Kalimantan Timur di satu tempat.
                </p>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100">Quick Links</h3>
                <ul class="space-y-3 m-0 p-0" style="list-style: none;">
                    <li><a href="index.php" class="text-gray-400 hover:text-[#60a5fa] hover:translate-x-1 inline-block transition-all text-sm no-underline">Home</a></li>
                    <li><a href="kios.php" class="text-gray-400 hover:text-[#60a5fa] hover:translate-x-1 inline-block transition-all text-sm no-underline">Kios & UMKM</a></li>
                    <li><a href="gallery.php" class="text-gray-400 hover:text-[#60a5fa] hover:translate-x-1 inline-block transition-all text-sm no-underline">Gallery</a></li>
                    <li><a href="events.php" class="text-gray-400 hover:text-[#60a5fa] hover:translate-x-1 inline-block transition-all text-sm no-underline">Events</a></li>
                    <li><a href="reviews.php" class="text-gray-400 hover:text-[#60a5fa] hover:translate-x-1 inline-block transition-all text-sm no-underline">Reviews</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100">Hubungi Kami</h3>
                <ul class="space-y-4 m-0 p-0" style="list-style: none;">
                    <li class="flex items-start gap-3 text-gray-400 text-sm">
                        <i data-lucide="map-pin" class="w-5 h-5 shrink-0 mt-0.5" style="color: #60a5fa;"></i>
                        <span>Jl. Niaga, Ps. Pagi, Samarinda Kota,<br>Kalimantan Timur 75111</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm">
                        <i data-lucide="phone" class="w-5 h-5 shrink-0" style="color: #60a5fa;"></i>
                        <span>+62 821-xxxx-xxxx</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm">
                        <i data-lucide="mail" class="w-5 h-5 shrink-0" style="color: #60a5fa;"></i>
                        <span>info@citraniaga.com</span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-100">Ikuti Kami</h3>
                <p class="text-gray-400 text-sm mb-4">Dapatkan info dan promo terbaru melalui sosial media kami.</p>
                <div class="flex gap-4">
                    <a href="https://www.instagram.com/citraniagasamarinda?igsh=MXIxc2JuMmN5bHZvZw==" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-pink-500 hover:to-purple-500 hover:text-white transition-all shadow-sm hover:shadow-md no-underline">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm m-0">
                © <?= date('Y') ?> Citra Niaga Samarinda. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-gray-500 hover:text-gray-300 transition-colors no-underline">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-gray-300 transition-colors no-underline">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
</body>
</html>