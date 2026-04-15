<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../../config/koneksi.php';
/** @var mysqli $conn */ 

require_once '../../models/KiosModel.php';

$kiosModel = new KiosModel($conn);
$kiosList = $kiosModel->getAllKios();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kios - Citra Niaga</title>
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
            <a href="history.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="book-open" class="w-5 h-5"></i> Kelola Sejarah</a>
            <a href="kios.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-600 text-white"><i data-lucide="store" class="w-5 h-5"></i> Kelola Kios</a>
            <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="message-square" class="w-5 h-5"></i> Kelola Review</a>
            
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white"><i data-lucide="settings" class="w-5 h-5"></i> Pengaturan Web</a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <a href="../../controllers/AuthController.php?logout=true" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-white/5"><i data-lucide="log-out" class="w-5 h-5"></i> Logout</a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto relative">
        <header class="bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10 shadow-sm">
            <h2 class="text-xl font-bold text-gray-800">Daftar Kios & UMKM</h2>
        </header>
        
        <div class="p-8 max-w-6xl mx-auto">
            
            <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2 border border-green-200">
                <i data-lucide="check-circle" class="w-5 h-5"></i> Data Kios berhasil diperbarui!
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="flex items-center gap-2 mb-4 border-b pb-3">
                    <i data-lucide="plus-circle" class="text-blue-600"></i>
                    <h3 class="font-bold text-gray-800">Tambah Kios Baru</h3>
                </div>
                <form action="../../controllers/KiosController.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Nama Kios</label>
                        <input type="text" name="name" placeholder="Misal: Optik Jaya" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                    
                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Jenis Bisnis</label>
                        <select name="business_type" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="">Pilih Jenis Bisnis...</option>
                            <option value="Kerajinan & Suvenir">Kerajinan & Suvenir</option>
                            <option value="Pakaian & Fashion">Pakaian & Fashion</option>
                            <option value="Kuliner">Kuliner</option>
                            <option value="Batu Permata & Aksesoris">Batu Permata & Aksesoris</option>
                            <option value="Jasa">Jasa</option>
                            <option value="Toko Umum & Retail">Toko Umum & Retail</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Lokasi (Blok)</label>
                        <input type="text" name="location" placeholder="Misal: Blok A No. 12" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Nomor Telepon / WA</label>
                        <input type="text" name="contact_phone" placeholder="Misal: 0812-3456-7890" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">URL Foto Kios (Opsional)</label>
                        <input type="text" name="image" placeholder="Misal: ../assets/img/kios/optik.jpg" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-600 mb-1 block">Deskripsi Kios</label>
                        <textarea name="description" placeholder="Menjual kacamata, softlens, dll..." rows="3" class="p-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>

                    <button type="submit" name="tambah" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 w-fit flex items-center gap-2 transition-colors">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Kios
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm