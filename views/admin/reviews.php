<?php 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 
require_once '../../models/ReviewsModel.php'; 

$reviewsModel = new ReviewsModel($conn);
$reviewsList = $reviewsModel->getAllReviews(); 

$totalReviews = 0;
$totalRating = 0;
$positiveReviews = 0;
$reviewsData = [];

if($reviewsList && mysqli_num_rows($reviewsList) > 0) {
    while($row = mysqli_fetch_assoc($reviewsList)) {
        $reviewsData[] = $row;
        $totalReviews++;
        $totalRating += (int)$row['rating'];
        
        if((int)$row['rating'] >= 4) {
            $positiveReviews++;
        }
    }
}

$avgRating = $totalReviews > 0 ? number_format($totalRating / $totalReviews, 1) : "0.0";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Review - Admin Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-cinzel { font-family: 'Cinzel', serif; }
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
            <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white transition shadow-lg shadow-blue-600/20">
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
                <h1 class="font-bold text-xl text-slate-800 tracking-wider uppercase">Kelola Review</h1>
                <p class="text-xs text-slate-400">Moderasi ulasan pengunjung Citra Niaga</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="hidden md:block">
                    <p class="text-xs font-bold text-blue-600"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                    <p class="text-[10px] text-slate-400 uppercase">Administrator</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 hide-scrollbar bg-slate-50/50">
            
            <?php if(isset($_GET['deleted'])): ?>
            <div id="notification-alert" class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-100 shadow-sm transform translate-y-0 opacity-100">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Ulasan berhasil dihapus!</span>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800"><?= $totalReviews ?></h3>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total Ulasan</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                        <i data-lucide="star" class="w-6 h-6 fill-current"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800"><?= $avgRating ?></h3>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Rata-rata Bintang</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                        <i data-lucide="thumbs-up" class="w-6 h-6 fill-current"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800"><?= $positiveReviews ?></h3>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Ulasan Positif</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pengunjung</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rating</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ulasan</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php if(count($reviewsData) > 0): ?>
                                <?php foreach($reviewsData as $row): ?>
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0 border border-slate-200">
                                                <?= strtoupper(substr($row['visitor_name'], 0, 1)) ?>
                                            </div>
                                            <span class="text-sm font-bold text-slate-700">
                                                <?= htmlspecialchars($row['visitor_name']); ?>
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-1">
                                            <div class="flex text-yellow-400 text-xs">
                                                <?php 
                                                $rating = (int)$row['rating'];
                                                for($i=1; $i<=5; $i++): 
                                                    echo $i <= $rating ? '★' : '<span class="text-slate-200">★</span>';
                                                endfor; 
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <p class="text-xs text-slate-500 line-clamp-2 max-w-xs leading-relaxed" title="<?= htmlspecialchars($row['comment']); ?>">
                                            "<?= htmlspecialchars($row['comment']); ?>"
                                        </p>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-[11px] text-slate-400 font-medium whitespace-nowrap uppercase">
                                        <?= date('d M Y', strtotime($row['visit_date'])); ?>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        <form action="../../controllers/ReviewsController.php" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="hapus" class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition shadow-sm border border-red-100">
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