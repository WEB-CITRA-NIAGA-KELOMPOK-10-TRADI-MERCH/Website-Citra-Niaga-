<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */

require_once '../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';

require_once '../models/KiosModel.php'; 
$kiosModel = new KiosModel($conn);
$kios = $kiosModel->getAllKios();

$groupedKios = [];
if ($kios && mysqli_num_rows($kios) > 0) {
    while($row = mysqli_fetch_assoc($kios)) {
        $type = !empty($row['business_type']) ? trim($row['business_type']) : 'Lainnya';
        if (!isset($groupedKios[$type])) {
            $groupedKios[$type] = [];
        }
        $groupedKios[$type][] = $row;
    }
}

require_once 'templates/header.php'; 
?>

<style>
    :root {
        --theme-color: <?= $theme_color ?>;
        --font-custom: '<?= $font_family ?>', sans-serif;
    }

    main {
        font-family: var(--font-custom) !important;
    }

    .text-theme { color: var(--theme-color) !important; }
    .bg-theme { background-color: var(--theme-color) !important; }
    .border-theme { border-color: var(--theme-color) !important; }
    
    .active-theme-btn {
        background-color: var(--theme-color) !important;
        border-color: var(--theme-color) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.15);
    }
    .inactive-theme-btn {
        background-color: #ffffff !important;
        border-color: #e5e7eb !important;
        color: #4b5563 !important;
    }
    .inactive-theme-btn:hover {
        border-color: var(--theme-color) !important;
        color: var(--theme-color) !important;
    }

    .group:hover .group-hover\:text-theme { color: var(--theme-color) !important; }

    #custom-lightbox {
        display: none; 
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .lightbox-img {
        max-height: 85vh;
        max-width: 90vw;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }

    /* ======================================================== */
    /* --- FIX RESPONSIVE KHUSUS LAYAR HP (MOBILE DEVICES) ---  */
    /* ======================================================== */
    @media (max-width: 768px) {
        /* Kurangi padding utama biar layar HP gak kerasa melompong */
        .main-container { padding-top: 6rem !important; padding-bottom: 3rem !important; }
        
        /* Judul utama & deskripsi disesuaikan ukurannya */
        .hero-title { font-size: 2.2rem !important; margin-bottom: 0.5rem !important; }
        .hero-desc { font-size: 1rem !important; padding: 0 1rem; margin-bottom: 2rem !important;}
        
        /* Tombol filter diperkecil biar muat banyak di layar HP */
        .filter-btn { 
            padding: 8px 16px !important; 
            font-size: 0.85rem !important; 
            margin-bottom: 0.25rem;
        }
        #kios-filters { margin-bottom: 2rem !important; }
        
        /* Spasi antar kategori diperkecil */
        .category-section { margin-bottom: 3rem !important; }
        .category-header { margin-bottom: 1.5rem !important; }
        .category-title { font-size: 1.25rem !important; }

        /* Teks lightbox disesuaikan buat HP */
        #lightbox-title { font-size: 1.25rem !important; }
        #lightbox-desc { font-size: 0.85rem !important; }
        
        /* Tombol close di lightbox dibikin lebih gampang dipencet jempol */
        #close-lightbox { 
            top: 1rem !important; 
            right: 1rem !important; 
            padding: 10px !important; 
            background-color: rgba(0,0,0,0.5) !important; 
            border-radius: 50%; 
        }
    }
</style>

