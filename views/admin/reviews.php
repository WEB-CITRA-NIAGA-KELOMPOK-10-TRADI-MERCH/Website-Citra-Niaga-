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
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['admin_theme_color']) ? htmlspecialchars($web_setting['admin_theme_color']) : '#2563eb';
$sidebar_color = !empty($web_setting['admin_sidebar_color']) ? htmlspecialchars($web_setting['admin_sidebar_color']) : '#1e293b';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($_GET['action'] == 'pin') {
        $cek_pin = mysqli_query($conn, "SELECT COUNT(id) as total FROM reviews WHERE is_pinned = 1");
        $data_pin = mysqli_fetch_assoc($cek_pin);
        
        if ($data_pin['total'] >= 3) {
            header("Location: reviews.php?error=max_pin");
            exit;
        }
        
        mysqli_query($conn, "UPDATE reviews SET is_pinned = 1 WHERE id = $id");
        header("Location: reviews.php?pinned=1");
        exit;
    }
    
    if ($_GET['action'] == 'unpin') {
        mysqli_query($conn, "UPDATE reviews SET is_pinned = 0 WHERE id = $id");
        header("Location: reviews.php?unpinned=1");
        exit;
    }
}

require_once '../../models/ReviewsModel.php'; 

$reviewsModel = new ReviewsModel($conn);
$reviewsList = $reviewsModel->getAllReviews(); 

$totalReviews = 0;
$totalRating = 0;
$positiveReviews = 0;
$reviewsData = [];

$all_text = "";
$pos_count = 0; $neg_count = 0;
$pos_words = ['bagus','bersih','keren','nyaman','murah','enak','indah','ramah','mantap','rapi','luas','aman','estetik','sejuk','terbaik','puas'];
$neg_words = ['kotor','jelek','mahal','bau','macet','kasar','rusak','sempit','panas','buruk','jorok','berantakan','lama','kecewa','kurang'];

if($reviewsList && mysqli_num_rows($reviewsList) > 0) {
    while($row = mysqli_fetch_assoc($reviewsList)) {
        $reviewsData[] = $row;
        $totalReviews++;
        $totalRating += (int)$row['rating'];
        
        if((int)$row['rating'] >= 4) {
            $positiveReviews++;
        }

        $text = strtolower($row['comment'] ?? '');
        $all_text .= " " . $text;

        foreach($pos_words as $w) { if(strpos($text, $w) !== false) $pos_count++; }
        foreach($neg_words as $w) { if(strpos($text, $w) !== false) $neg_count++; }
    }
}

$avgRating = $totalReviews > 0 ? number_format($totalRating / $totalReviews, 1) : "0.0";

$sentiment_result = "Netral 😐"; $sentiment_color = "text-slate-600"; $sentiment_icon = "minus"; 
$sentiment_bg = "bg-slate-50"; $sentiment_border = "border-slate-200";

if ($pos_count > $neg_count + 2) { 
    $sentiment_result = "Sangat Positif 🤩"; $sentiment_color = "text-emerald-600"; $sentiment_icon = "smile"; 
    $sentiment_bg = "bg-emerald-50/50"; $sentiment_border = "border-emerald-200";
}
elseif ($pos_count > $neg_count) { 
    $sentiment_result = "Positif 🙂"; $sentiment_color = "text-green-600"; $sentiment_icon = "thumbs-up"; 
    $sentiment_bg = "bg-green-50/50"; $sentiment_border = "border-green-200";
}
elseif ($neg_count > $pos_count + 2) { 
    $sentiment_result = "Sangat Negatif 😡"; $sentiment_color = "text-red-600"; $sentiment_icon = "frown"; 
    $sentiment_bg = "bg-red-50/50"; $sentiment_border = "border-red-200";
}
elseif ($neg_count > $pos_count) { 
    $sentiment_result = "Negatif 😟"; $sentiment_color = "text-orange-600"; $sentiment_icon = "thumbs-down"; 
    $sentiment_bg = "bg-orange-50/50"; $sentiment_border = "border-orange-200";
}

