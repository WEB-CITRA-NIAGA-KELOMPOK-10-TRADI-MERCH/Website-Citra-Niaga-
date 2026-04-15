<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../models/SettingsModel.php';

$settingsModelGlobal = new SettingsModel($conn);
$global_setting = $settingsModelGlobal->getSettings();

$is_setting_valid = is_array($global_setting);

$site_title = ($is_setting_valid && !empty($global_setting['site_title'])) ? htmlspecialchars($global_setting['site_title']) : 'Citra Niaga Samarinda';
$site_desc = ($is_setting_valid && !empty($global_setting['site_desc'])) ? htmlspecialchars($global_setting['site_desc']) : 'Pusat UMKM, kuliner, dan budaya di Samarinda.';
$seo_keywords = ($is_setting_valid && !empty($global_setting['seo_keywords'])) ? htmlspecialchars($global_setting['seo_keywords']) : 'citra niaga, samarinda, budaya, umkm';
$theme_color = ($is_setting_valid && !empty($global_setting['theme_color'])) ? htmlspecialchars($global_setting['theme_color']) : '#254794';

$current_page = basename($_SERVER['PHP_SELF']); 

$nav_active = "text-[#254794] font-bold relative after:absolute after:-bottom-1.5 after:left-0 after:w-full after:h-[3px] after:bg-[#254794] after:rounded-full";
$nav_inactive = "text-gray-500 hover:text-[#254794] font-medium transition-colors relative after:absolute after:-bottom-1.5 after:left-0 after:w-0 after:h-[3px] after:bg-[#254794] after:rounded-full hover:after:w-full after:transition-all after:duration-300";

$mobile_active = "block px-3 py-2 rounded-md text-base font-bold text-[#254794] bg-blue-50 no-underline";
$mobile_inactive = "block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-[#254794] hover:bg-gray-50 no-underline transition-colors";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    
    <title><?= $site_title ?></title>
    <meta name="description" content="<?= $site_desc ?>">
    <meta name="keywords" content="<?= $seo_keywords ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="index.php" class="flex items-center gap-2 no-underline group">
                    <div class="p-2 rounded-lg group-hover:opacity-80 transition-opacity" style="background-color: <?= $theme_color ?>;">
                        <i data-lucide="map-pin" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="font-cinzel font-bold text-xl tracking-wide no-underline" style="color: <?= $theme_color ?>;">CITRA NIAGA</span>
                </a>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'index.php') ? $nav_active : $nav_inactive ?>">Home</a>
                    <a href="profile.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'profile.php') ? $nav_active : $nav_inactive ?>">Profile & History</a>
                    <a href="gallery.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'gallery.php') ? $nav_active : $nav_inactive ?>">Gallery</a>
                    <a href="kios.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'kios.php') ? $nav_active : $nav_inactive ?>">Kios</a>
                    <a href="reviews.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'reviews.php') ? $nav_active : $nav_inactive ?>">Reviews</a>
                    <a href="events.php" class="px-2 py-1 text-sm no-underline <?= ($current_page == 'events.php') ? $nav_active : $nav_inactive ?>">Events</a>
                    
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
                    <button id="mobile-menu-btn" class="text-gray-500 focus:outline-none p-2" style="hover:color: <?= $theme_color ?>;">
                        <i data-lucide="menu" class="w-7 h-7"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg absolute w-full left-0">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="index.php" class="<?= ($current_page == 'index.php') ? $mobile_active : $mobile_inactive ?>">Home</a>
                <a href="profile.php" class="<?= ($current_page == 'profile.php') ? $mobile_active : $mobile_inactive ?>">Profile & History</a>
                <a href="gallery.php" class="<?= ($current_page == 'gallery.php') ? $mobile_active : $mobile_inactive ?>">Gallery</a>
                <a href="kios.php" class="<?= ($current_page == 'kios.php') ? $mobile_active : $mobile_inactive ?>">Kios</a>
                <a href="reviews.php" class="<?= ($current_page == 'reviews.php') ? $mobile_active : $mobile_inactive ?>">Reviews</a>
                <a href="events.php" class="<?= ($current_page == 'events.php') ? $mobile_active : $mobile_inactive ?>">Events</a>
                
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