<main class="w-full pt-20 pb-20 bg-[#fafafa] min-h-screen main-container">
    
    <div id="custom-lightbox" class="fixed top-0 left-0 w-full h-full flex-col items-center justify-center">
        <button id="close-lightbox" class="absolute top-4 right-4 p-2 bg-transparent border-0 text-white opacity-75 hover:opacity-100 transition-opacity" style="cursor: pointer; z-index: 10000;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
        <img id="lightbox-img" src="" class="lightbox-img rounded-xl object-contain">
        <div class="text-center mt-4 md:mt-6 px-4" style="max-width: 800px;" onclick="event.stopPropagation()">
            <h3 id="lightbox-title" class="font-cinzel font-bold text-white text-2xl mb-2 tracking-wider"></h3>
            <p id="lightbox-desc" class="text-white/70 text-base md:text-lg m-0"></p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 md:mt-10">
        
        <div class="text-center max-w-3xl mx-auto mb-10 md:mb-16 fade-in-up">
            <h1 class="font-cinzel text-4xl md:text-5xl font-bold text-gray-900 mb-4 md:mb-6 uppercase tracking-widest hero-title">Direktori Kios</h1>
            <p class="text-lg text-gray-600 hero-desc">Temukan ragam UMKM, kuliner lokal, dan produk kerajinan di kawasan Citra Niaga.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-10 md:mb-16 fade-in-up" id="kios-filters">
            <button class="filter-btn px-6 py-2.5 rounded-full border-2 active-theme-btn font-bold transition-all" data-filter="all">
                Semua Kategori
            </button>
            <?php foreach(array_keys($groupedKios) as $cat): ?>
                <button class="filter-btn px-6 py-2.5 rounded-full border-2 inactive-theme-btn font-bold transition-all" data-filter="<?= htmlspecialchars($cat) ?>">
                    <?= ucwords(str_replace('_', ' ', htmlspecialchars($cat))) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="kios-container">
            <?php foreach($groupedKios as $businessType => $items): ?>
                <div class="mb-12 md:mb-16 category-section transition-all duration-500" data-category="<?= htmlspecialchars($businessType) ?>">
                    
                    <div class="flex items-center gap-4 mb-6 md:mb-8 fade-in-up category-header">
                        <h2 class="font-cinzel text-xl md:text-2xl font-bold text-gray-900 uppercase tracking-widest category-title">
                            <?= ucwords(str_replace('_', ' ', htmlspecialchars($businessType))) ?>
                        </h2>
                        <div class="h-px bg-gray-300 flex-grow"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        <?php foreach($items as $item): ?>
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col h-full fade-in-up group hover-lift">
                            
                            <?php $imagePath = !empty($item['image']) ? $item['image'] : '../assets/img/default-placeholder.png'; ?>
                            <div class="relative h-56 w-full bg-gray-100 overflow-hidden cursor-pointer" onclick="openBox('<?= htmlspecialchars($imagePath, ENT_QUOTES) ?>', '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['description'], ENT_QUOTES) ?>')">
                                <img src="<?= htmlspecialchars($imagePath) ?>" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <div class="absolute top-4 right-4 px-4 py-1.5 bg-white/90 backdrop-blur-sm text-theme text-xs font-bold rounded-full shadow-sm uppercase tracking-wider z-10">
                                    <?= ucwords(str_replace('_', ' ', htmlspecialchars($item['business_type']))) ?>
                                </div>

                                <div class="absolute bottom-4 left-4 w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 z-10 text-theme">
                                    <i data-lucide="maximize-2" class="w-5 h-5"></i>
                                </div>
                            </div>

                            <div class="p-5 md:p-6 flex flex-col flex-grow relative">
                                <div class="absolute -top-8 right-6 w-12 h-12 bg-theme text-white rounded-full flex items-center justify-center shadow-lg border-4 border-white z-20">
                                    <i data-lucide="store" class="w-5 h-5"></i>
                                </div>

                                <h3 class="font-bold text-lg md:text-xl text-gray-900 mb-3 group-hover:text-theme transition-colors pr-10">
                                    <?= htmlspecialchars($item['name']) ?>
                                </h3>
                                
                                <p class="text-gray-500 text-sm mb-6 leading-relaxed flex-grow">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>
                                
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-3 text-sm text-gray-600">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 shrink-0"><i data-lucide="map-pin" class="w-4 h-4 text-theme"></i></div> 
                                        <span class="font-medium text-gray-800"><?= htmlspecialchars($item['location']) ?></span>
                                    </div>
                                    
                                    <?php if(!empty($item['contact_phone']) && $item['contact_phone'] !== '-'): ?>
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 shrink-0"><i data-lucide="phone" class="w-4 h-4 text-green-600"></i></div> 
                                        <span class="font-medium text-gray-800"><?= htmlspecialchars($item['contact_phone']) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }

        const filterBtns = document.querySelectorAll('.filter-btn');
        const sections = document.querySelectorAll('.category-section');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                
                filterBtns.forEach(b => {
                    b.classList.remove('active-theme-btn');
                    b.classList.add('inactive-theme-btn');
                });
                
                btn.classList.remove('inactive-theme-btn');
                btn.classList.add('active-theme-btn');

                const filterValue = btn.getAttribute('data-filter');

                sections.forEach(section => {
                    if (filterValue === 'all' || section.getAttribute('data-category') === filterValue) {
                        section.style.display = 'block';
                        section.style.opacity = '0';
                        setTimeout(() => { section.style.opacity = '1'; }, 50);
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });
    });

    const lightbox = document.getElementById('custom-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxTitle = document.getElementById('lightbox-title');
    const lightboxDesc = document.getElementById('lightbox-desc');

    function openBox(img, title, desc) {
        lightboxImg.src = img;
        lightboxTitle.textContent = title;
        lightboxDesc.textContent = desc;
        
        lightbox.style.setProperty('display', 'flex', 'important');
        setTimeout(() => {
            lightbox.style.opacity = '1';
            lightboxImg.style.transform = 'scale(1)';
        }, 10);
        document.body.style.overflow = 'hidden'; 
    }

    function closeBox() {
        lightbox.style.opacity = '0';
        lightboxImg.style.transform = 'scale(0.9)';
        setTimeout(() => {
            lightbox.style.setProperty('display', 'none', 'important');
            document.body.style.overflow = ''; 
        }, 300);
    }

    document.getElementById('close-lightbox').addEventListener('click', closeBox);
    lightbox.addEventListener('click', function(e) {
        if(e.target === lightbox) { closeBox(); }
    });
</script>

<script src="../assets/js/main.js?v=<?= time() ?>"></script>
<?php require_once 'templates/footer.php'; ?>