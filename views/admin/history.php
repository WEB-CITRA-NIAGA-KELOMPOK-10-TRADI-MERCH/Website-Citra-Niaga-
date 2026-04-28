<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

require_once '../../models/HistoryModel.php';

$historyModel = new HistoryModel($conn);
$histories = $historyModel->getAllHistory();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sejarah - Citra Niaga</title>
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
        .hover-bg-theme:hover { filter: brightness(0.9); }
        @media (max-width: 768px) {
            .form-container { padding: 1.25rem !important; }
            .modal-container { padding: 1rem !important; }
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden relative">
    
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden transition-opacity"></div>

    <?php $admin_page = basename($_SERVER['PHP_SELF']); ?>
    <aside id="main-sidebar" class="w-64 bg-sidebar text-slate-300 flex flex-col h-full shrink-0 shadow-2xl z-40 fixed lg:static transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
        <div class="p-6 flex items-center gap-3 border-b border-white/5 justify-between lg:justify-start">
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
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 uppercase tracking-wider">Kelola Sejarah</h2>
                    <p class="text-[10px] text-slate-400 hidden sm:block">Kelola lini masa dan profil kawasan</p>
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
            <div id="notification-alert" class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2 border border-green-200 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i> 
                <span class="text-sm font-medium">Aksi berhasil dilakukan!</span>
            </div>
            <?php endif; ?>

            <div class="bg-white p-5 lg:p-6 rounded-xl shadow-sm border border-gray-100 mb-8 form-container">
                <div class="flex items-center gap-2 mb-4 border-b pb-3">
                    <i data-lucide="plus-circle" class="text-theme"></i>
                    <h3 class="font-bold text-gray-800">Tambah Data Sejarah Baru</h3>
                </div>
                <form action="../../controllers/HistoryController.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Judul Peristiwa</label>
                        <input type="text" name="title" placeholder="Misal: Aga Khan Award" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun / Periode</label>
                        <input type="text" name="year" placeholder="Misal: 1989" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Isi / Cerita Sejarah</label>
                        <textarea name="content" placeholder="Ceritakan detail sejarah di sini..." class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" rows="4" required></textarea>
                    </div>
                    <div class="md:col-span-2 flex justify-end pt-2">
                        <button type="submit" name="add" class="w-full md:w-auto bg-theme text-white px-6 py-3 rounded-lg font-bold hover-bg-theme flex justify-center items-center gap-2 transition-colors shadow-md">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Sejarah
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-10">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-24">Tahun</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider w-1/4">Judul</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Konten Singkat</th>
                                <th class="p-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            if(mysqli_num_rows($histories) > 0):
                                while($row = mysqli_fetch_assoc($histories)): 
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 font-bold text-theme align-top"><?= htmlspecialchars($row['year'] ?? '') ?></td>
                                <td class="p-4 font-bold text-gray-800 align-top"><?= htmlspecialchars($row['title'] ?? '') ?></td>
                                <td class="p-4 text-gray-500 text-sm text-justify align-top">
                                    <?= htmlspecialchars(substr($row['content'] ?? '', 0, 150)) ?><?= strlen($row['content'] ?? '') > 150 ? '...' : '' ?>
                                </td>
                                <td class="p-4 text-right align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                                onclick='openEditModal(
                                                    <?= $row['id'] ?>, 
                                                    <?= json_encode($row['title'] ?? '') ?>, 
                                                    <?= json_encode($row['year'] ?? '') ?>, 
                                                    <?= json_encode($row['content'] ?? '') ?>
                                                )' 
                                                class="p-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <a href="../../controllers/HistoryController.php?hapus=<?= $row['id'] ?>" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" onclick="return confirm('Yakin hapus data sejarah ini?');" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">
                                        <i data-lucide="book-x" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                                        <p class="text-sm">Belum ada data sejarah. Silakan tambahkan data baru.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div id="editModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]" id="modalContent">
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-slate-50 shrink-0">
                <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-theme"></i> Edit Data Sejarah
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-5 modal-container">
                <form action="../../controllers/HistoryController.php" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Judul Peristiwa</label>
                            <input type="text" name="title" id="edit_title" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                        </div>
                        
                        <div>
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun / Periode</label>
                            <input type="text" name="year" id="edit_year" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Isi / Cerita Sejarah</label>
                            <textarea name="content" id="edit_content" rows="6" class="p-3 bg-slate-50 border border-gray-300 rounded-lg w-full focus:ring-2 ring-theme outline-none transition leading-relaxed text-sm" required></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col md:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeEditModal()" class="w-full md:w-auto px-5 py-2.5 rounded-lg text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium transition-colors text-center">Batal</button>
                        <button type="submit" name="update" class="w-full md:w-auto px-5 py-2.5 bg-theme text-white rounded-lg font-bold hover-bg-theme transition-colors flex items-center justify-center gap-2 shadow-md">
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

        function openEditModal(id, title, year, content) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_year').value = year;
            document.getElementById('edit_content').value = content;

            const modal = document.getElementById('editModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.add('scale-95');
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