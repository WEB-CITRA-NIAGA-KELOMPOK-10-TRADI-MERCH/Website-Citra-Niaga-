<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */
require_once '../models/SettingsModel.php'; 

$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';
$site_title  = !empty($web_setting['site_title']) ? htmlspecialchars($web_setting['site_title']) : 'Citra Niaga Samarinda';
$site_desc   = !empty($web_setting['site_desc']) ? htmlspecialchars($web_setting['site_desc']) : 'Pusat UMKM, kuliner, dan budaya di Samarinda.';
$hero_banner = !empty($web_setting['hero_banner']) ? htmlspecialchars($web_setting['hero_banner']) : 'citraniagabackground.png';

$text_color = !empty($web_setting['text_color']) ? htmlspecialchars($web_setting['text_color']) : '#64748b';
$header_text_color = !empty($web_setting['header_text_color']) ? htmlspecialchars($web_setting['header_text_color']) : '#0f172a';

$limitedReviews = [];
$q_rev = mysqli_query($conn, "SELECT * FROM reviews WHERE is_pinned = 1 ORDER BY created_at DESC LIMIT 3");
if($q_rev) {
    while($row = mysqli_fetch_assoc($q_rev)){
        $limitedReviews[] = $row;
    }
}

$glimpses = [];
$q_glimpse = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC LIMIT 3");
if($q_glimpse) {
    while($row = mysqli_fetch_assoc($q_glimpse)) {
        $glimpses[] = [
            'image' => $row['image'],
            'title' => $row['title'] ?? 'Kawasan Citra Niaga',
            'desc'  => $row['description'] ?? 'Eksplorasi keindahan kawasan Citra Niaga Samarinda.'
        ];
    }
}

$history_text_1 = "Dibangun pada tahun 1987, Citra Niaga bukanlah sekadar pusat perbelanjaan biasa. Kawasan ini merupakan wujud keberhasilan peremajaan tata kota yang menyatukan ruang publik, pedagang kaki lima, dan pertokoan dalam satu ekosistem yang harmonis.";
$history_text_2 = "Kejeniusan desain arsitekturnya yang merangkul budaya lokal Dayak dan Kutai berhasil membawa Citra Niaga meraih penghargaan arsitektur paling bergengsi di dunia Islam, yakni Aga Khan Award for Architecture, pada tahun 1989.";

$q_hist = mysqli_query($conn, "SELECT content FROM history ORDER BY id ASC LIMIT 2");
$hist_arr = [];
if($q_hist) {
    while($r = mysqli_fetch_assoc($q_hist)) {
        $hist_arr[] = $r['content'];
    }
}
if(count($hist_arr) >= 2) {
    $history_text_1 = $hist_arr[0];
    $history_text_2 = $hist_arr[1];
} elseif(count($hist_arr) == 1) {
    $history_text_1 = $hist_arr[0];
}

require_once 'templates/header.php'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    if (sessionStorage.getItem("citraNiagaLoaded")) {
        document.documentElement.classList.add("skip-preloader");
    }
</script>

