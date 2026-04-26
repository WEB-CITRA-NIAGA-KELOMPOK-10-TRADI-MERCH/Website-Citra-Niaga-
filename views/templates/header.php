<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

$current_page = strtolower(basename($_SERVER['PHP_SELF'])); 

$ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$visit_date = date('Y-m-d');

$cek_visitor = @mysqli_query($conn, "SELECT id FROM visitor_logs WHERE ip_address = '$ip_address' AND visit_date = '$visit_date' AND page_visited = '$current_page'");
if ($cek_visitor && mysqli_num_rows($cek_visitor) == 0) {
    @mysqli_query($conn, "INSERT INTO visitor_logs (ip_address, visit_date, page_visited) VALUES ('$ip_address', '$visit_date', '$current_page')");
}

require_once __DIR__ . '/../../models/SettingsModel.php';

$settingsModelGlobal = new SettingsModel($conn);
$global_setting = $settingsModelGlobal->getSettings();

$is_setting_valid = is_array($global_setting);

$site_title = ($is_setting_valid && !empty($global_setting['site_title'])) ? htmlspecialchars($global_setting['site_title']) : 'Citra Niaga Samarinda';
$site_desc = ($is_setting_valid && !empty($global_setting['site_desc'])) ? htmlspecialchars($global_setting['site_desc']) : 'Pusat UMKM, kuliner, dan budaya di Samarinda.';
$seo_keywords = ($is_setting_valid && !empty($global_setting['seo_keywords'])) ? htmlspecialchars($global_setting['seo_keywords']) : 'citra niaga, samarinda, budaya, umkm';

$theme_color = ($is_setting_valid && !empty($global_setting['theme_color'])) ? htmlspecialchars($global_setting['theme_color']) : '#254794';
$header_color = ($is_setting_valid && !empty($global_setting['header_color'])) ? htmlspecialchars($global_setting['header_color']) : '#ffffff';
$text_color = ($is_setting_valid && !empty($global_setting['text_color'])) ? htmlspecialchars($global_setting['text_color']) : '#333333';
$header_text_color = ($is_setting_valid && !empty($global_setting['header_text_color'])) ? htmlspecialchars($global_setting['header_text_color']) : '#333333';
$font_family = ($is_setting_valid && !empty($global_setting['font_family'])) ? htmlspecialchars($global_setting['font_family']) : 'Plus Jakarta Sans';

