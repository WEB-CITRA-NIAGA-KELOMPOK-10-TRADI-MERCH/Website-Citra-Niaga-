<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */
require_once '../models/ReviewsModel.php'; 
require_once '../models/SettingsModel.php'; 

$reviewsModel = new ReviewsModel($conn);
$reviewsList = $reviewsModel->getAllReviews(); 

$reviewsArray = [];
while($row = mysqli_fetch_assoc($reviewsList)){
    $reviewsArray[] = $row;
}

$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$site_title  = !empty($web_setting['site_title']) ? htmlspecialchars($web_setting['site_title']) : 'Citra Niaga Samarinda';
$site_desc   = !empty($web_setting['site_desc']) ? htmlspecialchars($web_setting['site_desc']) : 'Pusat UMKM, kuliner, dan budaya di Samarinda.';
$hero_banner = !empty($web_setting['hero_banner']) ? htmlspecialchars($web_setting['hero_banner']) : 'citraniagabackground.png';

require_once 'templates/header.php'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<style>
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .img-zoom { transition: transform 0.5s ease; }
    .group:hover .img-zoom { transform: scale(1.1); }
    [v-cloak] { display: none; }
</style>

<main id="app" v-cloak class="w-100 bg-white font-plus-jakarta-sans">
    
    <section class="position-relative d-flex align-items-center overflow-hidden" style="min-height: 100vh;" @mousemove="handleMouseMove">
        
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0 overflow-hidden">
            <img src="../assets/img/Bangunan/<?= $hero_banner ?>" 
                 onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';"
                 alt="Latar Belakang Citra Niaga" 
                 class="w-100 h-100 object-fit-cover parallax-bg" 
                 :style="{ transform: `scale(1.05) translate(${mouseX * 15}px, ${mouseY * 15}px)` }"
                 style="object-position: 80% center;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(255,255,255,1) 0%, rgba(255,255,255,0.9) 40%, rgba(255,255,255,0) 100%);"></div>
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(255,255,255,1) 0%, rgba(255,255,255,0.2) 30%, rgba(255,255,255,0) 100%);"></div>
        </div>
        
        <div class="container position-relative z-1" style="margin-top: -6rem;">
            <div class="row">
                <div class="col-lg-8 col-md-10 fade-in-up">
                    
                    <div class="animate-float d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4 bg-white/80 backdrop-blur-sm border shadow-sm">
                        <i data-lucide="map-pin" class="text-brand" style="width: 16px; height: 16px; color: <?= $theme_color ?>;"></i> 
                        <span class="fw-bold" style="font-size: 0.85rem; color: #333;">Samarinda, Kalimantan Timur</span>
                    </div>
                    
                    <h1 class="font-cinzel fw-bold text-dark mb-4 text-uppercase" style="font-size: clamp(3rem, 6vw, 4.5rem); letter-spacing: 0.05em; line-height: 1.1;">
                        <?= $site_title ?>
                    </h1>
                    
                    <p class="text-secondary mb-5 fw-medium" style="font-size: 1.15rem; line-height: 1.7; max-width: 600px;">
                        <?= $site_desc ?>
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                        <a href="detail.php" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg hover-lift" style="background-color: <?= $theme_color ?>; border: none;">Pelajari Lebih Lanjut</a>
                        <a href="gallery.php" class="btn btn-light border rounded-pill px-5 py-3 fw-bold shadow-sm text-dark hover-lift">Lihat Galeri</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light position-relative z-1">
        <div class="container py-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3 fade-in-up">
                <div>
                    <p class="fw-bold text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.1em; color: <?= $theme_color ?>;">Sekilas Pandang</p>
                    <h2 class="font-cinzel fw-bold text-dark text-uppercase mb-0" style="font-size: 2.5rem;">Perjalanan Visual</h2>
                </div>
                <a href="gallery.php" class="btn btn-white border rounded-pill px-4 py-2 text-dark fw-bold shadow-sm hover-lift" style="background: white;">Lihat Semua Foto</a>
            </div>

            <transition-group name="list" tag="div" class="row g-4" appear>
                <div class="col-md-4" v-for="(item, index) in glimpses" :key="'glimpse-' + index">
                    <div class="group h-100 p-3 bg-white rounded-4 border-0 hover-lift cursor-pointer d-flex flex-column">
                        <div class="rounded-3 overflow-hidden position-relative mb-4" style="height: 240px;">
                            <img :src="item.image" 
                                 onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';"
                                 class="w-100 h-100 object-fit-cover img-zoom" 
                                 :alt="item.title">
                            <div class="position-absolute top-0 start-0 w-100 h-100 transition-opacity duration-300 opacity-0 group-hover:opacity-100" style="background: linear-gradient(to top, rgba(37, 71, 148, 0.4), transparent);"></div>
                            
                            <div class="position-absolute top-0 end-0 m-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-pill shadow-sm text-dark fw-bold" style="font-size: 0.75rem;">
                                <i data-lucide="camera" class="d-inline-block" style="width:12px; height:12px; margin-right: 4px;"></i> Galeri
                            </div>
                        </div>
                        
                        <div class="mt-auto px-2">
                            <h3 class="font-cinzel fw-bold text-dark text-uppercase mb-2" style="font-size: 1.25rem;">{{ item.title }}</h3>
                            <p class="text-secondary opacity-75 mb-1" style="font-size: 0.95rem; line-height: 1.6;">{{ item.desc }}</p>
                        </div>
                    </div>
                </div>
            </transition-group>
        </div>
    </section>

    <section class="py-5 bg-white position-relative z-1">
        <div class="container py-5">
            <div class="text-center mb-5 mx-auto fade-in-up" style="max-width: 600px;">
                <p class="fw-bold text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.1em; color: <?= $theme_color ?>;">Testimoni</p>
                <h2 class="font-cinzel fw-bold text-dark text-uppercase mb-3" style="font-size: 2.5rem;">Kata Pengunjung</h2>
                <p class="text-secondary" style="font-size: 1.1rem;">Temukan cerita dan pengalaman dari mereka yang telah menjelajahi sudut-sudut semarak Citra Niaga.</p>
            </div>
            
            <transition-group name="list" tag="div" class="row g-4" appear>
                <div class="col-md-4" v-for="(review, index) in limitedReviews" :key="'review-' + index">
                    <div class="card h-100 border-0 bg-light rounded-4 p-4 position-relative hover-lift">
                        <div class="position-absolute" style="top: 24px; right: 24px; color: #e5e7eb;">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>

                        <div class="card-body p-0 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex gap-1 mb-4" style="color: #f59e0b;">
                                    <svg v-for="n in 5" :key="'star-' + index + '-' + n" :class="n <= parseInt(review.rating || 5) ? 'text-warning' : 'text-secondary opacity-25'" style="width:20px; height:20px;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <p class="text-secondary fst-italic mb-4 position-relative z-1" style="font-size: 1rem; line-height: 1.6;">
                                    "{{ review.comment }}"
                                </p>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-auto border-top pt-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; background-color: <?= $theme_color ?>; color: white;">
                                    {{ review.visitor_name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <h6 class="font-cinzel fw-bold mb-1 text-dark text-uppercase" style="font-size: 1rem;">{{ review.visitor_name }}</h6>
                                    <small class="text-secondary opacity-75" style="font-size: 0.85rem;">Pengunjung Terverifikasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition-group>
            
        </div>
    </section>
</main>

<script>
    Vue.createApp({
        data() {
            return {
                mouseX: 0,
                mouseY: 0,
                allReviews: <?php echo json_encode($reviewsArray); ?>,
                glimpses: [
                    { 
                        image: '../assets/img/Gallery/Gambar_Lainnya.png', 
                        title: 'Gerbang Utama Citra Niaga', 
                        desc: 'Gerbang ikonik dengan arsitektur khas Kalimantan yang menyambut setiap pengunjung.' 
                    },
                    { 
                        image: '../assets/img/Gallery_Lainnya/area_pengunjung.jpg', 
                        title: 'Suasana Citra Niaga', 
                        desc: 'Aktivitas pedagang dan pembeli di pagi hari yang penuh interaksi dan semangat komunitas.' 
                    },
                    { 
                        image: '../assets/img/Events/insevent2.png', 
                        title: 'Pusat Seni & Budaya', 
                        desc: 'Ruang terbuka yang sering menjadi wadah bagi penampilan seni tradisional Dayak dan kegiatan lokal lainnya.' 
                    }
                ]
            }
        },
        computed: {
            limitedReviews() {
                return this.allReviews.slice(0, 3);
            }
        },
        methods: {
            handleMouseMove(e) {
                const x = (window.innerWidth / 2 - e.clientX) / window.innerWidth;
                const y = (window.innerHeight / 2 - e.clientY) / window.innerHeight;
                this.mouseX = x;
                this.mouseY = y;
            }
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>

<?php require_once 'templates/footer.php'; ?>