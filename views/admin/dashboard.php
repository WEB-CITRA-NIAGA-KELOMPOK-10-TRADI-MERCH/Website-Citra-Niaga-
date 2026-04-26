<?php 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 

// === PANGGIL PENGATURAN CMS BIAR DASHBOARD DINAMIS ===
require_once '../../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

// PENTING: Panggil admin_theme_color & admin_sidebar_color biar pisah total dari warna publik!
$theme_color = !empty($web_setting['admin_theme_color']) ? htmlspecialchars($web_setting['admin_theme_color']) : '#2563eb';
$sidebar_color = !empty($web_setting['admin_sidebar_color']) ? htmlspecialchars($web_setting['admin_sidebar_color']) : '#1e293b';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';
// ======================================================

$total_kios = 0;
$query_kios = @mysqli_query($conn, "SELECT COUNT(*) as total FROM kios");
if($query_kios && $row = mysqli_fetch_assoc($query_kios)) { $total_kios = $row['total']; }

$total_galeri = 0;
$query_galeri = @mysqli_query($conn, "SELECT COUNT(*) as total FROM gallery");
if($query_galeri && $row = mysqli_fetch_assoc($query_galeri)) { $total_galeri = $row['total']; }

$total_review = 0;
$rata_review = "0.0";
$query_review = @mysqli_query($conn, "SELECT COUNT(id) as total, AVG(rating) as avg_rating FROM reviews");
if($query_review && $row = mysqli_fetch_assoc($query_review)) {
    $total_review = $row['total'];
    $rata_review = $row['avg_rating'] ? number_format($row['avg_rating'], 1) : "0.0";
}

$total_sejarah = 0;
$query_sejarah = @mysqli_query($conn, "SELECT COUNT(*) as total FROM history");
if($query_sejarah && $row = mysqli_fetch_assoc($query_sejarah)) { $total_sejarah = $row['total']; }

$chart_labels = [];
$visitor_data = [];
$review_data = [];
$total_visitor_7_hari = 0;

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $display_date = date('d M', strtotime("-$i days"));
    $chart_labels[] = $display_date;

    $q_visitor = @mysqli_query($conn, "SELECT COUNT(*) as cnt FROM visitor_logs WHERE visit_date = '$date'");
    $v_count = ($q_visitor && $r = mysqli_fetch_assoc($q_visitor)) ? (int)$r['cnt'] : 0;
    $visitor_data[] = $v_count;
    $total_visitor_7_hari += $v_count;

    $q_review = @mysqli_query($conn, "SELECT COUNT(*) as cnt FROM reviews WHERE DATE(visit_date) = '$date'");
    $r_count = ($q_review && $r = mysqli_fetch_assoc($q_review)) ? (int)$r['cnt'] : 0;
    $review_data[] = $r_count;
}

$query_traffic_pages = @mysqli_query($conn, "SELECT page_visited, COUNT(id) as total_views FROM visitor_logs GROUP BY page_visited ORDER BY total_views DESC LIMIT 5");
$top_pages_data = [];
if ($query_traffic_pages) {
    while($row = mysqli_fetch_assoc($query_traffic_pages)) {
        $top_pages_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lora:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* SIHIR CSS DINAMIS UNTUK DASHBOARD ADMIN */
        :root {
            --theme-color: <?= $theme_color ?>;
            --sidebar-color: <?= $sidebar_color ?>;
            --font-custom: '<?= $font_family ?>', sans-serif;
            --theme-shadow: color-mix(in srgb, var(--theme-color) 30%, transparent);
            --theme-light: color-mix(in srgb, var(--theme-color) 10%, white);
            --theme-border-light: color-mix(in srgb, var(--theme-color) 20%, white);
        }
        
        body { font-family: var(--font-custom) !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }

        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .bg-sidebar { background-color: var(--sidebar-color) !important; }
        .shadow-theme { box-shadow: 0 10px 15px -3px var(--theme-shadow) !important; }
        
        /* CLASS KHUSUS AKSES CEPAT BIAR GAK HARDCODING PELANGI LAGI */
        .bg-theme-light { background-color: var(--theme-light) !important; }
        .border-theme-light { border-color: var(--theme-border-light) !important; }
        .hover-bg-theme-light:hover { background-color: color-mix(in srgb, var(--theme-color) 15%, white) !important; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-800 h-screen overflow-hidden flex relative">

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

    <div class="flex-1 flex flex-col h-screen min-w-0 overflow-hidden bg-white">
        
        <header class="bg-white px-4 lg:px-8 py-4 flex justify-between items-center border-b border-slate-100 z-10 shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h1 class="font-bold text-lg lg:text-xl text-slate-800 tracking-wider">DASHBOARD</h1>
                    <p class="text-[10px] text-slate-400 hidden sm:block">Citra Niaga Samarinda</p>
                </div>
            </div>
            <div class="flex items-center gap-5">
                <div class="text-right mr-4 border-r border-slate-200 pr-5 hidden md:block">
                    <p id="live-time" class="text-sm font-bold text-theme tracking-wider">00:00:00</p>
                    <p id="live-date" class="text-[10px] text-slate-400 uppercase">Memuat tanggal...</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-theme text-white flex items-center justify-center font-bold text-sm shadow-sm transition-colors">
                        <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-xs text-slate-500">Selamat datang, <span class="font-bold text-theme"><?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?></span></p>
                        <p class="text-[10px] text-slate-400">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 hide-scrollbar bg-slate-50/50 w-full">
            
            <div class="bg-theme rounded-2xl p-6 lg:p-8 mb-6 lg:mb-8 relative overflow-hidden shadow-theme transition-colors duration-300">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 w-32 h-32 lg:w-48 lg:h-48 border-[15px] lg:border-[20px] border-white/10 rounded-full"></div>
                <p class="text-white/80 text-xs lg:text-sm flex items-center gap-2 mb-1">
                    <i data-lucide="trending-up" class="w-4 h-4"></i> Panel Administrasi
                </p>
                <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2 tracking-wide">SELAMAT DATANG, <?= strtoupper(htmlspecialchars($_SESSION['username'] ?? 'ADMIN')) ?> 👋</h2>
                <p class="text-white/80 text-xs lg:text-sm max-w-md">Pantau dan kelola data Citra Niaga Samarinda dari satu tempat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <div class="bg-theme rounded-2xl p-5 lg:p-6 text-white shadow-theme relative overflow-hidden transition-colors duration-300">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 lg:mb-4 bg-white/20">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-bold mb-1"><?= $total_sejarah ?></h3>
                    <p class="text-xs lg:text-sm font-semibold">Total Sejarah</p>
                    <p class="text-[10px] text-white/60">artikel tercatat</p>
                </div>

                <div class="bg-indigo-600 rounded-2xl p-5 lg:p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 lg:mb-4 bg-white/20">
                        <i data-lucide="store" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-bold mb-1"><?= $total_kios ?></h3>
                    <p class="text-xs lg:text-sm font-semibold">Total UMKM / Kios</p>
                    <p class="text-[10px] text-indigo-200">unit terdaftar</p>
                </div>

                <div class="bg-red-500 rounded-2xl p-5 lg:p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 lg:mb-4 bg-white/20">
                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-3xl lg:text-4xl font-bold mb-1"><?= $rata_review ?></h3>
                        <span class="text-lg">★</span>
                    </div>
                    <p class="text-xs lg:text-sm font-semibold">Total Review</p>
                    <p class="text-[10px] text-red-200">dari <?= $total_review ?> ulasan</p>
                </div>

                <div class="bg-emerald-500 rounded-2xl p-5 lg:p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 lg:mb-4 bg-white/20">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-bold mb-1"><?= $total_visitor_7_hari ?></h3>
                    <p class="text-xs lg:text-sm font-semibold">Traffic Mingguan</p>
                    <p class="text-[10px] text-emerald-100">pengunjung unik 7 hari</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">
                
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    <div class="bg-white rounded-3xl p-5 lg:p-8 shadow-sm border border-slate-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-6 gap-3">
                            <div>
                                <h3 class="font-bold text-base lg:text-lg text-slate-800 tracking-widest uppercase">Statistik Pengunjung</h3>
                                <p class="text-xs text-slate-500">Jumlah kunjungan 7 hari terakhir</p>
                            </div>
                            <div class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100 flex items-center gap-1.5 shadow-sm w-fit">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                Live
                            </div>
                        </div>
                        <div class="w-full h-[220px]">
                            <canvas id="trafficChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-5 lg:p-8 shadow-sm border border-slate-100">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="font-bold text-base lg:text-lg text-slate-800 tracking-widest uppercase">Aktivitas Ulasan</h3>
                                <p class="text-xs text-slate-500">Jumlah ulasan 7 hari terakhir</p>
                            </div>
                        </div>
                        <div class="w-full h-[220px]">
                            <canvas id="reviewChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-5 lg:p-8 shadow-sm border border-slate-100">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="font-bold text-base lg:text-lg text-slate-800 tracking-widest uppercase flex items-center gap-2">
                                    Halaman Terpopuler
                                </h3>
                                <p class="text-xs text-slate-500">Halaman yang paling sering dikunjungi</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <?php 
                            $max_views = !empty($top_pages_data) ? $top_pages_data[0]['total_views'] : 1; 
                            foreach($top_pages_data as $index => $t): 
                                $percentage = ($t['total_views'] / $max_views) * 100;
                                $page_name = $t['page_visited'];
                                if($page_name == 'index.php' || $page_name == '') $page_name = 'Home / Beranda';
                                if($page_name == 'kios.php') $page_name = 'Kios & UMKM';
                                if($page_name == 'gallery.php') $page_name = 'Galeri Foto';
                                if($page_name == 'events.php') $page_name = 'Event & Acara';
                                if($page_name == 'profile.php') $page_name = 'Profile & Sejarah';
                                if($page_name == 'reviews.php') $page_name = 'Ulasan Pengunjung';
                            ?>
                            <div>
                                <div class="flex justify-between text-xs lg:text-sm mb-1">
                                    <span class="font-bold text-slate-700 line-clamp-1 pr-2"><?= $index + 1 ?>. <?= $page_name ?></span>
                                    <span class="text-slate-500 font-bold shrink-0"><?= $t['total_views'] ?> views</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-theme h-2 rounded-full transition-all duration-300" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if(empty($top_pages_data)): ?>
                                <p class="text-sm text-slate-400 italic text-center py-4">Belum ada data kunjungan halaman.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="bg-white rounded-3xl p-5 lg:p-8 shadow-sm border border-slate-100 h-fit sticky top-8">
                    <h3 class="font-bold text-base lg:text-lg text-slate-800 tracking-widest uppercase mb-1">Akses Cepat</h3>
                    <p class="text-xs text-slate-500 mb-6">Kelola data langsung</p>

                    <div class="space-y-3">
                        <a href="history.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="clock" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Kelola Sejarah
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="gallery.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="image" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Kelola Galeri
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="kios.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="store" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Kelola Kios
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        
                        <a href="events.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="calendar" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Kelola Acara
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>

                        <a href="reviews.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="star" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Kelola Ulasan
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>

                        <a href="settings.php" class="flex items-center justify-between p-3 lg:p-4 rounded-xl border border-theme-light bg-theme-light hover-bg-theme-light transition text-theme font-semibold text-xs lg:text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="settings" class="w-4 h-4 lg:w-5 lg:h-5 text-theme"></i> Pengaturan Web
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();

        // JS BUKA TUTUP SIDEBAR HP
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            
            const liveTime = document.getElementById('live-time');
            const liveDate = document.getElementById('live-date');
            
            if(liveTime) liveTime.innerText = now.toLocaleTimeString('id-ID', timeOptions);
            if(liveDate) liveDate.innerText = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock(); 

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }, 
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } },
                    border: { display: false },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { color: '#94a3b8', font: { size: 10 } },
                    border: { display: false },
                    grid: { display: false }
                }
            }
        };

        const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
        new Chart(ctxTraffic, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>, 
                datasets: [{
                    label: 'Pengunjung Web',
                    data: <?php echo json_encode($visitor_data); ?>, 
                    borderColor: '#10b981', 
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4, 
                    fill: true
                }]
            },
            options: commonOptions
        });

        // GRAFIK REVIEW JUGA IKUTAN DINAMIS WARNANYA!
        const themeColorHex = '<?= $theme_color ?>';
        const ctxReview = document.getElementById('reviewChart').getContext('2d');
        new Chart(ctxReview, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>, 
                datasets: [{
                    label: 'Jumlah Ulasan',
                    data: <?php echo json_encode($review_data); ?>, 
                    borderColor: themeColorHex, 
                    backgroundColor: `color-mix(in srgb, ${themeColorHex} 15%, transparent)`,
                    borderWidth: 3,
                    pointBackgroundColor: themeColorHex,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4, 
                    fill: true
                }]
            },
            options: commonOptions
        });
    </script>
</body>
</html>