$words = str_word_count(preg_replace('/[^a-z]/', ' ', strtolower($all_text)), 1);
$stopwords = ['yang','dan','di','ke','dari','ini','itu','untuk','dengan','ada','tidak','bisa','juga','sudah','saya','kami','tempat','citra','niaga','sangat','banget','lagi','buat','kalau','sama','nya','aku','karena'];
$filtered_words = array_diff($words, $stopwords);
$filtered_words = array_filter($filtered_words, function($w) { return strlen($w) > 3; }); 
$word_counts = array_count_values($filtered_words);
arsort($word_counts);
$top_keywords = array_slice($word_counts, 0, 6, true); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ulasan - Admin Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --theme-color: <?= $theme_color ?>;
            --sidebar-color: <?= $sidebar_color ?>;
            --font-custom: '<?= $font_family ?>', sans-serif;
            --theme-shadow: color-mix(in srgb, var(--theme-color) 30%, transparent);
            --theme-light: color-mix(in srgb, var(--theme-color) 10%, white);
            --theme-border-light: color-mix(in srgb, var(--theme-color) 20%, white);
        }
        
        body { font-family: var(--font-custom) !important; }
        .font-cinzel { font-family: 'Cinzel', serif !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        #notification-alert { transition: opacity 0.5s ease, transform 0.5s ease; }
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .bg-sidebar { background-color: var(--sidebar-color) !important; }
        .shadow-theme { box-shadow: 0 10px 15px -3px var(--theme-shadow) !important; }
        .border-theme { border-color: var(--theme-color) !important; }      
        .bg-theme-light { background-color: var(--theme-light) !important; }
        .border-theme-light { border-color: var(--theme-border-light) !important; }
        .hover-bg-theme-light:hover { background-color: color-mix(in srgb, var(--theme-color) 15%, white) !important; }
        @media (max-width: 768px) {
            .filter-btn { padding: 8px 14px !important; font-size: 0.75rem !important; }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 h-screen overflow-hidden flex relative">

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
                    <h2 class="font-bold text-lg lg:text-xl text-slate-800 tracking-wider uppercase">Kelola Ulasan</h2>
                    <p class="text-[10px] text-slate-400 hidden sm:block">Moderasi dan Analisis ulasan pengunjung Citra Niaga</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-theme text-white flex items-center justify-center font-bold text-sm shadow-sm transition-colors">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="hidden md:block">
                    <p class="text-xs font-bold text-theme"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                    <p class="text-[10px] text-slate-400 uppercase">Administrator</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 hide-scrollbar bg-[#f8fafc] w-full">
            
            <?php if(isset($_GET['deleted'])): ?>
            <div id="notification-alert" class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-100 shadow-sm transform translate-y-0 opacity-100">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Ulasan berhasil dihapus!</span>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['pinned'])): ?>
            <div id="notification-alert" class="bg-theme-light text-theme p-4 rounded-xl mb-6 flex items-center gap-3 border border-theme-light shadow-sm transform translate-y-0 opacity-100">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Ulasan berhasil di-pin ke halaman Home!</span>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['unpinned'])): ?>
            <div id="notification-alert" class="bg-slate-50 text-slate-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-slate-200 shadow-sm transform translate-y-0 opacity-100">
                <i data-lucide="pin-off" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Ulasan dilepas dari halaman Home.</span>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['error']) && $_GET['error'] == 'max_pin'): ?>
            <div id="notification-alert" class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-200 shadow-sm transform translate-y-0 opacity-100">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <span class="text-sm font-medium"><strong>GAGAL:</strong> Maksimal ulasan yang di-pin hanya 3! Lepas (unpin) ulasan lain terlebih dahulu.</span>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                
                <div class="bg-theme rounded-2xl p-6 shadow-theme text-white relative overflow-hidden flex flex-col justify-between transition-colors">
                    <i data-lucide="message-square" class="absolute -right-4 -bottom-4 w-28 h-28 text-white opacity-10 transform -rotate-12"></i>
                    <div class="relative z-10">
                        <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest mb-1">Total Ulasan Masuk</p>
                        <h3 class="text-4xl lg:text-5xl font-black mb-1"><?= $totalReviews ?></h3>
                    </div>
                    <div class="relative z-10 mt-4">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/20 backdrop-blur-sm text-xs font-medium border border-white/20">
                            <i data-lucide="activity" class="w-3 h-3"></i> Data Real-time
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Rata-Rata Rating</p>
                            <i data-lucide="star" class="w-5 h-5 text-amber-400 fill-current"></i>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <h3 class="text-4xl font-black text-slate-800"><?= $avgRating ?></h3>
                            <span class="text-sm text-slate-400 font-bold">/ 5.0</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex text-amber-400 mb-2 text-sm">
                            <?php for($i=1; $i<=5; $i++) echo $i <= round($avgRating) ? '★' : '<span class="text-slate-200">★</span>'; ?>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-amber-400 h-1.5 rounded-full" style="width: <?= ($avgRating/5)*100 ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="<?= $sentiment_bg ?> rounded-2xl p-6 border <?= $sentiment_border ?> shadow-sm flex flex-col justify-center items-center text-center transition-colors">
                    <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-sm mb-4 <?= $sentiment_color ?>">
                        <i data-lucide="<?= $sentiment_icon ?>" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-black <?= $sentiment_color ?> mb-1"><?= $sentiment_result ?></h3>
                    <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">Hasil Analisis</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-4 flex items-center gap-1.5">
                        <i data-lucide="hash" class="w-3.5 h-3.5 text-theme"></i> Top Keywords
                    </p>
                    <div class="flex flex-wrap gap-2 mt-auto">
                        <?php if(!empty($top_keywords)): foreach($top_keywords as $word => $count): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 text-slate-600 text-[11px] font-bold border border-slate-200 hover-bg-theme-light hover:text-theme hover-border-theme transition-all cursor-default shadow-sm">
                                <?= strtoupper($word) ?> 
                                <span class="bg-white px-1.5 py-0.5 rounded-full text-[9px] text-slate-400"><?= $count ?></span>
                            </span>
                        <?php endforeach; else: echo "<span class='text-xs text-slate-400 italic'>Belum ada data</span>"; endif; ?>
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-3 mb-4 mt-8">
                <i data-lucide="filter" class="w-5 h-5 text-slate-400"></i>
                <h3 class="font-bold text-slate-700">Filter Bintang Ulasan</h3>
            </div>
            
            <div class="flex flex-wrap gap-2 mb-6" id="filter-container">
                <button onclick="filterReviews('all', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-theme text-white shadow-sm border border-theme transition-all">SEMUA</button>
                <button onclick="filterReviews('5', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all">5 ★</button>
                <button onclick="filterReviews('4', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all">4 ★</button>
                <button onclick="filterReviews('3', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all">3 ★</button>
                <button onclick="filterReviews('2', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all">2 ★</button>
                <button onclick="filterReviews('1', this)" class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all">1 ★</button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-10">
                <div class="p-4 lg:p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-2 bg-slate-50/50">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Daftar Ulasan Pengunjung</h3>
                    <span class="text-[10px] text-theme bg-theme-light px-3 py-1 rounded-full border border-theme-light font-bold w-fit">Maksimal 3 ulasan yang dapat di-pin ke Home</span>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-1/4">Pengunjung</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rating</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-1/3">Ulasan</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tampil Home</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="reviews-tbody" class="divide-y divide-slate-100">
                            <?php if(count($reviewsData) > 0): ?>
                                <?php foreach($reviewsData as $row): ?>
                                <tr class="review-row hover:bg-slate-50/80 transition duration-150" data-rating="<?= (int)$row['rating'] ?>">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm font-bold shrink-0 border border-slate-200">
                                                <?= strtoupper(substr($row['visitor_name'], 0, 1)) ?>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-800">
                                                    <?= htmlspecialchars($row['visitor_name']); ?>
                                                </span>
                                                <span class="text-[10px] text-slate-400"><?= date('d M Y', strtotime($row['visit_date'])); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 align-top pt-5">
                                        <div class="flex text-amber-400 text-xs">
                                            <?php 
                                            $rating = (int)$row['rating'];
                                            for($i=1; $i<=5; $i++) echo $i <= $rating ? '★' : '<span class="text-slate-200">★</span>';
                                            ?>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <p class="text-xs text-slate-500 line-clamp-3 max-w-xs leading-relaxed" title="<?= htmlspecialchars($row['comment']); ?>">
                                            "<?= htmlspecialchars($row['comment']); ?>"
                                        </p>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center align-middle">
                                        <?php if(isset($row['is_pinned']) && $row['is_pinned'] == 1): ?>
                                            <span class="bg-theme-light text-theme px-3 py-1 rounded-full text-[10px] font-bold border border-theme-light inline-flex items-center justify-center gap-1 shadow-sm w-fit mx-auto">
                                                <i data-lucide="pin" class="w-3 h-3 fill-current"></i> Dipin
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-[10px] font-medium">-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center space-x-1 align-middle">
                                        <?php if(isset($row['is_pinned']) && $row['is_pinned'] == 1): ?>
                                            <a href="?action=unpin&id=<?= $row['id'] ?>" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition border border-slate-200" title="Lepas dari Home">
                                                <i data-lucide="pin-off" class="w-4 h-4"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?action=pin&id=<?= $row['id'] ?>" class="inline-flex items-center justify-center w-8 h-8 bg-theme-light hover-bg-theme-light text-theme rounded-lg transition border border-theme-light" title="Tampilkan di Home">
                                                <i data-lucide="pin" class="w-4 h-4"></i>
                                            </a>
                                        <?php endif; ?>

                                        <form action="../../controllers/ReviewsController.php" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="hapus" class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition border border-red-200" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <i data-lucide="inbox" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
                                        <p class="text-slate-400 text-sm italic">Belum ada ulasan dari pengunjung.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function filterReviews(rating, btnElement) {
            const allBtns = document.querySelectorAll('.filter-btn');
            allBtns.forEach(btn => {
                btn.classList.remove('bg-theme', 'text-white', 'shadow-sm', 'border-theme');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
            });
            btnElement.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            btnElement.classList.add('bg-theme', 'text-white', 'shadow-sm', 'border-theme');

            const rows = document.querySelectorAll('.review-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                if (rating === 'all' || row.getAttribute('data-rating') === rating) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            let emptyRow = document.getElementById('empty-filter-msg');
            if (visibleCount === 0 && rows.length > 0) {
                if (!emptyRow) {
                    const tbody = document.getElementById('reviews-tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'empty-filter-msg';
                    tr.innerHTML = `<td colspan="5" class="py-20 text-center"><i data-lucide="star-off" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i><p class="text-slate-500 font-medium text-sm">Tidak ada ulasan dengan rating ini.</p></td>`;
                    tbody.appendChild(tr);
                    lucide.createIcons();
                } else {
                    emptyRow.style.display = '';
                }
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }
        }

        const alertBox = document.getElementById('notification-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.opacity = '0';
                alertBox.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alertBox.remove();
                }, 500); 
            }, 5000); 
        }
    </script>
</body>
</html>