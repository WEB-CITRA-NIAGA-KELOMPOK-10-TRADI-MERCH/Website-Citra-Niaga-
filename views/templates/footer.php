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

if (!isset($global_setting)) {
    require_once __DIR__ . '/../../config/koneksi.php';
    require_once __DIR__ . '/../../models/SettingsModel.php';
    $settingsModelFooter = new SettingsModel($conn);
    $global_setting = $settingsModelFooter->getSettings();
}

$is_setting_valid = is_array($global_setting);

$f_title = ($is_setting_valid && !empty($global_setting['site_title'])) ? htmlspecialchars($global_setting['site_title']) : 'CITRA NIAGA';
$f_desc = ($is_setting_valid && !empty($global_setting['site_desc'])) ? htmlspecialchars($global_setting['site_desc']) : 'Pusat UMKM, kuliner, dan budaya di Samarinda.';

$f_theme = ($is_setting_valid && !empty($global_setting['theme_color'])) ? htmlspecialchars($global_setting['theme_color']) : '#254794';
$f_footer_bg = ($is_setting_valid && !empty($global_setting['footer_color'])) ? htmlspecialchars($global_setting['footer_color']) : '#1e293b';

$f_text_color = ($is_setting_valid && !empty($global_setting['footer_text_color'])) ? htmlspecialchars($global_setting['footer_text_color']) : '#9ca3af';

$f_phone = ($is_setting_valid && !empty($global_setting['contact_phone'])) ? htmlspecialchars($global_setting['contact_phone']) : '+62 822-1949-7715';
$f_email = ($is_setting_valid && !empty($global_setting['contact_email'])) ? htmlspecialchars($global_setting['contact_email']) : 'info@citraniaga.com';
$f_ig = ($is_setting_valid && !empty($global_setting['contact_ig'])) ? htmlspecialchars($global_setting['contact_ig']) : '@citraniagasamarinda';
$f_address = ($is_setting_valid && !empty($global_setting['contact_address'])) ? nl2br(htmlspecialchars($global_setting['contact_address'])) : 'Jl. Niaga, Ps. Pagi, Samarinda Kota,<br>Kalimantan Timur 75111';

$ig_clean = str_replace('@', '', $f_ig);
$ig_link = "https://www.instagram.com/" . $ig_clean;

$wa_clean = preg_replace('/[^0-9]/', '', $f_phone);
if (substr($wa_clean, 0, 1) === '0') {
    $wa_clean = '62' . substr($wa_clean, 1);
}
$wa_link = "https://wa.me/" . $wa_clean;
?>

</div> 
<style>
    .f-heading { 
        color: <?= $f_text_color ?> !important; 
        font-weight: 700; 
    }
    .f-text { 
        color: <?= $f_text_color ?> !important; 
        opacity: 0.85; 
    }
    .f-link { 
        color: <?= $f_text_color ?> !important; 
        opacity: 0.85;
        transition: all 0.3s ease; 
        text-decoration: none; 
    }
    .f-link:hover { 
        color: <?= $f_theme ?> !important; 
        transform: translateX(4px); 
        opacity: 1;
    }
    .f-icon { 
        color: <?= $f_theme ?> !important; 
    }
    
    .f-bg-icon { 
        background-color: transparent !important; 
        border: 1px solid <?= $f_text_color ?> !important;
        color: <?= $f_text_color ?> !important; 
        transition: all 0.3s; 
        opacity: 0.85;
    }
    
    .hover-ig:hover { 
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%) !important;
        border-color: transparent !important;
        color: #ffffff !important; 
        opacity: 1;
    }
    .hover-wa:hover {
        background-color: #25D366 !important;
        border-color: #25D366 !important;
        color: #ffffff !important;
        opacity: 1;
    }
</style>

<footer class="pt-16 pb-8 mt-auto border-t-[4px]" style="background-color: <?= $f_footer_bg ?>; border-top-color: <?= $f_theme ?>;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <h2 class="text-2xl mb-4 tracking-wider uppercase f-heading" style="font-family: 'Cinzel', sans-serif !important;">
                    <?= $f_title ?>
                </h2>
                <p class="text-sm leading-relaxed mb-4 f-text">
                    <?= $f_desc ?>
                </p>
            </div>

            <div>
                <h3 class="text-lg mb-4 f-heading">Quick Links</h3>
                <ul class="space-y-3 m-0 p-0" style="list-style: none;">
                    <li><a href="index.php" class="inline-block text-sm f-link">Home</a></li>
                    <li><a href="kios.php" class="inline-block text-sm f-link">Kios & UMKM</a></li>
                    <li><a href="gallery.php" class="inline-block text-sm f-link">Gallery</a></li>
                    <li><a href="events.php" class="inline-block text-sm f-link">Events</a></li>
                    <li><a href="reviews.php" class="inline-block text-sm f-link">Reviews</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg mb-4 f-heading">Hubungi Kami</h3>
                <ul class="space-y-4 m-0 p-0" style="list-style: none;">
                    <li class="flex items-start gap-3 text-sm">
                        <i data-lucide="map-pin" class="w-5 h-5 shrink-0 mt-0.5 f-icon"></i>
                        <span class="f-text"><?= $f_address ?></span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i data-lucide="phone" class="w-5 h-5 shrink-0 f-icon"></i>
                        <span class="f-text"><?= $f_phone ?></span>
                    </li>
                    <li class="flex items-center gap-3 text-sm">
                        <i data-lucide="mail" class="w-5 h-5 shrink-0 f-icon"></i>
                        <span class="f-text"><?= $f_email ?></span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg mb-4 f-heading">Ikuti Kami</h3>
                <p class="text-sm mb-4 f-text">Dapatkan info dan promo terbaru melalui sosial media kami.</p>
                
                <div class="flex flex-col gap-4">
                    
                    <div class="flex gap-4 items-center">
                        <a href="<?= $ig_link ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full flex items-center justify-center hover-ig shadow-sm hover:shadow-md no-underline f-bg-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="<?= $ig_link ?>" target="_blank" rel="noopener noreferrer" class="text-sm font-medium f-link">
                            <?= $f_ig ?>
                        </a>
                    </div>

                    <div class="flex gap-4 items-center">
                        <a href="<?= $wa_link ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm hover:shadow-md no-underline f-bg-icon hover-wa transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                            </svg>
                        </a>
                        <a href="<?= $wa_link ?>" target="_blank" rel="noopener noreferrer" class="text-sm font-medium f-link">
                            WhatsApp Kami
                        </a>
                    </div>

                </div>
            </div>

        </div>

        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4" style="border-top: 1px solid <?= $f_text_color ?>40;">
            <p class="text-sm m-0 f-text">
                © <?= date('Y') ?> <?= $f_title ?>. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="f-link">Privacy Policy</a>
                <a href="#" class="f-link">Terms of Service</a>
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