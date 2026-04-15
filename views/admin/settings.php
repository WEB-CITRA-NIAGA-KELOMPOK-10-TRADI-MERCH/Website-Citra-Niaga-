<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 

require_once '../../models/SettingsModel.php';

$settingsModel = new SettingsModel($conn);
$setting = $settingsModel->getSettings();

if (!$setting) {
    $setting = [
        'site_title' => 'Citra Niaga Samarinda',
        'site_desc' => 'Pusat UMKM, kuliner, dan budaya di Samarinda.',
        'theme_color' => '#254794',
        'seo_keywords' => 'citra niaga, samarinda, budaya, umkm',
        'hero_banner' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Web - Admin Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        #notification-alert { transition: opacity 0.5s ease, transform 0.5s ease; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-800 h-screen overflow-hidden flex">

    <aside class="w-64 bg-[#1e293b] text-slate-300 flex flex-col h-full shrink-0 shadow-xl z-20">
        <div class="p-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-white">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="font-bold text-white tracking-wide uppercase">CITRA NIAGA</h1>
                <p class="text-[10px] text-slate-400">Admin Portal</p>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="gallery.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
                <i data-lucide="image" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Galeri</span>
            </a>
            <a href="history.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Sejarah</span>
            </a>
            <a href="kios.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
                <i data-lucide="store" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Kios</span>
            </a>
            <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Kelola Review</span>
            </a>
            
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white transition shadow-lg shadow-blue-600/20">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Pengaturan Web</span>
            </a>
        </nav>

        <div class="p-4 mt-auto border-t border-slate-700/50">
            <a href="../../controllers/AuthController.php?logout=true" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-400/10 rounded-xl transition font-medium">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span class="text-sm">Logout</span>
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-white">
        
        <header class="bg-white px-8 py-4 flex justify-between items-center border-b border-slate-100 z-10 shrink-0">
            <div>
                <h1 class="font-bold text-xl text-slate-800 tracking-wider uppercase">Pengaturan Web</h1>
                <p class="text-xs text-slate-400">Konfigurasi SEO, Tampilan, dan Informasi Website</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="hidden md:block">
                    <p class="text-xs font-bold text-blue-600"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                    <p class="text-[10px] text-slate-400 uppercase">Administrator</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 hide-scrollbar bg-slate-50/50">
            
            <div class="max-w-4xl mx-auto">
                
                <?php if(isset($_GET['success'])): ?>
                <div id="notification-alert" class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm transform translate-y-0 opacity-100">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Pengaturan website berhasil diperbarui!</span>
                </div>
                <?php endif; ?>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Form Konfigurasi</h2>
                    </div>

                    <form action="../../controllers/SettingsController.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 mb-2 block">Judul Website (SEO Title)</label>
                                <input type="text" name="site_title" value="<?= htmlspecialchars($setting['site_title'] ?? '') ?>" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50 focus:bg-white" required>
                                <p class="text-[11px] text-slate-400 mt-1">Judul ini akan muncul di tab browser dan hasil pencarian Google.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 mb-2 block">Deskripsi Singkat (SEO Description)</label>
                                <textarea name="site_desc" rows="3" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50 focus:bg-white" required><?= htmlspecialchars($setting['site_desc'] ?? '') ?></textarea>
                                <p class="text-[11px] text-slate-400 mt-1">Deskripsi ini digunakan untuk SEO dan teks di bagian Hero Banner.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 mb-2 block">Kata Kunci (SEO Keywords)</label>
                                <input type="text" name="seo_keywords" value="<?= htmlspecialchars($setting['seo_keywords'] ?? '') ?>" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50 focus:bg-white">
                                <p class="text-[11px] text-slate-400 mt-1">Pisahkan dengan koma. Contoh: citra niaga, umkm samarinda, budaya kaltim.</p>
                            </div>

                            <div class="p-5 bg-blue-50/50 border border-blue-100 rounded-xl">
                                <label class="text-sm font-bold text-slate-700 mb-2 block">Warna Tema Utama</label>
                                <div class="flex items-center gap-4">
                                    <div class="relative w-14 h-14 rounded-lg overflow-hidden border-2 border-white shadow-md shrink-0">
                                        <input type="color" name="theme_color" value="<?= htmlspecialchars($setting['theme_color'] ?? '#254794') ?>" class="absolute -top-2 -left-2 w-20 h-20 cursor-pointer">
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800 uppercase tracking-wider"><?= htmlspecialchars($setting['theme_color'] ?? '#254794') ?></p>
                                        <p class="text-[11px] text-slate-500">Klik kotak warna untuk mengubah. Warna ini akan diterapkan pada tombol dan aksen web.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                                <label class="text-sm font-bold text-slate-700 mb-2 block">Ganti Banner Utama (Hero Image)</label>
                                <input type="file" name="hero_banner" accept="image/*" class="w-full p-2 border border-slate-200 rounded-lg text-sm bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-[11px] text-slate-500 mt-2">Biarkan kosong jika tidak ingin mengubah banner saat ini.</p>
                                <div class="mt-3 flex items-center gap-2 text-xs font-medium text-blue-600 bg-blue-50 w-fit px-3 py-1.5 rounded-md border border-blue-100">
                                    <i data-lucide="image" class="w-3.5 h-3.5"></i> Banner aktif: <?= htmlspecialchars($setting['hero_banner'] ?? 'Belum ada banner') ?>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" name="update_settings" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();

        const alertBox = document.getElementById('notification-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.opacity = '0';
                alertBox.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alertBox.remove();
                }, 500);
            }, 3000); 
        }
    </script>
</body>
</html>