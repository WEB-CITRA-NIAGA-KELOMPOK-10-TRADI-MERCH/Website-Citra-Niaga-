<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */

require_once '../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';

$text_color = !empty($web_setting['text_color']) ? htmlspecialchars($web_setting['text_color']) : '#333333';
$header_text_color = !empty($web_setting['header_text_color']) ? htmlspecialchars($web_setting['header_text_color']) : '#333333';

require_once '../models/HistoryModel.php'; 

$historyModel = new HistoryModel($conn);
$histories = $historyModel->getAllHistory();

require_once 'templates/header.php'; 
?>

<style>
    :root {
        --theme-color: <?= $theme_color ?>;
        --font-custom: '<?= $font_family ?>', sans-serif;
        --theme-color-light: color-mix(in srgb, var(--theme-color) 10%, white);
        --text-color: <?= $text_color ?>;
        --header-text-color: <?= $header_text_color ?>;
    }

    main {
        font-family: var(--font-custom) !important;
    }

    h1, h2, h3, .text-gray-900, .text-\[\#111827\] {
        color: var(--header-text-color) !important;
    }
    
    p, .text-gray-500, .text-gray-600 {
        color: var(--text-color) !important;
    }

    .text-theme { color: var(--theme-color) !important; }
    .bg-theme { background-color: var(--theme-color) !important; }
    .bg-theme-light { background-color: var(--theme-color-light) !important; }
    .border-theme { border-color: var(--theme-color) !important; }

    @media (max-width: 768px) {
        .hero-section { padding-top: 6rem !important; padding-bottom: 4rem !important; }
        .hero-title { font-size: 2.25rem !important; line-height: 1.3 !important; }
        .feature-cards-container { margin-top: -2.5rem !important; margin-bottom: 4rem !important; }
        .feature-card { padding: 1.5rem !important; }
        .timeline-title { font-size: 1.75rem !important; margin-bottom: 3rem !important; }
        .timeline-line { left: 1.5rem !important; } 
        .timeline-dot { left: 1rem !important; margin-top: 1.5rem !important; } 
        .timeline-content { padding-left: 3.5rem !important; }
        .timeline-card { padding: 1.5rem !important; }
    }
</style>

<main class="w-full bg-[#fafafa] pb-24 min-h-screen">
    
    <section class="pt-32 pb-24 text-center relative overflow-hidden hero-section">
        <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiMwMDAwMDAiIGZpbGwtb3BhY2l0eT0iMSIvPjwvc3ZnPg==')] z-0"></div>
        
        <h1 class="font-cinzel text-5xl md:text-6xl font-bold text-gray-900 tracking-widest uppercase relative z-10 fade-in-up drop-shadow-sm hero-title">SEJARAH & PROFIL</h1>
    </section>

    <section class="max-w-5xl mx-auto px-4 -mt-12 relative z-20 mb-32 feature-cards-container">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition feature-card">
                <div class="w-12 h-12 bg-theme-light text-theme rounded-full flex items-center justify-center mb-5 shrink-0">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Lokasi Strategis</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Terletak di jantung Kota Samarinda, menjadi pusat bertemunya ekonomi dan budaya lokal.</p>
            </div>
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition feature-card">
                <div class="w-12 h-12 bg-red-50 text-red-400 rounded-full flex items-center justify-center mb-5 shrink-0">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Penghargaan Aga Khan</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Meraih penghargaan arsitektur dunia pada 1989 berkat keunggulan tata ruang publiknya.</p>
            </div>
            <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col items-center hover:shadow-md transition feature-card">
                <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-full flex items-center justify-center mb-5 shrink-0">
                    <i data-lucide="hammer" class="w-5 h-5"></i>
                </div>
                <h3 class="font-cinzel font-bold text-sm tracking-widest mb-3 uppercase text-gray-900">Dibangun Tahun 1987</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Dirancang oleh arsitek Adhi Moersid untuk menyatukan pedagang kaki lima dan pertokoan.</p>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="font-cinzel text-3xl md:text-4xl font-bold text-[#111827] uppercase tracking-widest timeline-title">Jejak Langkah Citra Niaga</h2>
        </div>

        <div class="relative pl-0 md:pl-0">
            <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-px bg-gray-300 md:-translate-x-1/2 timeline-line"></div>

            <div class="space-y-12 md:space-y-16">
                <?php $i = 0; while($row = mysqli_fetch_assoc($histories)): $i++; ?>
                <div class="relative flex flex-col md:flex-row items-center <?= $i % 2 == 0 ? 'md:flex-row-reverse' : '' ?> fade-in-up">
                    
                    <div class="absolute left-0 md:left-1/2 w-4 h-4 rounded-full border-2 border-theme bg-white z-10 md:transform md:-translate-x-1/2 mt-8 md:mt-0 timeline-dot"></div>
                    
                    <div class="hidden md:block w-1/2"></div>
                    
                    <div class="pl-10 md:pl-0 w-full md:w-1/2 <?= $i % 2 == 0 ? 'md:pr-16' : 'md:pl-16' ?> timeline-content">
                        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center hover:shadow-md transition duration-300 timeline-card">
                            
                            <div class="inline-block px-4 py-1.5 bg-theme-light text-theme text-xs font-bold rounded-full mb-4 md:mb-5 tracking-wider shadow-sm">
                                <?= $row['year'] ?>
                            </div>
                            
                            <h3 class="font-cinzel text-lg md:text-xl font-bold text-gray-900 mb-3 md:mb-4 uppercase tracking-wider"><?= htmlspecialchars($row['title']) ?></h3>
                            
                            <p class="text-gray-500 text-sm leading-relaxed text-justify md:text-center">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
<script src="../assets/js/main.js?v=<?= time() ?>"></script>
<?php require_once 'templates/footer.php'; ?>