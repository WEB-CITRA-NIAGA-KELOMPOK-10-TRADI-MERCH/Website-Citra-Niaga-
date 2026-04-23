<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */
require_once '../models/GalleryModel.php'; 

$galleryModel = new GalleryModel($conn);
$galleries = $galleryModel->getAllGallery();

$groupedGalleries = [];
if ($galleries) {
    while($row = mysqli_fetch_assoc($galleries)) {
        $category = isset($row['category']) && $row['category'] !== '' ? trim($row['category']) : 'Lainnya';
        if (!isset($groupedGalleries[$category])) {
            $groupedGalleries[$category] = [];
        }
        $groupedGalleries[$category][] = $row;
    }
}

$categories = array_keys($groupedGalleries);
$finalOrder = [];

if (in_array('Event', $categories)) {
    $finalOrder[] = 'Event';
}

foreach ($categories as $cat) {
    if ($cat !== 'Event' && $cat !== 'Lainnya') {
        $finalOrder[] = $cat;
    }
}

if (in_array('Lainnya', $categories)) {
    $finalOrder[] = 'Lainnya';
}

require_once 'templates/header.php'; 
?>

<style>
    .lightbox-overlay {
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
        z-index: 9999;
        cursor: zoom-out;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .lightbox-img {
        max-height: 85vh;
        max-width: 90vw;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        cursor: default;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
</style>

<main class="w-full pt-20 pb-20 bg-[#fafafa] font-plus-jakarta-sans min-h-screen">
    
    <div id="custom-lightbox" class="fixed top-0 left-0 w-full h-full lightbox-overlay flex-col items-center justify-center" style="display: none !important;">
        <button id="close-lightbox" class="absolute top-4 right-4 p-2 bg-transparent border-0 text-white opacity-75 hover:opacity-100 transition-opacity" style="cursor: pointer; z-index: 10000;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
        <img id="lightbox-img" src="" class="lightbox-img rounded-xl object-contain">
        <div class="text-center mt-6 px-4" style="max-width: 800px;" onclick="event.stopPropagation()">
            <h3 id="lightbox-title" class="font-cinzel font-bold text-white text-2xl mb-2 tracking-wider"></h3>
            <p id="lightbox-desc" class="text-white/70 text-base md:text-lg m-0"></p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="text-center max-w-3xl mx-auto mb-10 fade-in-up">
            <h1 class="font-cinzel text-4xl md:text-5xl font-bold text-gray-900 mb-4 uppercase tracking-widest">Galeri</h1>
            <p class="text-lg text-gray-600">Perjalanan visual melintasi sudut-sudut ikonik Citra Niaga.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-16 fade-in-up" id="gallery-filters">
            <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-[#254794] bg-[#254794] text-white font-bold transition-all shadow-md shadow-blue-900/20" data-filter="all">
                Semua Kategori
            </button>
            <?php foreach($finalOrder as $cat): ?>
                <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-gray-200 bg-white text-gray-600 font-bold hover:border-[#254794] hover:text-[#254794] transition-all" data-filter="<?= htmlspecialchars($cat) ?>">
                    <?= str_replace('_', ' ', htmlspecialchars($cat)) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="gallery-container">
            <?php foreach($finalOrder as $categoryName): 
                $items = $groupedGalleries[$categoryName] ?? [];
            ?>
                <div class="mb-16 category-section transition-all duration-500" data-category="<?= htmlspecialchars($categoryName) ?>">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="font-cinzel text-2xl font-bold text-gray-900 uppercase tracking-widest"><?= str_replace('_', ' ', htmlspecialchars($categoryName)) ?></h2>
                        <div class="h-px bg-gray-300 flex-grow"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($items as $item): ?>
                        <div class="group cursor-zoom-in fade-in-up bg-white p-3 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300"
                             onclick="openBox('<?= htmlspecialchars($item['image'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($item['title'] ?? 'Tanpa Judul', ENT_QUOTES) ?>', '<?= htmlspecialchars($item['description'] ?? '', ENT_QUOTES) ?>')">
                            
                            <div class="relative overflow-hidden rounded-2xl aspect-[4/3] mb-4 bg-gray-100">
                                <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" 
                                     onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" 
                                     alt="<?= htmlspecialchars($item['title'] ?? '') ?>" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1a2b4d]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                    <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                        <div class="w-10 h-10 rounded-full bg-white text-[#254794] flex items-center justify-center shadow-lg mb-3">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-2 pb-2">
                                <h3 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-[#254794] transition-colors"><?= isset($item['title']) ? htmlspecialchars($item['title']) : 'Tanpa Judul' ?></h3>
                                <p class="text-sm text-gray-500 line-clamp-2"><?= isset($item['description']) ? htmlspecialchars($item['description']) : '' ?></p>
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
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const filterBtns = document.querySelectorAll('.filter-btn');
        const sections = document.querySelectorAll('.category-section');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-[#254794]', 'text-white', 'border-[#254794]', 'shadow-md', 'shadow-blue-900/20');
                    b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });
                
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                btn.classList.add('bg-[#254794]', 'text-white', 'border-[#254794]', 'shadow-md', 'shadow-blue-900/20');

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