$font_url = str_replace(' ', '+', $font_family);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title><?= $site_title ?></title>
    <meta name="description" content="<?= $site_desc ?>">
    <meta name="keywords" content="<?= $seo_keywords ?>">
    
    <link rel="icon" type="image/png" href="../assets/img/Logo/logo_citra_niaga.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=<?= $font_url ?>:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        :root {
            --theme-color: <?= $theme_color ?>;
            --header-color: <?= $header_color ?>;
            --text-color: <?= $text_color ?>;
            --header-text-color: <?= $header_text_color ?>;
            --font-family: '<?= $font_family ?>', sans-serif;
        }
        
        * { font-family: var(--font-family) !important; }

        body, p, h1, h2, h3, h4, h5, h6, span, div, 
        .text-gray-500, .text-gray-600, .text-gray-700, .text-gray-800, .text-gray-900, 
        .text-slate-500, .text-slate-600, .text-slate-700, .text-slate-800, .text-slate-900 {
            color: var(--text-color) !important;
        }
        
        .text-white { color: #ffffff !important; }
        .text-red-500 { color: #ef4444 !important; }
        .text-green-500 { color: #22c55e !important; }
        .bg-blue-600 { background-color: var(--theme-color) !important; }
        .nav-link-item {
            display: inline-block;
            text-align: center;
        }
        .nav-link-item::before {
            content: attr(data-text);
            display: block;
            font-weight: 700;
            height: 0;
            overflow: hidden;
            visibility: hidden;
        }

        .nav-active {
            color: var(--theme-color) !important;
            font-weight: 700 !important; 
            position: relative;
        }
        .nav-active::after {
            content: ''; position: absolute; bottom: -0.375rem; left: 0; width: 100%; height: 3px; 
            background-color: var(--theme-color); border-radius: 9999px;
        }
        
        .nav-inactive {
            color: var(--header-text-color) !important; 
            opacity: 0.65; 
            font-weight: 500 !important;
            transition: all 0.3s; position: relative;
        }
        .nav-inactive:hover {
            color: var(--theme-color) !important;
            opacity: 1;
        }
        .nav-inactive::after {
            content: ''; position: absolute; bottom: -0.375rem; left: 50%; width: 0; height: 3px; 
            background-color: var(--theme-color); border-radius: 9999px; transition: all 0.3s;
            transform: translateX(-50%);
        }
        .nav-inactive:hover::after { width: 100%; }

        .mobile-active {
            display: block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 1rem; 
            font-weight: 700; color: var(--theme-color) !important; background-color: #eff6ff; text-decoration: none;
        }
        .mobile-inactive {
            display: block; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 1rem; 
            font-weight: 500; color: var(--header-text-color) !important; text-decoration: none; transition: all 0.3s;
        }
        .mobile-inactive:hover { color: var(--theme-color) !important; background-color: #f9fafb; }
    </style>
</head>
<body class="min-h-screen flex flex-col" style="background-color: #f8fafc;">

    <nav class="fixed w-full z-50 transition-all duration-300 shadow-sm border-b border-gray-100" style="background-color: <?= $header_color ?>;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <a href="index.php" class="flex items-center gap-3 no-underline group">
                    <img src="../assets/img/Logo/logo_citra_niaga.png" alt="Logo Citra Niaga" class="h-10 w-auto group-hover:opacity-80 transition-opacity drop-shadow-sm">
                    <span class="font-bold text-xl tracking-wide no-underline" style="color: <?= $theme_color ?> !important; font-family: 'Cinzel', var(--font-family) !important;">CITRA NIAGA</span>
                </a>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" data-text="Beranda" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'index.php') ? 'nav-active' : 'nav-inactive' ?>">Beranda</a>
                    <a href="profile.php" data-text="Sejarah & Profil" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'profile.php') ? 'nav-active' : 'nav-inactive' ?>">Sejarah & Profil</a>
                    <a href="gallery.php" data-text="Galeri" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'gallery.php') ? 'nav-active' : 'nav-inactive' ?>">Galeri</a>
                    <a href="kios.php" data-text="Kios" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'kios.php') ? 'nav-active' : 'nav-inactive' ?>">Kios</a>
                    <a href="reviews.php" data-text="Ulasan" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'reviews.php') ? 'nav-active' : 'nav-inactive' ?>">Ulasan</a>
                    <a href="events.php" data-text="Acara" class="nav-link-item px-2 py-1 text-sm no-underline <?= ($current_page == 'events.php') ? 'nav-active' : 'nav-inactive' ?>">Acara</a>
                    
                    <div class="pl-4 border-l border-gray-200">
                        <?php if (isset($_SESSION['role'])): ?>
                            <a href="../controllers/AuthController.php?logout=true" class="px-5 py-2.5 rounded-full text-sm font-bold text-red-500 bg-red-50 hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center gap-2 shadow-sm no-underline">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="px-5 py-2.5 rounded-full text-sm font-bold text-white transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg no-underline hover:-translate-y-0.5" style="background-color: <?= $theme_color ?>;">
                                <i data-lucide="log-in" class="w-4 h-4"></i> Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-500 focus:outline-none p-2" style="color: <?= $theme_color ?> !important;">
                        <i data-lucide="menu" class="w-7 h-7"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden shadow-lg absolute w-full left-0 border-t border-gray-100" style="background-color: <?= $header_color ?>;">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="index.php" class="<?= ($current_page == 'index.php') ? 'mobile-active' : 'mobile-inactive' ?>">Home</a>
                <a href="profile.php" class="<?= ($current_page == 'profile.php') ? 'mobile-active' : 'mobile-inactive' ?>">Profile & History</a>
                <a href="gallery.php" class="<?= ($current_page == 'gallery.php') ? 'mobile-active' : 'mobile-inactive' ?>">Gallery</a>
                <a href="kios.php" class="<?= ($current_page == 'kios.php') ? 'mobile-active' : 'mobile-inactive' ?>">Kios</a>
                <a href="reviews.php" class="<?= ($current_page == 'reviews.php') ? 'mobile-active' : 'mobile-inactive' ?>">Reviews</a>
                <a href="events.php" class="<?= ($current_page == 'events.php') ? 'mobile-active' : 'mobile-inactive' ?>">Events</a>
                
                <div class="border-t border-gray-200 mt-4 pt-4 pb-2">
                    <?php if (isset($_SESSION['role'])): ?>
                        <a href="../controllers/AuthController.php?logout=true" class="w-full flex justify-center items-center gap-2 px-5 py-3 rounded-md text-base font-bold text-red-600 bg-red-50 hover:bg-red-100 no-underline">
                            <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="w-full flex justify-center items-center gap-2 px-5 py-3 rounded-md text-base font-bold text-white no-underline shadow-sm" style="background-color: <?= $theme_color ?>;">
                            <i data-lucide="log-in" class="w-5 h-5"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            const icon = this.querySelector('i');
            
            mobileMenu.classList.toggle('hidden');
            
            if (mobileMenu.classList.contains('hidden')) {
                icon.setAttribute('data-lucide', 'menu');
            } else {
                icon.setAttribute('data-lucide', 'x');
            }
            lucide.createIcons();
        });
    </script>

    <div class="pt-20 flex-grow">