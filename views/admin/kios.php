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


require_once '../../models/KiosModel.php';

$kiosModel = new KiosModel($conn);
$kiosList = $kiosModel->getAllKios();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kios - Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .bg-sidebar { background-color: var(--sidebar-color) !important; }
        .shadow-theme { box-shadow: 0 10px 15px -3px var(--theme-shadow) !important; }
        .border-theme { border-color: var(--theme-color) !important; }
        .ring-theme:focus { --tw-ring-color: var(--theme-color) !important; border-color: transparent !important; }       
        .bg-theme-light { background-color: var(--theme-light) !important; }
        .border-theme-light { border-color: var(--theme-border-light) !important; }
        .hover-bg-theme-light:hover { background-color: var(--theme-light) !important; }
        .hover-border-theme:hover { border-color: var(--theme-color) !important; }
        .group:hover .group-hover\:text-theme { color: var(--theme-color) !important; }
        .hover-bg-theme:hover { filter: brightness(0.9); }
        @media (max-width: 768px) {
            .filter-btn { padding: 8px 14px !important; font-size: 0.75rem !important; }
            .form-container { padding: 1.25rem !important; }
            .modal-container { padding: 1rem !important; }
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
        <header class="bg-white border-b border-gray-200 px-4 lg:px-8 py-4 sticky top-0 z-10 shadow-sm flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 uppercase tracking-wider">Daftar Kios & UMKM</h2>
                    <p class="text-[10px] text-slate-400 hidden sm:block">Kelola data pedagang dan UMKM di kawasan</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-theme text-white flex items-center justify-center font-bold text-sm shadow-sm transition-colors">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
                </div>
            </div>
        </header>
        
        <div class="flex-1 overflow-y-auto p-4 lg:p-8 max-w-6xl mx-auto w-full">
            
            <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2 border border-green-200 shadow-sm" id="notification-alert">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i> 
                <span class="text-sm font-medium">Data Kios berhasil diperbarui!</span>
            </div>
            <?php endif; ?>

            <div class="bg-white p-5 lg:p-6 rounded-xl shadow-sm border border-gray-100 mb-8 form-container">
                <div class="flex items-center gap-2 mb-4 border-b pb-3">
                    <i data-lucide="plus-circle" class="text-theme"></i>
                    <h3 class="font-bold text-gray-800">Tambah Kios Baru</h3>
                </div>
                
                <form action="../../controllers/KiosController.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Nama Kios</label>
                        <input type="text" name="name" placeholder="Misal: Optik Jaya" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                    </div>
                    
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Jenis Bisnis</label>
                        <input list="business_types" name="business_type" placeholder="Pilih / Ketik Kategori Baru" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                        <datalist id="business_types">
                            <option value="Kerajinan_Suvenir">
                            <option value="Fashion_Aksesoris">
                            <option value="Kuliner">
                            <option value="Kesehatan_Kecantikan">
                            <option value="Umum">
                        </datalist>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Lokasi (Blok)</label>
                        <input type="text" name="location" placeholder="Misal: Blok A No. 12" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Nomor Telepon / WA</label>
                        <input type="text" name="contact_phone" placeholder="Misal: 0812-3456-7890" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Deskripsi Kios</label>
                        <textarea name="description" placeholder="Menjual kacamata, softlens, dll..." rows="3" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-2 block">Upload Foto Kios (Maks 1MB)</label>
                        <div class="relative mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover-border-theme hover-bg-theme-light transition-colors group cursor-pointer bg-slate-50/50" onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center">
                                <i data-lucide="upload-cloud" class="mx-auto h-12 w-12 text-gray-400 group-hover:text-theme transition-colors"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative font-medium text-theme focus-within:outline-none">
                                        <span>Klik untuk pilih foto dari File Explorer</span>
                                        <input id="file-upload" name="image" type="file" class="sr-only" accept="image/jpeg, image/png, image/webp">
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Hanya PNG, JPG, JPEG, WEBP (Max 1MB)</p>
                                <p id="file-name-display" class="text-sm font-bold text-green-600 mt-2 line-clamp-1 px-4"></p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end pt-4">
                        <button type="submit" name="tambah" class="w-full md:w-auto bg-theme text-white px-8 py-3 rounded-xl font-bold hover-bg-theme flex items-center justify-center gap-2 transition-colors shadow-md">
                            <i data-lucide="save" class="w-5 h-5"></i> Simpan Kios
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3 mb-4 mt-8">
                <i data-lucide="filter" class="w-5 h-5 text-gray-400"></i>
                <h3 class="font-bold text-gray-700">Filter Bisnis UMKM</h3>
            </div>
            
            <div class="flex flex-wrap gap-2 mb-6" id="filter-container">
                <button onclick="filterKios('all', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-theme text-white shadow-sm border border-theme transition-all">Semua Kategori</button>
                <button onclick="filterKios('Kerajinan_Suvenir', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Kerajinan Suvenir</button>
                <button onclick="filterKios('Fashion_Aksesoris', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Fashion Aksesoris</button>
                <button onclick="filterKios('Kuliner', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Kuliner</button>
                <button onclick="filterKios('Kesehatan_Kecantikan', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Kesehatan Kecantikan</button>
                <button onclick="filterKios('Umum', this)" class="filter-btn px-5 py-2.5 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all">Umum</button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-10">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-20">Foto</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-1/4">Nama & Kontak</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-1/4">Kategori & Lokasi</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Deskripsi</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-24 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kios-tbody" class="divide-y divide-gray-100">
                            <?php if($kiosList && mysqli_num_rows($kiosList) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($kiosList)): ?>
                                <tr class="kios-row hover:bg-gray-50/50 transition-colors" data-category="<?= htmlspecialchars($row['business_type']) ?>">
                                    <td class="p-4">
                                        <?php 
                                        $db_img = !empty($row['image']) ? $row['image'] : '';
                                        $display_img = $db_img ? str_replace('../assets', '../../assets', $db_img) : '../../assets/img/default-placeholder.png';
                                        ?>
                                        <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm flex items-center justify-center shrink-0">
                                            <img src="<?= htmlspecialchars($display_img) ?>" class="w-full h-full object-cover" onerror="this.src='../../assets/img/default-placeholder.png'">
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($row['name']) ?></div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-1"><i data-lucide="phone" class="w-3 h-3"></i> <?= htmlspecialchars($row['contact_phone']) ?></div>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-block px-3 py-1 bg-theme-light text-theme text-[10px] font-bold rounded-full mb-1 border border-theme-light whitespace-nowrap">
                                            <?= ucwords(str_replace('_', ' ', htmlspecialchars($row['business_type']))) ?>
                                        </span>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-1"><i data-lucide="map-pin" class="w-3 h-3 shrink-0"></i> <span class="line-clamp-1"><?= htmlspecialchars($row['location']) ?></span></div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-600 line-clamp-2 mt-3 max-w-[200px]">
                                        <?= htmlspecialchars($row['description']) ?>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="openEditModal(
                                                <?= $row['id'] ?>, 
                                                '<?= htmlspecialchars(addslashes($row['name'])) ?>', 
                                                '<?= htmlspecialchars(addslashes($row['business_type'])) ?>', 
                                                '<?= htmlspecialchars(addslashes($row['location'])) ?>', 
                                                '<?= htmlspecialchars(addslashes($row['contact_phone'])) ?>', 
                                                '<?= htmlspecialchars(addslashes($row['description'])) ?>',
                                                '<?= htmlspecialchars(addslashes($row['image'])) ?>'
                                            )" class="p-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <a href="../../controllers/KiosController.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kios ini?')" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500 italic">Belum ada data kios.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <div id="editModal" class="fixed inset-0 bg-black/60 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div id="modalContent" class="bg-white rounded-2xl w-full max-w-2xl transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-slate-50 shrink-0">
                <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-theme"></i> Edit Data Kios
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            
            <div class="overflow-y-auto p-5 modal-container">
                <form action="../../controllers/KiosController.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="old_image" id="edit_old_image"> 
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Nama Kios</label>
                            <input type="text" name="name" id="edit_name" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Jenis Bisnis</label>
                            <input list="edit_business_types" name="business_type" id="edit_business_type" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                            <datalist id="edit_business_types">
                                <option value="Kerajinan_Suvenir">
                                <option value="Fashion_Aksesoris">
                                <option value="Kuliner">
                                <option value="Kesehatan_Kecantikan">
                                <option value="Umum">
                            </datalist>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Lokasi (Blok)</label>
                            <input type="text" name="location" id="edit_location" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Nomor Telepon</label>
                            <input type="text" name="contact_phone" id="edit_phone" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Deskripsi Kios</label>
                            <textarea name="description" id="edit_description" rows="3" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-600 mb-2 block">Upload Foto Baru (Maks 1MB) <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ganti)</span></label>
                            <div class="relative mt-1 flex justify-center px-6 pt-4 pb-5 border-2 border-gray-300 border-dashed rounded-xl hover-border-theme hover-bg-theme-light transition-colors group cursor-pointer bg-slate-50/50" onclick="document.getElementById('edit-file-upload').click()">
                                <div class="space-y-1 text-center">
                                    <i data-lucide="image-plus" class="mx-auto h-8 w-8 text-gray-400 group-hover:text-theme transition-colors"></i>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span class="relative font-medium text-theme focus-within:outline-none">
                                            <span>Klik untuk pilih foto dari File Explorer</span>
                                            <input id="edit-file-upload" name="image" type="file" class="sr-only" accept="image/jpeg, image/png, image/webp">
                                        </span>
                                    </div>
                                    <p id="edit-file-name-display" class="text-sm font-bold text-green-600 mt-2 line-clamp-1 px-4"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                        <button type="button" onclick="closeEditModal()" class="w-full md:w-auto px-5 py-2.5 rounded-xl text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium transition-colors text-center">Batal</button>
                        <button type="submit" name="update" class="w-full md:w-auto px-5 py-2.5 bg-theme text-white rounded-xl font-bold hover-bg-theme transition-colors flex items-center justify-center gap-2 shadow-md">
                            <i data-lucide="check" class="w-4 h-4"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function filterKios(category, btnElement) {
            const allBtns = document.querySelectorAll('.filter-btn');
            allBtns.forEach(btn => {
                btn.classList.remove('bg-theme', 'text-white', 'shadow-sm', 'border-theme');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
            });
            btnElement.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
            btnElement.classList.add('bg-theme', 'text-white', 'shadow-sm', 'border-theme');

            const rows = document.querySelectorAll('.kios-row');
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
                    const tbody = document.getElementById('kios-tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'empty-filter-msg';
                    tr.innerHTML = `<td colspan="5" class="p-8 text-center text-gray-500"><i data-lucide="store" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i><p>Tidak ada kios di kategori ini.</p></td>`;
                    tbody.appendChild(tr);
                    lucide.createIcons();
                } else {
                    emptyRow.style.display = '';
                }
            } else if (emptyRow) {
                emptyRow.style.display = 'none';
            }
        }

        function validateImageFile(input) {
            const file = input.files[0];
            if (file) {
                if (file.size > 1048576) {
                    alert("⚠️ UKURAN TERLALU BESAR!\nMaksimal file gambar adalah 1 MB.");
                    input.value = ""; 
                    return false;
                }
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert("⚠️ FORMAT TIDAK DIDUKUNG!\nHanya diperbolehkan upload file berformat JPG, JPEG, PNG, atau WEBP.");
                    input.value = ""; 
                    return false;
                }
            }
            return true;
        }

        document.getElementById('file-upload').addEventListener('change', function(e) {
            if (validateImageFile(this)) {
                const fileName = e.target.files[0] ? e.target.files[0].name : '';
                document.getElementById('file-name-display').textContent = fileName ? 'File dipilih: ' + fileName : '';
            } else {
                document.getElementById('file-name-display').textContent = '';
            }
        });

        document.getElementById('edit-file-upload').addEventListener('change', function(e) {
            if (validateImageFile(this)) {
                const fileName = e.target.files[0] ? e.target.files[0].name : '';
                document.getElementById('edit-file-name-display').textContent = fileName ? 'File dipilih: ' + fileName : '';
            } else {
                document.getElementById('edit-file-name-display').textContent = '';
            }
        });

        function openEditModal(id, name, type, loc, phone, desc, oldImg) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_business_type').value = type;
            document.getElementById('edit_location').value = loc;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_description').value = desc;
            document.getElementById('edit_old_image').value = oldImg; 
            document.getElementById('edit-file-name-display').textContent = ''; 

            const modal = document.getElementById('editModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('modalContent');
            
            content.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        const alertBox = document.getElementById('notification-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alertBox.style.opacity = '0';
                alertBox.style.transform = 'translateY(-10px)';
                setTimeout(() => alertBox.remove(), 500); 
            }, 3000); 
        }
    </script>
</body>
</html>