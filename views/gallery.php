<?php 
require_once '../config/koneksi.php';

/** @var mysqli $conn */

require_once '../models/GalleryModel.php'; 

$galleryModel = new GalleryModel($conn);
$galleries = $galleryModel->getAllGallery();

$groupedGalleries = [];
while($row = mysqli_fetch_assoc($galleries)) {
    $category = isset($row['category']) && $row['category'] !== '' ? trim($row['category']) : 'Lainnya';
    if (!isset($groupedGalleries[$category])) {
        $groupedGalleries[$category] = [];
    }
    $groupedGalleries[$category][] = $row;
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

<main class="w-full pt-20 pb-20 bg-[#fafafa] font-plus-jakarta-sans min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="text-center max-w-3xl mx-auto mb-10 fade-in-up">
            <h1 class="font-cinzel text-4xl md:text-5xl font-bold text-gray-900 mb-4 uppercase tracking-widest">Gallery</h1>
            <p class="text-lg text-gray-600">Perjalanan visual melintasi sudut-sudut ikonik Citra Niaga.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-16 fade-in-up" id="gallery-filters">
            <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-[#254794] bg-[#254794] text-white font-bold transition-all shadow-md shadow-blue-900/20" data-filter="all">
                Semua Kategori
            </button>
            <?php foreach($finalOrder as $cat): ?>
                <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-gray-200 bg-white text-gray-600 font-bold hover:border-[#254794] hover:text-[#254794] transition-all" data-filter="<?= htmlspecialchars($cat) ?>">
                    <?= htmlspecialchars($cat) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="gallery-container">
            <?php foreach($finalOrder as $categoryName): 
                $items = $groupedGalleries[$categoryName];
            ?>
                <div class="mb-16 category-section transition-all duration-500" data-category="<?= htmlspecialchars($categoryName) ?>">
                    
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="font-cinzel text-2xl font-bold text-gray-900 uppercase tracking-widest"><?= htmlspecialchars($categoryName) ?></h2>
                        <div class="h-px bg-gray-300 flex-grow"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($items as $item): ?>
                        <div class="group cursor-pointer fade-in-up bg-white p-3 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
                            <div class="relative overflow-hidden rounded-2xl aspect-[4/3] mb-4 bg-gray-100">
                                <?php
                                $imagePath = '';
                                if (!empty($item['image_url'])) {
                                    $imagePath = $item['image_url'];
                                } elseif (!empty($item['image'])) {
                                    $imagePath = $item['image'];
                                } elseif (!empty($item['gambar'])) {
                                    $imagePath = $item['gambar'];
                                } elseif (!empty($item['foto'])) {
                                    $imagePath = $item['foto'];
                                }
                                ?>
                                
                                <img src="<?= htmlspecialchars($imagePath) ?>" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" alt="<?= htmlspecialchars($item['title'] ?? '') ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1a2b4d]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                    <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                        <div class="w-10 h-10 rounded-full bg-white text-[#254794] flex items-center justify-center shadow-lg mb-3">
                                            <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-2 pb-2">
                                <h3 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-[#254794] transition-colors"><?= isset($item['title']) ? htmlspecialchars($item['title']) : 'Tanpa Judul' ?></h3>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                    <i data-lucide="tag" class="w-3 h-3"></i> <?= htmlspecialchars($categoryName) ?>
                                </span>
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
</script>

<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>