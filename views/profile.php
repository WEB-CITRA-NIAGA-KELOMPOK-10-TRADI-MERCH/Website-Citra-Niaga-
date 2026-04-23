<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */

require_once '../models/HistoryModel.php'; 

$historyModel = new HistoryModel($conn);
$histories = $historyModel->getAllHistory();

require_once 'templates/header.php'; 
?>

<main class="w-full bg-[#fafafa] pb-24 font-plus-jakarta-sans">
    
    <section class="pt-32 pb-24 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiMwMDAwMDAiIGZpbGwtb3BhY2l0eT0iMSIvPjwvc3ZnPg==')] z-0"></div>
        
        <h1 class="font-cinzel text-5xl md:text-6xl font-bold text-gray-900 tracking-widest uppercase relative z-10 fade-in-up drop-shadow-sm">SEJARAH & PROFIL</h1>
    </section>

    <section class="max-w-5xl mx-auto px-4 -mt-12 relative z-20 mb-32">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-50 text-brand-blue rounded-full flex items-center justify-center mb-5">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Lokasi Strategis</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Terletak di jantung Kota Samarinda, menjadi pusat bertemunya ekonomi dan budaya lokal.</p>
            </div>
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-50 text-red-400 rounded-full flex items-center justify-center mb-5">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Penghargaan Aga Khan</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Meraih penghargaan arsitektur dunia pada 1989 berkat keunggulan tata ruang publiknya.</p>
            </div>
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-full flex items-center justify-center mb-5">
                    <i data-lucide="hammer" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Dibangun Tahun 1987</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Dirancang oleh arsitek Adhi Moersid untuk menyatukan pedagang kaki lima dan pertokoan.</p>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-[#111827] uppercase tracking-widest">Jejak Langkah Citra Niaga</h2>
        </div>

        <div class="relative pl-8 md:pl-0">
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-px bg-gray-300 md:-translate-x-1/2"></div>

            <div class="space-y-16">
                <?php $i = 0; while($row = mysqli_fetch_assoc($histories)): $i++; ?>
                <div class="relative flex flex-col md:flex-row items-center <?= $i % 2 == 0 ? 'md:flex-row-reverse' : '' ?> fade-in-up">
                    
                    <div class="absolute left-0 md:left-1/2 w-4 h-4 rounded-full border-2 border-[#1e3a8a] bg-white z-10 md:transform md:-translate-x-1/2 mt-8 md:mt-0"></div>
                    
                    <div class="hidden md:block w-1/2"></div>
                    
                    <div class="pl-10 md:pl-0 w-full md:w-1/2 <?= $i % 2 == 0 ? 'md:pr-16' : 'md:pl-16' ?>">
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center hover:shadow-md transition duration-300">
                            
                            <div class="inline-block px-4 py-1.5 bg-[#eef2ff] text-[#1e3a8a] text-xs font-bold rounded-full mb-5 tracking-wider">
                                <?= $row['year'] ?>
                            </div>
                            
                            <h3 class="font-cinzel text-xl font-bold text-gray-900 mb-4 uppercase tracking-wider"><?= htmlspecialchars($row['title']) ?></h3>
                            
                            <p class="text-gray-500 text-sm leading-relaxed">
                                <?= htmlspecialchars($row['content']) ?>
                            </p>

                        </div>
                    </div>

                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>

<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>