<?php 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 

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
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $display_date = date('d M', strtotime("-$i days"));
    $chart_labels[] = $display_date;

    $q_chart = @mysqli_query($conn, "SELECT COUNT(*) as cnt FROM reviews WHERE DATE(visit_date) = '$date'");
    $count = ($q_chart && $r = mysqli_fetch_assoc($q_chart)) ? (int)$r['cnt'] : 0;
    $chart_data[] = $count;
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
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
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white transition shadow-lg shadow-blue-600/20">
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
            
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition">
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
                <h1 class="font-bold text-xl text-slate-800 tracking-wider">DASHBOARD</h1>
                <p class="text-xs text-slate-400">Citra Niaga Samarinda</p>
            </div>
            <div class="flex items-center gap-5">
                <div class="text-right mr-4 border-r border-slate-200 pr-5 hidden md:block">
                    <p id="live-time" class="text-sm font-bold text-blue-600 tracking-wider">00:00:00</p>
                    <p id="live-date" class="text-[10px] text-slate-400 uppercase">Memuat tanggal...</p>
                </div>

                <button class="text-slate-400 hover:text-blue-600 transition relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-xs text-slate-500">Selamat datang, <span class="font-bold text-blue-600"><?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?></span></p>
                        <p class="text-[10px] text-slate-400">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 hide-scrollbar bg-slate-50/50">
            
            <div class="bg-blue-600 rounded-2xl p-8 mb-8 relative overflow-hidden shadow-sm">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 w-48 h-48 border-[20px] border-white/10 rounded-full"></div>
                <p class="text-blue-100 text-sm flex items-center gap-2 mb-1">
                    <i data-lucide="trending-up" class="w-4 h-4"></i> Panel Administrasi
                </p>
                <h2 class="text-3xl font-bold text-white mb-2 tracking-wide">SELAMAT DATANG, <?= strtoupper(htmlspecialchars($_SESSION['username'] ?? 'ADMIN')) ?> 👋</h2>
                <p class="text-blue-100 text-sm">Pantau dan kelola data Citra Niaga Samarinda dari satu tempat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-white/20">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-1"><?= $total_sejarah ?></h3>
                    <p class="text-sm font-semibold">Total Sejarah</p>
                    <p class="text-[10px] text-blue-200">artikel tercatat</p>
                </div>

                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-white/20">
                        <i data-lucide="store" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-1"><?= $total_kios ?></h3>
                    <p class="text-sm font-semibold">Total UMKM / Kios</p>
                    <p class="text-[10px] text-indigo-200">unit terdaftar</p>
                </div>

                <div class="bg-sky-500 rounded-2xl p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-white/20">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-4xl font-bold mb-1"><?= $total_galeri ?></h3>
                    <p class="text-sm font-semibold">Total Galeri</p>
                    <p class="text-[10px] text-sky-100">foto diunggah</p>
                </div>

                <div class="bg-red-500 rounded-2xl p-6 text-white shadow-sm relative overflow-hidden">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 bg-white/20">
                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-4xl font-bold mb-1"><?= $rata_review ?></h3>
                        <span class="text-lg">★</span>
                    </div>
                    <p class="text-sm font-semibold">Total Review</p>
                    <p class="text-[10px] text-red-200">dari <?= $total_review ?> ulasan</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">
                
                <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 tracking-widest uppercase">Aktivitas Ulasan</h3>
                            <p class="text-xs text-slate-500">Statistik 7 hari terakhir</p>
                        </div>
                        <div class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-full border border-green-100 flex items-center gap-1.5 shadow-sm">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            Live
                        </div>
                    </div>
                    <div class="w-full h-[250px]">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                    <h3 class="font-bold text-lg text-slate-800 tracking-widest uppercase mb-1">Akses Cepat</h3>
                    <p class="text-xs text-slate-500 mb-6">Kelola data langsung</p>

                    <div class="space-y-3">
                        <a href="history.php" class="flex items-center justify-between p-4 rounded-xl border border-blue-100 bg-blue-50/50 hover:bg-blue-100 transition text-blue-700 font-semibold text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="clock" class="w-5 h-5 text-blue-500"></i> Kelola Sejarah
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="gallery.php" class="flex items-center justify-between p-4 rounded-xl border border-indigo-100 bg-indigo-50/50 hover:bg-indigo-100 transition text-indigo-700 font-semibold text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="image" class="w-5 h-5 text-indigo-500"></i> Kelola Galeri
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="kios.php" class="flex items-center justify-between p-4 rounded-xl border border-sky-100 bg-sky-50/50 hover:bg-sky-100 transition text-sky-700 font-semibold text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="store" class="w-5 h-5 text-sky-500"></i> Kelola Kios
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                        <a href="reviews.php" class="flex items-center justify-between p-4 rounded-xl border border-red-100 bg-red-50/50 hover:bg-red-100 transition text-red-700 font-semibold text-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="star" class="w-5 h-5 text-red-500"></i> Kelola Review
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

        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            
            document.getElementById('live-time').innerText = now.toLocaleTimeString('id-ID', timeOptions);
            document.getElementById('live-date').innerText = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock(); 

        const ctx = document.getElementById('trafficChart').getContext('2d');
        const trafficChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>, 
                datasets: [{
                    label: 'Jumlah Ulasan',
                    data: <?php echo json_encode($chart_data); ?>, 
                    borderColor: '#2563eb', 
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4, 
                    fill: true
                }]
            },
            options: {
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
            }
        });
    </script>
</body>
</html>