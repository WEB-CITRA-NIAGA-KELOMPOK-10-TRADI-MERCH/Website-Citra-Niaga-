<?php 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 

// === PANGGIL PENGATURAN CMS BIAR ADMIN DINAMIS ===
require_once '../../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

// PENTING: Panggil admin_theme_color & admin_sidebar_color biar pisah dari publik!
$theme_color = !empty($web_setting['admin_theme_color']) ? htmlspecialchars($web_setting['admin_theme_color']) : '#2563eb';
$sidebar_color = !empty($web_setting['admin_sidebar_color']) ? htmlspecialchars($web_setting['admin_sidebar_color']) : '#1e293b';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';
// ======================================================

$query_events = mysqli_query($conn, "SELECT * FROM events ORDER BY start_date DESC");
$events = [];
if ($query_events) {
    while($row = mysqli_fetch_assoc($query_events)) {
        $events[] = $row;
    }
}
$total_events = count($events);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Acara - Admin Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* SIHIR CSS DINAMIS UNTUK ADMIN */
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

        /* KELAS DINAMIS */
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .bg-sidebar { background-color: var(--sidebar-color) !important; }
        .shadow-theme { box-shadow: 0 10px 15px -3px var(--theme-shadow) !important; }
        .border-theme { border-color: var(--theme-color) !important; }
        .ring-theme:focus { --tw-ring-color: var(--theme-color) !important; border-color: transparent !important; }
        
        .bg-theme-light { background-color: var(--theme-light) !important; }
        .border-theme-light { border-color: var(--theme-border-light) !important; }
        .hover-bg-theme-light:hover { background-color: color-mix(in srgb, var(--theme-color) 15%, white) !important; }
        .hover-bg-theme:hover { filter: brightness(0.9); }

        /* ======================================================== */
        /* --- FIX RESPONSIVE KHUSUS LAYAR HP (MOBILE DEVICES) ---  */
        /* ======================================================== */
        @media (max-width: 768px) {
            .filter-btn { padding: 8px 14px !important; font-size: 0.75rem !important; }
            .form-container { padding: 1.25rem !important; }
        }
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
                    <h1 class="font-bold text-lg lg:text-xl text-slate-800 tracking-wider uppercase">Kelola Acara</h1>
                    <p class="text-[10px] text-slate-400 hidden sm:block">Atur acara, festival, dan live music di kawasan</p>
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

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 hide-scrollbar bg-slate-50/50 relative w-full">
            
            <?php if(isset($_GET['success'])): ?>
            <div id="notification-alert" class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="text-sm font-medium">
                    <?php 
                        if($_GET['success'] == 'add') echo 'Event berhasil ditambahkan!';
                        elseif($_GET['success'] == 'edit') echo 'Event berhasil diperbarui!';
                        else echo 'Event berhasil dihapus!';
                    ?>
                </span>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 lg:p-6 mb-8 form-container">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <i data-lucide="calendar-plus" class="text-theme w-5 h-5"></i>
                    <h3 class="font-bold text-lg text-slate-800">Tambah Acara Baru</h3>
                </div>

                <form action="../../controllers/EventController.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Judul Acara</label>
                            <input type="text" name="title" required placeholder="Misal: Festival Mahakam 2026" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition">
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition cursor-pointer">
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Tanggal Selesai</label>
                            <input type="date" name="end_date" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition cursor-pointer">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Deskripsi Acara</label>
                            <textarea name="description" rows="3" required placeholder="Ceritakan detail acara ini..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 block">Upload Poster / Banner</label>
                            <input type="file" name="image" accept="image/jpeg, image/png, image/webp" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold bg-theme-light text-theme hover-bg-theme-light transition cursor-pointer border border-slate-200 rounded-xl">
                            <p class="text-[10px] text-slate-400 mt-2">Rekomendasi format: JPG, PNG, WEBP. Maksimal ukuran 2MB.</p>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" name="tambah_event" class="w-full md:w-auto bg-theme text-white px-6 py-2.5 rounded-xl font-bold hover-bg-theme transition shadow-md flex justify-center items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Event
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3 mb-4 mt-8">
                <i data-lucide="filter" class="w-5 h-5 text-gray-400"></i>
                <h3 class="font-bold text-gray-700">Filter Jadwal Acara</h3>
            </div>
            
            <div class="flex flex-wrap gap-2 mb-6" id="filter-container">
                <button onclick="filterEvents('all', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-theme text-white shadow-sm border border-theme transition-all">Semua Acara</button>
                <button onclick="filterEvents('ongoing', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Sedang Berjalan</button>
                <button onclick="filterEvents('upcoming', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Akan Datang</button>
                <button onclick="filterEvents('past', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Telah Selesai</button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Daftar Acara Terekam (<?= $total_events ?>)</h3>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24">Poster</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul & Deskripsi</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelaksanaan</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="events-tbody" class="divide-y divide-slate-50">
                            <?php if($total_events > 0): ?>
                                <?php 
                                $today = date('Y-m-d');
                                foreach($events as $row): 
                                    $start_date = $row['start_date'];
                                    $end_date = $row['end_date'];
                                    
                                    if ($today >= $start_date && $today <= $end_date) {
                                        $status = 'ongoing';
                                        $badge_class = 'bg-green-100 text-green-700 border-green-200';
                                        $badge_text = 'Sedang Berjalan';
                                    } elseif ($start_date > $today) {
                                        $status = 'upcoming';
                                        $badge_class = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                                        $badge_text = 'Akan Datang';
                                    } else {
                                        $status = 'past';
                                        $badge_class = 'bg-slate-100 text-slate-500 border-slate-200';
                                        $badge_text = 'Telah Selesai';
                                    }
                                ?>
                                <tr class="event-row hover:bg-slate-50/80 transition duration-150" data-category="<?= $status ?>">
                                    
                                    <td class="py-4 px-6">
                                        <?php if(!empty($row['image'])): ?>
                                            <img src="../../assets/img/Events/<?= htmlspecialchars($row['image']) ?>" alt="Poster" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm <?= $status == 'past' ? 'grayscale opacity-70' : '' ?>">
                                        <?php else: ?>
                                            <div class="w-16 h-16 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200 text-slate-400 <?= $status == 'past' ? 'grayscale opacity-70' : '' ?>">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-bold text-slate-800 <?= $status == 'past' ? 'text-slate-500 line-through decoration-slate-300' : '' ?>"><?= htmlspecialchars($row['title']) ?></h4>
                                        </div>
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed <?= $status == 'past' ? 'opacity-70' : '' ?>"><?= htmlspecialchars($row['description']) ?></p>
                                    </td>
                                    
                                    <td class="py-4 px-6 align-middle">
                                        <div class="inline-flex flex-col gap-2 items-stretch min-w-[170px]">
                                            <span class="text-center px-3 py-1.5 border text-[10px] font-bold rounded-lg <?= $badge_class ?>"><?= $badge_text ?></span>
                                            
                                            <div class="flex items-center justify-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg border <?= $status == 'past' ? 'bg-slate-50 border-slate-200 text-slate-500' : 'bg-theme-light border-theme-light text-theme' ?>">
                                                <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                                <span class="whitespace-nowrap">
                                                <?php 
                                                    $start = date('d M Y', strtotime($row['start_date']));
                                                    $end = date('d M Y', strtotime($row['end_date']));
                                                    echo ($start == $end) ? $start : "$start - $end";
                                                ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center space-x-2">
                                        <button type="button" onclick="document.getElementById('editModal<?= $row['id'] ?>').classList.remove('hidden')" class="inline-block bg-theme-light hover-bg-theme-light text-theme p-2 rounded-lg transition border border-theme-light" title="Edit Event">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <a href="../../controllers/EventController.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus event ini? Data dan poster akan hilang permanen.');" class="inline-block bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition border border-red-100" title="Hapus Event">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div id="editModal<?= $row['id'] ?>" class="hidden fixed inset-0 bg-slate-900/50 z-[100] flex items-center justify-center backdrop-blur-sm p-4">
                                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
                                        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                            <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                                <i data-lucide="edit-3" class="w-5 h-5 text-theme"></i> Edit <?= htmlspecialchars($row['title']) ?>
                                            </h3>
                                            <button type="button" onclick="document.getElementById('editModal<?= $row['id'] ?>').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                                        </div>
                                        <div class="p-6 overflow-y-auto">
                                            <form action="../../controllers/EventController.php" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                
                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 block">Judul Acara</label>
                                                    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition">
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 block">Tanggal Mulai</label>
                                                        <input type="date" name="start_date" value="<?= $row['start_date'] ?>" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition">
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 block">Tanggal Selesai</label>
                                                        <input type="date" name="end_date" value="<?= $row['end_date'] ?>" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 block">Deskripsi Acara</label>
                                                    <textarea name="description" rows="4" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 ring-theme outline-none transition"><?= htmlspecialchars($row['description']) ?></textarea>
                                                </div>

                                                <div>
                                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 block">Upload Poster Baru (Opsional)</label>
                                                    <input type="file" name="image" accept="image/jpeg, image/png, image/webp" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold bg-theme-light text-theme hover-bg-theme-light transition cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                                                    <p class="text-[10px] text-slate-400 mt-2">Biarkan kosong jika tidak ingin mengubah poster saat ini.</p>
                                                </div>
                                                
                                                <div class="pt-4 border-t border-slate-100 flex flex-col md:flex-row justify-end gap-3">
                                                    <button type="button" onclick="document.getElementById('editModal<?= $row['id'] ?>').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition text-center">Batal</button>
                                                    <button type="submit" name="edit_event" class="bg-theme text-white px-6 py-2.5 rounded-xl font-bold hover-bg-theme transition shadow-md text-center">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <i data-lucide="calendar-x" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
                                        <p class="text-slate-400 text-sm italic">Belum ada acara yang dijadwalkan.</p>
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

        // JS BUKA TUTUP SIDEBAR HP
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function filterEvents(category, btnElement) {
            const allBtns = document.querySelectorAll('.filter-btn');
            allBtns.forEach(btn => {
                btn.classList.remove('bg-theme', 'text-white', 'shadow-sm', 'border-theme');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
            });
            btnElement.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
            btnElement.classList.add('bg-theme', 'text-white', 'shadow-sm', 'border-theme');

            const rows = document.querySelectorAll('.event-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                if (category === 'all' || row.getAttribute('data-category') === category) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            let emptyRow = document.getElementById('empty-filter-msg');
            if (visibleCount === 0 && rows.length > 0) {
                if (!emptyRow) {
                    const tbody = document.getElementById('events-tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'empty-filter-msg';
                    tr.innerHTML = `<td colspan="4" class="py-20 text-center"><i data-lucide="calendar-search" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i><p class="text-slate-500 font-medium text-sm">Tidak ada acara di kategori ini.</p></td>`;
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
                setTimeout(() => { alertBox.remove(); }, 500); 
            }, 3000); 
        }
    </script>
</body>
</html>