<style>
    :root {
        --theme-color: <?= $theme_color ?>;
        --text-color: <?= $text_color ?>;
        --header-text-color: <?= $header_text_color ?>;
        --font-custom: '<?= $font_family ?>', sans-serif;
    }

    body, main { font-family: var(--font-custom) !important; }
    
    p, .text-secondary, .text-muted, body { color: var(--text-color) !important; }
    h1, h2, h3, h4, h5, h6, .text-dark, .font-cinzel { color: var(--header-text-color) !important; }
    
    .text-theme { color: var(--theme-color) !important; }
    .bg-theme { background-color: var(--theme-color) !important; }

    .text-white, .btn.text-white, .text-white-force { color: #ffffff !important; }
    .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; }
    .preloader-title { color: var(--header-text-color) !important; }
    .preloader-subtitle { color: var(--theme-color) !important; }

    img { -webkit-user-drag: none; -khtml-user-drag: none; -moz-user-drag: none; -o-user-drag: none; user-select: none; }
    [v-cloak] { display: none !important; }

    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }

    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; }
    .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    
    .img-zoom { transition: transform 0.5s ease; }
    .group:hover .img-zoom { transform: scale(1.1); }
    
    .backdrop-blur-sm { backdrop-filter: blur(8px); background-color: rgba(255,255,255,0.85); }
    .transition-opacity { transition: opacity 0.3s ease; }
    .opacity-0 { opacity: 0; }
    .group:hover .group-hover\:opacity-100 { opacity: 1; }

    .lightbox-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.95); backdrop-filter: blur(10px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .lightbox-img { max-height: 85vh; max-width: 90vw; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border-radius: 12px; object-fit: contain; }
    
    .fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
    .fade-enter-from, .fade-leave-to { opacity: 0; }
    .zoom-enter-active, .zoom-leave-active { transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .zoom-enter-from, .zoom-leave-to { transform: scale(0.9); }

    .body-locked { overflow: hidden !important; }

    .fixed-preloader {
        position: fixed; inset: 0; z-index: 999999;
        background: radial-gradient(circle at center, #ffffff 0%, #f1f5f9 100%);
        display: flex; align-items: center; justify-content: center;
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s, transform 0.8s ease;
    }

    html.skip-preloader .fixed-preloader {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        animation: none !important;
    }

    .preloader-logo {
        width: 160px; height: auto; margin: 0 auto; transform-style: preserve-3d;
        filter: drop-shadow(0 15px 25px rgba(37, 71, 148, 0.15));
        animation: float3DPremium 3s ease-in-out infinite;
    }

    @keyframes float3DPremium {
        0% { transform: translateY(0px) scale(1) rotateY(0deg); }
        50% { transform: translateY(-12px) scale(1.05) rotateY(10deg); filter: drop-shadow(0 25px 35px rgba(37, 71, 148, 0.25)); }
        100% { transform: translateY(0px) scale(1) rotateY(0deg); }
    }

    .preloader-title {
        letter-spacing: 8px; font-weight: 900; font-size: 1.8rem; margin-bottom: 0; margin-top: 1.5rem;
        opacity: 0; transform: translateY(15px); animation: fadeUpPremium 0.8s ease 0.3s forwards;
    }
    
    .preloader-subtitle {
        letter-spacing: 14px; font-size: 0.85rem; text-transform: uppercase; font-weight: 700;
        opacity: 0; transform: translateY(15px); margin-top: 8px; animation: fadeUpPremium 0.8s ease 0.5s forwards;
    }

    @keyframes fadeUpPremium { to { opacity: 1; transform: translateY(0); } }

    .progress-container {
        width: 240px; height: 4px; margin: 35px auto 0; background: rgba(37, 71, 148, 0.1); border-radius: 10px; overflow: hidden;
        opacity: 0; animation: fadeUpPremium 0.8s ease 0.7s forwards; position: relative;
    }
    
    .progress-bar-fill {
        width: 0%; height: 100%; background: linear-gradient(90deg, <?= $theme_color ?>, #3b82f6);
        box-shadow: 0 0 12px rgba(59, 130, 246, 0.5); border-radius: 10px;
        animation: loadBarPremium 2s cubic-bezier(0.77, 0, 0.175, 1) 0.8s forwards;
    }

    @keyframes loadBarPremium { 0% { width: 0%; } 40% { width: 60%; } 100% { width: 100%; } }

    .fixed-preloader.hide-preloader { opacity: 0; visibility: hidden; pointer-events: none; transform: scale(1.03); }

    .hero-content-box { margin-top: -6rem; }
    .sejarah-img-box { height: 400px; }
    .hero-title-text { font-size: clamp(3rem, 6vw, 4.5rem); }
    
    @media (max-width: 768px) {
        .hero-content-box { margin-top: 0 !important; padding-top: 3rem !important; }
        .sejarah-img-box { height: 260px !important; }
        .preloader-title { font-size: 1.5rem !important; letter-spacing: 4px !important; }
        .preloader-subtitle { font-size: 0.75rem !important; letter-spacing: 8px !important; }
        .cta-buttons-container { flex-direction: column !important; width: 100%; }
        .cta-buttons-container a { width: 100%; justify-content: center; margin-bottom: 10px; }
        .hero-title-text { font-size: 2.2rem !important; line-height: 1.2; text-align: center; }
        .hero-desc-text { text-align: center; font-size: 1rem !important; margin-left: auto; margin-right: auto; }
        .hero-location-badge { margin: 0 auto 1.5rem auto !important; display: flex !important; width: fit-content; }
    }
</style>

<div id="cinematic-preloader" class="fixed-preloader">
    <div class="preloader-content text-center">
        <img src="../assets/img/Logo/logo_citra_niaga.png" alt="Logo Citra Niaga" class="preloader-logo">
        <h2 class="font-cinzel preloader-title">CITRA NIAGA</h2>
        <p class="preloader-subtitle">SAMARINDA</p>
        <div class="progress-container">
            <div class="progress-bar-fill"></div>
        </div>
    </div>
</div>

<script>
    if (!sessionStorage.getItem("citraNiagaLoaded")) {
        document.body.classList.add("body-locked");
    }

    document.addEventListener("DOMContentLoaded", () => {
        const preloader = document.getElementById("cinematic-preloader");
        
        if (!sessionStorage.getItem("citraNiagaLoaded")) {
            setTimeout(() => {
                preloader.classList.add("hide-preloader");
                document.body.classList.remove("body-locked");
                sessionStorage.setItem("citraNiagaLoaded", "true");
                setTimeout(() => { preloader.style.display = "none"; }, 800);
            }, 3000); 
        }
    });
</script>

<main id="app" v-cloak class="w-100 bg-white">
    
    <transition name="fade">
        <div v-if="lightbox.show" class="lightbox-overlay" @click="closeLightbox">
            <button @click.stop="closeLightbox" class="position-absolute top-0 end-0 m-4 p-2 bg-transparent border-0 text-white opacity-75 hover-lift" style="z-index: 10000;">
                <i data-lucide="x" style="width: 36px; height: 36px;"></i>
            </button>
            <transition name="zoom" appear>
                <img v-if="lightbox.show" :src="lightbox.img" class="lightbox-img" @click.stop>
            </transition>
            <div class="text-center mt-4 px-3" style="max-width: 800px;" @click.stop>
                <h3 class="font-cinzel fw-bold text-white mb-2 tracking-wider">{{ lightbox.title }}</h3>
                <p class="text-white-50 mb-0" style="font-size: 1.1rem;">{{ lightbox.desc }}</p>
            </div>
        </div>
    </transition>

    <section class="position-relative d-flex align-items-center overflow-hidden" style="min-height: 100vh;" @mousemove="handleMouseMove" @mouseleave="resetMouse">
        
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0 overflow-hidden bg-dark">
            <img src="../assets/img/Gallery/Area_Bangunan/<?= $hero_banner ?>" 
                 onerror="this.onerror=null;this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';"
                 alt="Latar Belakang Citra Niaga" 
                 class="w-100 h-100 object-fit-cover parallax-bg" 
                 :style="{ transform: `scale(1.05) translate(${mouseX * 20}px, ${mouseY * 20}px)`, transition: isMouseLeft ? 'transform 0.5s ease-out' : 'none' }"
                 style="object-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(255,255,255,1) 0%, rgba(255,255,255,0.9) 50%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
        </div>
        
        <div class="container position-relative z-1 hero-content-box" style="pointer-events: none;">
            <div class="row">
                <div class="col-lg-8 col-md-10" style="pointer-events: auto;">
                    
                    <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4 backdrop-blur-sm border shadow-sm fade-in-up hero-location-badge">
                        <i data-lucide="map-pin" style="width: 16px; height: 16px; color: <?= $theme_color ?>;"></i> 
                        <span class="fw-bold" style="font-size: 0.85rem; color: var(--header-text-color);">Samarinda, Kalimantan Timur</span>
                    </div>
                    
                    <h1 class="font-cinzel fw-bold text-dark mb-4 text-uppercase fade-in-up hero-title-text" style="letter-spacing: 0.05em; transition-delay: 0.1s;">
                        <?= $site_title ?>
                    </h1>
                    
                    <p class="text-secondary mb-5 fw-medium fade-in-up hero-desc-text" style="line-height: 1.7; max-width: 600px; transition-delay: 0.2s;">
                        <?= $site_desc ?>
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4 fade-in-up cta-buttons-container" style="transition-delay: 0.3s;">
                        <a href="detail.php" class="btn rounded-pill px-5 py-3 fw-bold shadow-sm hover-lift text-white" style="background-color: <?= $theme_color ?>; border: none;">Pelajari Lebih Lanjut</a>
                        <a href="gallery.php" class="btn btn-light border rounded-pill px-5 py-3 fw-bold shadow-sm text-dark hover-lift">Lihat Galeri</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white position-relative z-1 border-bottom">
        <div class="container py-4 py-md-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 fade-in-up">
                    <div class="position-relative rounded-4 overflow-hidden shadow-lg group hover-lift sejarah-img-box" 
                         @click="openLightbox('../assets/img/Gallery/Area_Bangunan/Budaya.png', 'Sejarah Citra Niaga', 'Pusat pelestarian budaya dan sejarah kota Samarinda.')">
                        
                        <img src="../assets/img/Gallery/Area_Bangunan/Budaya.png" onerror="this.onerror=null;this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';" class="w-100 h-100 object-fit-cover img-zoom" alt="Sejarah Citra Niaga">
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100 transition-opacity opacity-0 group-hover:opacity-100" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
                        
                        <div class="position-absolute bottom-0 start-0 m-4 d-flex align-items-center justify-content-center bg-white rounded-circle shadow transition-opacity opacity-0 group-hover:opacity-100" style="width: 48px; height: 48px; color: <?= $theme_color ?>;">
                            <i data-lucide="maximize-2" style="width: 24px; height: 24px;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 fade-in-up text-center text-md-start" style="transition-delay: 0.1s;">
                    <p class="fw-bold text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.1em; color: <?= $theme_color ?>;">Sejarah Singkat</p>
                    <h2 class="font-cinzel fw-bold text-dark text-uppercase mb-4" style="font-size: 2.2rem;">Jejak Langkah Citra Niaga</h2>
                    <p class="text-secondary mb-4" style="line-height: 1.8; text-align: justify;">
                        <?= htmlspecialchars($history_text_1) ?>
                    </p>
                    <p class="text-secondary mb-5" style="line-height: 1.8; text-align: justify;">
                        <?= htmlspecialchars($history_text_2) ?>
                    </p>
                    <a href="profile.php" class="btn bg-white rounded-pill px-4 py-2 fw-bold hover-lift border shadow-sm w-100 w-md-auto" style="color: <?= $theme_color ?>;">Baca Sejarah Lengkap</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light position-relative z-1">
        <div class="container py-4 py-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-end mb-4 mb-md-5 gap-3 fade-in-up text-center text-md-start">
                <div>
                    <p class="fw-bold text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.1em; color: <?= $theme_color ?>;">Sekilas Pandang</p>
                    <h2 class="font-cinzel fw-bold text-dark text-uppercase mb-0" style="font-size: 2.2rem;">Perjalanan Visual</h2>
                </div>
                <a href="gallery.php" class="btn btn-white border rounded-pill px-4 py-2 text-dark fw-bold shadow-sm hover-lift w-100 w-md-auto" style="background: white;">Lihat Semua Foto</a>
            </div>

            <div class="row g-4 fade-in-up" style="transition-delay: 0.2s;">
                <div class="col-md-4" v-for="(item, index) in glimpses" :key="'glimpse-' + index">
                    <div class="group h-100 p-3 bg-white rounded-4 border-0 hover-lift d-flex flex-column shadow-sm" @click="openLightbox(item.image, item.title, item.desc)">
                        
                        <div class="rounded-3 overflow-hidden position-relative mb-4 bg-light" style="height: 240px;">
                            <img :src="item.image" 
                                 onerror="this.onerror=null;this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';"
                                 class="w-100 h-100 object-fit-cover img-zoom" 
                                 :alt="item.title">
                            
                            <div class="position-absolute top-0 start-0 w-100 h-100 transition-opacity opacity-0 group-hover:opacity-100" style="background: linear-gradient(to top, rgba(37, 71, 148, 0.4), transparent);"></div>
                            
                            <div class="position-absolute top-0 end-0 m-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-pill shadow-sm text-dark fw-bold d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                <i data-lucide="camera" style="width:12px; height:12px;"></i> Galeri
                            </div>

                            <div class="position-absolute bottom-0 start-0 m-3 d-flex align-items-center justify-content-center bg-white rounded-circle shadow transition-opacity opacity-0 group-hover:opacity-100" style="width: 40px; height: 40px; color: <?= $theme_color ?>;">
                                <i data-lucide="maximize-2" style="width: 18px; height: 18px;"></i>
                            </div>
                        </div>
                        
                        <div class="mt-auto px-2">
                            <h3 class="font-cinzel fw-bold text-dark text-uppercase mb-2 transition-colors" style="font-size: 1.25rem;">{{ item.title }}</h3>
                            <p class="text-secondary opacity-75 mb-1 line-clamp-2" style="font-size: 0.95rem; line-height: 1.6;">{{ item.desc }}</p>
                        </div>
                    </div>
                </div>
                
                <div v-if="glimpses.length === 0" class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada foto galeri untuk ditampilkan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white position-relative z-1">
        <div class="container py-4 py-md-5">
            <div class="text-center mb-4 mb-md-5 mx-auto fade-in-up" style="max-width: 600px;">
                <p class="fw-bold text-uppercase mb-2" style="font-size: 0.85rem; letter-spacing: 0.1em; color: <?= $theme_color ?>;">Testimoni</p>
                <h2 class="font-cinzel fw-bold text-dark text-uppercase mb-3" style="font-size: 2.2rem;">Kata Pengunjung</h2>
            </div>
            
            <div class="row g-4 fade-in-up" style="transition-delay: 0.2s;">
                <div class="col-md-4" v-for="(review, index) in limitedReviews" :key="'review-' + index">
                    <div class="card h-100 border border-light bg-white rounded-4 p-4 position-relative hover-lift shadow-sm" style="overflow: hidden;">
                        
                        <div class="position-absolute" style="top: -15px; right: -5px; color: <?= $theme_color ?>; opacity: 0.04; transform: rotate(15deg);">
                            <svg width="120" height="120" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>

                        <div class="card-body p-0 d-flex flex-column justify-content-between position-relative z-1">
                            <div>
                                <div class="d-flex gap-1 mb-3" style="color: #f59e0b;">
                                    <svg v-for="n in 5" :key="'star-' + index + '-' + n" :class="n <= parseInt(review.rating || 5) ? 'text-warning' : 'text-secondary opacity-25'" style="width:18px; height:18px;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <p class="text-secondary fst-italic mb-4 line-clamp-4" style="font-size: 0.95rem; line-height: 1.7;">
                                    "{{ review.comment }}"
                                </p>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-auto border-top pt-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 46px; height: 46px; background: linear-gradient(135deg, <?= $theme_color ?>, #3b82f6); color: #ffffff !important; font-size: 1.1rem; border: 2px solid #e2e8f0;">
                                    {{ review.visitor_name ? review.visitor_name.charAt(0).toUpperCase() : 'A' }}
                                </div>
                                <div>
                                    <h6 class="font-cinzel fw-bold mb-0 text-dark text-uppercase" style="font-size: 0.95rem;">{{ review.visitor_name }}</h6>
                                    <small class="text-theme fw-medium d-flex align-items-center gap-1 mt-1" style="font-size: 0.75rem;">
                                        <i data-lucide="badge-check" style="width: 12px; height: 12px;"></i> Pengunjung Terverifikasi
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-if="limitedReviews.length === 0" class="col-12 text-center py-4">
                    <p class="text-muted fst-italic">Belum ada ulasan unggulan yang ditampilkan.</p>
                </div>
            </div>
            
        </div>
    </section>
</main>

<script>
    const app = Vue.createApp({
        data() {
            return {
                mouseX: 0,
                mouseY: 0,
                isMouseLeft: true,
                limitedReviews: <?php echo json_encode($limitedReviews); ?>,
                glimpses: <?php echo json_encode($glimpses); ?>,
                lightbox: {
                    show: false,
                    img: '',
                    title: '',
                    desc: ''
                }
            }
        },
        methods: {
            handleMouseMove(e) {
                this.isMouseLeft = false;
                const x = (window.innerWidth / 2 - e.clientX) / window.innerWidth;
                const y = (window.innerHeight / 2 - e.clientY) / window.innerHeight;
                this.mouseX = x;
                this.mouseY = y;
            },
            resetMouse() {
                this.isMouseLeft = true;
                this.mouseX = 0;
                this.mouseY = 0;
            },
            openLightbox(img, title, desc) {
                this.lightbox.img = img;
                this.lightbox.title = title;
                this.lightbox.desc = desc;
                this.lightbox.show = true;
                document.body.style.overflow = 'hidden';
            },
            closeLightbox() {
                this.lightbox.show = false;
                document.body.style.overflow = '';
            }
        },
        mounted() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            if (typeof window.observeAnimations === 'function') {
                setTimeout(() => { window.observeAnimations(); }, 100);
            }
        },
        updated() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    });

    app.mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js?v=<?= time() ?>"></script>

<?php require_once 'templates/footer.php'; ?>