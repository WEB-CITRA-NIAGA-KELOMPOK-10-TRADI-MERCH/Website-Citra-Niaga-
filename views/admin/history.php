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

require_once '../../models/HistoryModel.php';

$historyModel = new HistoryModel($conn);
$histories = $historyModel->getAllHistory();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Sejarah - Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">
    
    <aside class="w-64 bg-[#1a2b4d] text-white flex flex-col shrink-0">
        <div class="p-6 border-b border-white/10 flex items-center gap-3">
            <div class="p-2 bg-white/10 rounded-lg"><i data-lucide="map-pin"></i></div>
            <div><h1 class="font-bold tracking-wider">CITRA NIAGA</h1><p class="text-xs text-gray-400">Admin Portal</p></div>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard</a>
            <a href="gallery.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="image" class="w-5 h-5"></i> Kelola Galeri</a>
            <a href="history.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-600 text-white"><i data-lucide="book-open" class="w-5 h-5"></i> Kelola Sejarah</a>
            <a href="kios.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="store" class="w-5 h-5"></i> Kelola Kios</a>
            <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="message-square" class="w-5 h-5"></i> Kelola Review</a>
            
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="settings" class="w-5 h-5"></i> Pengaturan Web</a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <a href="../../controllers/AuthController.php?logout=true" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-white/5"><i data-lucide="log-out" class="w-5 h-5"></i> Logout</a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto relative">
        <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800">Kelola Sejarah & Profil</h2>
        </header>
        
        <div class="p-8 max-w-6xl mx-auto">
            
            <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2 border border-green-200">
                <i data-lucide="check-circle" class="w-5 h-5"></i> Aksi berhasil dilakukan!
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="flex items-center gap-2 mb-4 border-b pb-3">
                    <i data-lucide="plus-circle" class="text-blue-600"></i>
                    <h3 class="font-bold text-gray-800">Tambah Data Sejarah Baru</h3>
                </div>
                <form action="../../controllers/HistoryController.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Judul Peristiwa</label>
                        <input type="text" name="title" placeholder="Misal: Aga Khan Award" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun / Periode</label>
                        <input type="text" name="year" placeholder="Misal: 1989" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Isi / Cerita Sejarah</label>
                        <textarea name="content" placeholder="Ceritakan detail sejarah di sini..." class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="add" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 w-fit flex items-center gap-2 transition-colors">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Sejarah
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 font-semibold text-gray-600 text-sm w-24">Tahun</th>
                            <th class="p-4 font-semibold text-gray-600 text-sm w-1/4">Judul</th>
                            <th class="p-4 font-semibold text-gray-600 text-sm">Konten Singkat</th>
                            <th class="p-4 font-semibold text-gray-600 text-sm text-right w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($histories) > 0):
                            while($row = mysqli_fetch_assoc($histories)): 
                        ?>
                        <tr class="border-b hover:bg-gray-50/50 transition-colors">
                            <td class="p-4 font-bold text-blue-600"><?= htmlspecialchars($row['year'] ?? '') ?></td>
                            <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($row['title'] ?? '') ?></td>
                            <td class="p-4 text-gray-500 text-sm">
                                <?= htmlspecialchars(substr($row['content'] ?? '', 0, 100)) ?><?= strlen($row['content'] ?? '') > 100 ? '...' : '' ?>
                            </td>
                            <td class="p-4 text-right">
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
                                    <p>Belum ada data sejarah. Silakan tambahkan data baru.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl scale-95 transition-transform duration-300" id="modalContent">
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h3 class="font-bold text-lg text-gray-800">Edit Data Sejarah</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <form action="../../controllers/HistoryController.php" method="POST" class="p-6">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Judul Peristiwa</label>
                        <input type="text" name="title" id="edit_title" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Tahun / Periode</label>
                        <input type="text" name="year" id="edit_year" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Isi / Cerita Sejarah</label>
                        <textarea name="content" id="edit_content" rows="6" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none leading-relaxed" required></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition-colors">Batal</button>
                    <button type="submit" name="update" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

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
    </script>
</body>
</html>