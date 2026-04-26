<?php 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); exit;
}
require_once '../../config/koneksi.php';

/** @var mysqli $conn */

require_once '../../models/SettingsModel.php'; 

$settingsModel = new SettingsModel($conn);
$setting = $settingsModel->getSettings();

// PANGGIL WARNA KHUSUS ADMIN BIAR HALAMAN INI DINAMIS
$admin_theme = !empty($setting['admin_theme_color']) ? htmlspecialchars($setting['admin_theme_color']) : '#2563eb';
// INI UDAH GUE GANTI JADI ADMIN_SIDEBAR_COLOR
$sidebar_color = !empty($setting['admin_sidebar_color']) ? htmlspecialchars($setting['admin_sidebar_color']) : '#1e293b';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Web - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* SIHIR CSS ADMIN */
        :root {
            --admin-theme: <?= $admin_theme ?>;
            --sidebar-color: <?= $sidebar_color ?>;
            --admin-shadow: color-mix(in srgb, var(--admin-theme) 30%, transparent);
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        
        /* CLASS DINAMIS */
        .bg-theme { background-color: var(--admin-theme) !important; }
        .text-theme { color: var(--admin-theme) !important; }
        .border-theme { border-color: var(--admin-theme) !important; }
        .ring-theme:focus { --tw-ring-color: var(--admin-theme) !important; border-color: transparent !important; }
        .shadow-theme { box-shadow: 0 10px 15px -3px var(--admin-shadow) !important; }
        .bg-sidebar { background-color: var(--sidebar-color) !important; }

        /* ======================================================== */
        /* --- FIX RESPONSIVE KHUSUS LAYAR HP (MOBILE DEVICES) ---  */
        /* ======================================================== */
        @media (max-width: 768px) {
            .form-container { padding: 1.25rem !important; }
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden relative">
    
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-opacity"></div>

    <?php $admin_page = basename($_SERVER['PHP_SELF']); ?>
    <aside id="main-sidebar" class="w-64 bg-sidebar text-slate-300 flex flex-col h-full shrink-0 shadow-2xl z-40 fixed lg:static transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
        <div class="p-6 flex items-center justify-between border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-white">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white tracking-wide uppercase text-sm">CITRA NIAGA</h1>
                    <p class="text-[10px] text-slate-400">Admin Portal</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto hide-scrollbar">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'dashboard.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="gallery.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'gallery.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Galeri</span>
            </a>
            <a href="history.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'history.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Sejarah</span>
            </a>
            <a href="kios.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'kios.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="store" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Kios</span>
            </a>
            <a href="events.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'events.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Acara</span>
            </a>
            <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'reviews.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Ulasan</span>
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($admin_page == 'settings.php') ? 'bg-theme text-white shadow-theme' : 'hover:bg-white/5' ?>">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Pengaturan Web</span>
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-white/5">
            <a href="../../controllers/AuthController.php?logout=true" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-400/10 rounded-xl transition font-medium">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="text-sm">Logout</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen min-w-0 overflow-hidden bg-slate-50/50">
        <header class="bg-white border-b border-gray-200 px-4 lg:px-8 py-4 sticky top-0 z-10 flex justify-between items-center shadow-sm shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 uppercase tracking-wider">PENGATURAN WEB</h2>
                    <p class="text-[10px] text-gray-500 hidden sm:block">Konfigurasi SEO, Tampilan, dan Informasi Website</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-theme text-white flex items-center justify-center font-bold text-sm shadow-sm transition-colors">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                </div>
            </div>
        </header>
        
        <div class="flex-1 overflow-y-auto p-4 lg:p-8 w-full max-w-5xl mx-auto pb-20">
            
            <?php if(isset($_GET['success'])): ?>
            <div id="notification-alert" class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-2 border border-green-200 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i> Pengaturan berhasil disimpan!
            </div>
            <?php endif; ?>

            <form action="../../controllers/SettingsController.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 lg:p-8 form-container">
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 pb-4 border-b border-gray-100 gap-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="sliders" class="text-theme w-5 h-5"></i>
                        <h3 class="font-bold text-lg text-gray-800">Form Konfigurasi Utama</h3>
                    </div>
                    <button type="button" id="btnResetAll" class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5 font-bold border border-red-100 w-full sm:w-auto justify-center">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i> Reset Total
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-sm font-semibold text-gray-700 block">Judul Website (SEO Title)</label>
                            <button type="button" onclick="resetField('input_site_title', 'Citra Niaga Samarinda')" class="text-[10px] text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium bg-blue-50 px-2 py-0.5 rounded transition-colors"><i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default</button>
                        </div>
                        <input type="text" id="input_site_title" name="site_title" value="<?= htmlspecialchars($setting['site_title'] ?? '') ?>" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all">
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-sm font-semibold text-gray-700 block">Deskripsi Singkat (SEO Description)</label>
                            <button type="button" onclick="resetField('input_site_desc', 'Pusat UMKM, kuliner, dan budaya di Samarinda.')" class="text-[10px] text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium bg-blue-50 px-2 py-0.5 rounded transition-colors"><i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default</button>
                        </div>
                        <textarea id="input_site_desc" name="site_desc" rows="3" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all"><?= htmlspecialchars($setting['site_desc'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-sm font-semibold text-gray-700 block">Kata Kunci (SEO Keywords)</label>
                            <button type="button" onclick="resetField('input_seo_keywords', 'citra niaga, samarinda, budaya, umkm')" class="text-[10px] text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium bg-blue-50 px-2 py-0.5 rounded transition-colors"><i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default</button>
                        </div>
                        <input type="text" id="input_seo_keywords" name="seo_keywords" value="<?= htmlspecialchars($setting['seo_keywords'] ?? '') ?>" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all">
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-3 mb-6">
                            <i data-lucide="palette" class="text-theme w-5 h-5"></i>
                            <h3 class="font-bold text-lg text-gray-800">Pengaturan Tema & Tampilan</h3>
                        </div>
                        
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Warna Aksen & Sidebar</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_theme">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Aksen <span class="text-blue-600">Publik</span></label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="themeColorInput" name="theme_color" value="<?= htmlspecialchars($setting['theme_color'] ?? '#254794') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="themeColorText"><?= strtoupper(htmlspecialchars($setting['theme_color'] ?? '#254794')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('theme', '#254794')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_adminTheme">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Aksen <span class="text-red-600">Admin</span></label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="adminThemeColorInput" name="admin_theme_color" value="<?= htmlspecialchars($setting['admin_theme_color'] ?? '#2563eb') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="adminThemeColorText"><?= strtoupper(htmlspecialchars($setting['admin_theme_color'] ?? '#2563eb')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('adminTheme', '#2563eb')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all sm:col-span-2 md:col-span-1" id="color_container_adminSidebar">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Latar Sidebar <span class="text-red-600">Admin</span></label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="adminSidebarColorInput" name="admin_sidebar_color" value="<?= htmlspecialchars($setting['admin_sidebar_color'] ?? '#1e293b') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="adminSidebarColorText"><?= strtoupper(htmlspecialchars($setting['admin_sidebar_color'] ?? '#1e293b')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('adminSidebar', '#1e293b')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>

                        </div>

                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 mt-4">Warna Latar Header & Footer (Publik)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_header">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Latar Header</label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="headerColorInput" name="header_color" value="<?= htmlspecialchars($setting['header_color'] ?? '#ffffff') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="headerColorText"><?= strtoupper(htmlspecialchars($setting['header_color'] ?? '#ffffff')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('header', '#ffffff')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_footer">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Latar Footer</label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="footerColorInput" name="footer_color" value="<?= htmlspecialchars($setting['footer_color'] ?? '#1e293b') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="footerColorText"><?= strtoupper(htmlspecialchars($setting['footer_color'] ?? '#1e293b')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('footer', '#1e293b')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 mt-4">Warna Teks (Tulisan Publik)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_text">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Teks Konten</label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="textColorInput" name="text_color" value="<?= htmlspecialchars($setting['text_color'] ?? '#333333') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="textColorText"><?= strtoupper(htmlspecialchars($setting['text_color'] ?? '#333333')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('text', '#333333')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_headerText">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Teks Header</label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="headerTextColorInput" name="header_text_color" value="<?= htmlspecialchars($setting['header_text_color'] ?? '#333333') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="headerTextColorText"><?= strtoupper(htmlspecialchars($setting['header_text_color'] ?? '#333333')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('headerText', '#333333')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 transition-all" id="color_container_footerText">
                                <label class="text-xs font-semibold text-gray-700 block mb-2">Teks Footer</label>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="footerTextColorInput" name="footer_text_color" value="<?= htmlspecialchars($setting['footer_text_color'] ?? '#9ca3af') ?>" class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                                        <p class="font-bold text-gray-800 text-xs" id="footerTextColorText"><?= strtoupper(htmlspecialchars($setting['footer_text_color'] ?? '#9ca3af')) ?></p>
                                    </div>
                                    <button type="button" onclick="resetColor('footerText', '#9ca3af')" class="text-[10px] bg-white border border-gray-300 hover:bg-gray-200 text-gray-700 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-center gap-1 shadow-sm font-medium w-full mt-1">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 transition-all">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-semibold text-gray-700 block">Jenis Font (Tipografi)</label>
                                    <button type="button" onclick="resetField('input_font_family', 'Plus Jakarta Sans')" class="text-[10px] text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium bg-blue-50 px-2 py-1 rounded transition-colors"><i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default Font</button>
                                </div>
                                <select name="font_family" id="input_font_family" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 ring-theme outline-none transition-all cursor-pointer">
                                    <?php 
                                        $fonts = ['Plus Jakarta Sans', 'Inter', 'Poppins', 'Roboto', 'Lora', 'Playfair Display'];
                                        $saved_font = $setting['font_family'] ?? 'Plus Jakarta Sans';
                                        foreach($fonts as $f) {
                                            $selected = ($f === $saved_font) ? 'selected' : '';
                                            echo "<option value='$f' $selected>$f</option>";
                                        }
                                    ?>
                                </select>
                                <p class="text-[10px] text-gray-500 mt-2">Pilih font utama untuk website publik.</p>
                            </div>

                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 transition-all" id="banner_container">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-semibold text-gray-700 block">Ganti Banner Utama</label>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="setBannerDefault()" class="text-[10px] text-blue-500 hover:text-blue-700 flex items-center gap-1 font-medium bg-blue-50 px-2 py-1 rounded transition-colors"><i data-lucide="rotate-ccw" class="w-3 h-3"></i> Default</button>
                                        <button type="button" onclick="resetFile('input_hero_banner')" class="text-[10px] text-red-500 hover:text-red-700 flex items-center gap-1 font-medium bg-red-50 px-2 py-1 rounded transition-colors"><i data-lucide="x" class="w-3 h-3"></i> Batal Pilih</button>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="reset_banner" id="input_reset_banner" value="0">
                                <input type="file" id="input_hero_banner" name="hero_banner" accept="image/jpeg, image/png, image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold bg-white text-theme transition-all cursor-pointer border border-slate-200 mt-1">
                                <p id="banner_status_text" class="text-[10px] text-gray-500 mt-2 mb-2 transition-colors">Biarkan kosong jika tidak ingin mengubah banner saat ini. (Maks 2MB)</p>
                                
                                <?php if(!empty($setting['hero_banner'])): ?>
                                    <a href="../../assets/img/Gallery/Area_Bangunan/<?= htmlspecialchars($setting['hero_banner']) ?>" target="_blank" class="text-xs text-theme flex items-center gap-1 hover:underline mt-3 font-medium"><i data-lucide="image" class="w-3 h-3"></i> Lihat Banner Aktif</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <i data-lucide="contact" class="text-theme w-5 h-5"></i>
                            <h3 class="font-bold text-lg text-gray-800">Pengaturan Kontak</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-sm font-semibold text-gray-700 block"><i data-lucide="phone" class="w-4 h-4 inline pb-0.5 text-gray-400"></i> No. Telepon / WA</label>
                                    <button type="button" onclick="resetField('input_contact_phone', '')" class="text-[10px] text-gray-500 hover:text-gray-700 flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded transition-colors"><i data-lucide="trash-2" class="w-3 h-3"></i> Kosongkan</button>
                                </div>
                                <input type="text" id="input_contact_phone" name="contact_phone" value="<?= htmlspecialchars($setting['contact_phone'] ?? '') ?>" placeholder="Misal: +62 821-xxxx-xxxx" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all">
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-sm font-semibold text-gray-700 block"><i data-lucide="mail" class="w-4 h-4 inline pb-0.5 text-gray-400"></i> Alamat Email</label>
                                    <button type="button" onclick="resetField('input_contact_email', '')" class="text-[10px] text-gray-500 hover:text-gray-700 flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded transition-colors"><i data-lucide="trash-2" class="w-3 h-3"></i> Kosongkan</button>
                                </div>
                                <input type="email" id="input_contact_email" name="contact_email" value="<?= htmlspecialchars($setting['contact_email'] ?? '') ?>" placeholder="Misal: info@citraniaga.com" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all">
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-sm font-semibold text-gray-700 block"><i data-lucide="instagram" class="w-4 h-4 inline pb-0.5 text-gray-400"></i> Username Instagram</label>
                                    <button type="button" onclick="resetField('input_contact_ig', '')" class="text-[10px] text-gray-500 hover:text-gray-700 flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded transition-colors"><i data-lucide="trash-2" class="w-3 h-3"></i> Kosongkan</button>
                                </div>
                                <input type="text" id="input_contact_ig" name="contact_ig" value="<?= htmlspecialchars($setting['contact_ig'] ?? '') ?>" placeholder="Misal: @citraniaga.smd" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all">
                            </div>
                            
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-sm font-semibold text-gray-700 block"><i data-lucide="map-pin" class="w-4 h-4 inline pb-0.5 text-gray-400"></i> Alamat Lengkap Kawasan</label>
                                    <button type="button" onclick="resetField('input_contact_address', '')" class="text-[10px] text-gray-500 hover:text-gray-700 flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded transition-colors"><i data-lucide="trash-2" class="w-3 h-3"></i> Kosongkan</button>
                                </div>
                                <textarea id="input_contact_address" name="contact_address" rows="3" class="w-full p-3 bg-slate-50 border border-gray-200 rounded-xl focus:ring-2 ring-theme outline-none transition-all" placeholder="Jl. Niaga Selatan..."><?= htmlspecialchars($setting['contact_address'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end gap-3">
                        <button type="submit" name="simpan_pengaturan" class="w-full sm:w-auto bg-theme text-white px-8 py-3.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-colors shadow-theme hover:opacity-90">
                            <i data-lucide="save" class="w-5 h-5"></i> Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // JS BUKA TUTUP SIDEBAR HP
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        function resetField(inputId, defaultValue) {
            const el = document.getElementById(inputId);
            el.value = defaultValue;
            el.classList.add('ring-2', 'ring-green-400', 'bg-green-50');
            setTimeout(() => { el.classList.remove('ring-2', 'ring-green-400', 'bg-green-50'); }, 300);
        }

        function setBannerDefault() {
            document.getElementById('input_reset_banner').value = "1";
            document.getElementById('input_hero_banner').value = ""; 
            const st = document.getElementById('banner_status_text');
            st.textContent = "⚠️ Banner akan dikembalikan ke gambar bawaan setelah disimpan.";
            st.classList.remove('text-gray-500', 'text-green-500');
            st.classList.add('text-orange-500', 'font-bold');
            const c = document.getElementById('banner_container');
            c.classList.add('ring-2', 'ring-orange-400', 'bg-orange-50');
            setTimeout(() => { c.classList.remove('ring-2', 'ring-orange-400', 'bg-orange-50'); }, 300);
        }

        function resetFile(inputId) {
            document.getElementById(inputId).value = "";
            document.getElementById('input_reset_banner').value = "0"; 
            const st = document.getElementById('banner_status_text');
            st.textContent = "Biarkan kosong jika tidak ingin mengubah banner saat ini. (Maks 2MB)";
            st.classList.remove('text-orange-500', 'text-green-500', 'font-bold');
            st.classList.add('text-gray-500');
        }

        document.getElementById('input_hero_banner').addEventListener('change', function() {
            document.getElementById('input_reset_banner').value = "0"; 
            const st = document.getElementById('banner_status_text');
            if (this.value !== "") {
                st.textContent = "✅ File siap diupload. Jangan lupa klik Simpan Pengaturan.";
                st.classList.remove('text-gray-500', 'text-orange-500');
                st.classList.add('text-green-500', 'font-bold');
            } else { resetFile('input_hero_banner'); }
        });

        function setupColorPicker(type) {
            const input = document.getElementById(type + 'ColorInput');
            const text = document.getElementById(type + 'ColorText');
            if(input && text){
                input.addEventListener('input', () => { text.textContent = input.value.toUpperCase(); });
            }
        }
        
        setupColorPicker('theme'); 
        setupColorPicker('adminTheme'); 
        setupColorPicker('adminSidebar'); 
        setupColorPicker('header'); 
        setupColorPicker('footer');
        setupColorPicker('text'); 
        setupColorPicker('headerText'); 
        setupColorPicker('footerText');

        function resetColor(type, defaultHex) {
            const input = document.getElementById(type + 'ColorInput');
            const text = document.getElementById(type + 'ColorText');
            const container = document.getElementById('color_container_' + type);
            input.value = defaultHex;
            text.textContent = defaultHex.toUpperCase();
            container.classList.add('ring-2', 'ring-green-400', 'bg-green-50');
            setTimeout(() => { container.classList.remove('ring-2', 'ring-green-400', 'bg-green-50'); }, 300);
        }

        document.getElementById('btnResetAll').addEventListener('click', function() {
            if(confirm('Yakin ingin mereset TOTAL seluruh form ke pengaturan pabrik?')) {
                resetField('input_site_title', 'Citra Niaga Samarinda');
                resetField('input_site_desc', 'Pusat UMKM, kuliner, dan budaya di Samarinda.');
                resetField('input_seo_keywords', 'citra niaga, samarinda, budaya, umkm');
                
                resetColor('theme', '#254794');
                resetColor('adminTheme', '#2563eb');
                resetColor('adminSidebar', '#1e293b'); 
                resetColor('header', '#ffffff');
                resetColor('footer', '#1e293b');
                resetColor('text', '#333333');
                resetColor('headerText', '#333333');
                resetColor('footerText', '#9ca3af');

                resetField('input_font_family', 'Plus Jakarta Sans');
                
                resetField('input_contact_phone', '');
                resetField('input_contact_email', '');
                resetField('input_contact_ig', '');
                resetField('input_contact_address', '');
                
                setBannerDefault();
            }
        });

        const alertBox = document.getElementById('notification-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500); 
            }, 3000); 
        }
    </script>
</body>
</html>