<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */

require_once '../models/KiosModel.php'; 

$kiosModel = new KiosModel($conn);
$kios = $kiosModel->getAllKios();

$groupedKios = [];
while($row = mysqli_fetch_assoc($kios)) {
    $type = $row['business_type'];
    if (!isset($groupedKios[$type])) {
        $groupedKios[$type] = [];
    }
    $groupedKios[$type][] = $row;
}

require_once 'templates/header.php'; 
?>

<main class="w-full pt-20 pb-20 bg-[#fafafa] font-plus-jakarta-sans min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
            <h1 class="font-cinzel text-4xl md:text-5xl font-bold text-gray-900 mb-6 uppercase tracking-widest">Direktori Kios</h1>
            <p class="text-lg text-gray-600">Temukan ragam UMKM, kuliner lokal, dan produk kerajinan di kawasan Citra Niaga.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-16 fade-in-up" id="kios-filters">
            <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-[#254794] bg-[#254794] text-white font-bold transition-all shadow-md shadow-blue-900/20" data-filter="all">
                Semua Kategori
            </button>
            <?php foreach(array_keys($groupedKios) as $cat): ?>
                <button class="filter-btn px-6 py-2.5 rounded-full border-2 border-gray-200 bg-white text-gray-600 font-bold hover:border-[#254794] hover:text-[#254794] transition-all" data-filter="<?= htmlspecialchars($cat) ?>">
                    <?= htmlspecialchars($cat) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="kios-container">
            <?php foreach($groupedKios as $businessType => $items): ?>
                <div class="mb-16 category-section transition-all duration-500" data-category="<?= htmlspecialchars($businessType) ?>">
                    
                    <div class="flex items-center gap-4 mb-8 fade-in-up">
                        <h2 class="font-cinzel text-2xl font-bold text-gray-900 uppercase tracking-widest">
                            <?= htmlspecialchars($businessType) ?>
                        </h2>
                        <div class="h-px bg-gray-300 flex-grow"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($items as $item): ?>
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col h-full fade-in-up group">
                            
                            <div class="relative h-56 w-full bg-gray-100 overflow-hidden">
                                <?php
                                    $imagePath = !empty($item['image']) ? $item['image'] : '../assets/img/Gallery_Lainnya/area_pengunjung.png';
                                ?>
                                <img src="<?= htmlspecialchars($imagePath) ?>" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-70"></div>
                                
                                <div class="absolute top-4 right-4 px-4 py-1.5 bg-white/90 backdrop-blur-sm text-[#254794] text-xs font-bold rounded-full shadow-sm uppercase tracking-wider">
                                    <?= htmlspecialchars($item['business_type']) ?>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow relative">
                                <div class="absolute -top-8 right-6 w-12 h-12 bg-[#254794] text-white rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                    <i data-lucide="store" class="w-5 h-5"></i>
                                </div>

                                <h3 class="font-bold text-xl text-gray-900 mb-3 group-hover:text-[#254794] transition-colors pr-10">
                                    <?= htmlspecialchars($item['name']) ?>
                                </h3>
                                
                                <p class="text-gray-500 text-sm mb-6 leading-relaxed flex-grow">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>
                                
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-3 text-sm text-gray-600">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5"><i data-lucide="map-pin" class="w-4 h-4 text-[#254794]"></i></div> 
                                        <span class="font-medium text-gray-800"><?= htmlspecialchars($item['location']) ?></span>
                                    </div>
                                    
                                    <?php if(!empty($item['contact_phone']) && $item['contact_phone'] !== '-'): ?>
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5"><i data-lucide="phone" class="w-4 h-4 text-green-600"></i></div> 
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