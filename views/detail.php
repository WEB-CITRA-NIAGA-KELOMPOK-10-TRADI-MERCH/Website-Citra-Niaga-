<?php require_once 'templates/header.php'; ?>

<?php
/** @var mysqli $conn */

// ==========================================
// 1. LOGIKA AMBIL FOTO (1 TERBARU + PERWAKILAN KATEGORI)
// ==========================================
$query_gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC");
$gallery_items = [];
$seen_categories = [];
$all_rows = [];

if ($query_gallery) {
    while ($row = mysqli_fetch_assoc($query_gallery)) {
        $all_rows[] = $row;
    }
    
    if (count($all_rows) > 0) {
        // a. Ambil 1 Foto Terbaru sebagai Highlight
        $gallery_items[] = $all_rows[0];
        $seen_categories[] = isset($all_rows[0]['category']) ? $all_rows[0]['category'] : 'umum';
        
        // b. Cari 1 perwakilan dari masing-masing kategori berbeda
        foreach ($all_rows as $row) {
            $cat = isset($row['category']) ? $row['category'] : 'umum';
            if (!in_array($cat, $seen_categories)) {
                $gallery_items[] = $row;
                $seen_categories[] = $cat;
            }
            if (count($gallery_items) >= 6) break; 
        }
        
        // c. Kalau total foto masih kurang dari 6, penuhi sisa slot pakai foto yang ada
        if (count($gallery_items) < 6) {
            foreach ($all_rows as $row) {
                if (!in_array($row, $gallery_items)) {
                    $gallery_items[] = $row;
                }
                if (count($gallery_items) >= 6) break;
            }
        }
    }
}

// ==========================================
// 2. DATA KONTAK & BANNER DARI SETTINGS (CMS)
// ==========================================
$c_address = (!empty($global_setting['contact_address'])) ? htmlspecialchars($global_setting['contact_address']) : 'Jl. Niaga Selatan, Samarinda Kota';
$hero_banner = (!empty($global_setting['hero_banner'])) ? $global_setting['hero_banner'] : 'citraniagabackground.png';
// Hero banner cuma nyimpen nama file di DB, jadi kita tambahin path-nya
$hero_path = "../assets/img/Gallery/Area_Bangunan/" . $hero_banner; 
?>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* FIX WARNA & UI/UX ELEGAN */
    :root {
        --primary-blue: <?= !empty($global_setting['theme_color']) ? $global_setting['theme_color'] : '#254794' ?>;
    }

    .text-white-force { color: #ffffff !important; }
    .bg-brand { background-color: var(--primary-blue) !important; color: #ffffff !important; }
    .text-brand { color: var(--primary-blue) !important; }
    .title-border-left { border-left: 5px solid var(--primary-blue) !important; padding-left: 15px; }
    
    .carousel-inner { border-radius: 20px; background-color: #f1f5f9; border: 1px solid #e2e8f0; }
    .carousel-item img { height: 480px; object-fit: cover; width: 100%; }
    
    .thumb-btn { width: 85px; height: 60px; border-radius: 10px; overflow: hidden; border: 2px solid #e2e8f0; transition: all 0.3s ease; opacity: 0.6; padding: 0; margin-bottom: 10px; }
    .thumb-btn.active { border-color: var(--primary-blue) !important; opacity: 1; transform: translateY(-5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .thumb-btn img { width: 100%; height: 100%; object-fit: cover; }

    .timeline-item { position: relative; padding-left: 30px; border-left: 2px solid #e2e8f0; margin-bottom: 30px; }
    .timeline-badge { position: absolute; left: -10px; top: 0; width: 20px; height: 20px; background: var(--primary-blue); border-radius: 50%; border: 4px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    .custom-card { background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease; }
    .hero-overlay { background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 100%); }
</style>

<main class="w-100 font-plus-jakarta-sans pb-5 mt-5 pt-4 bg-light">
    <div class="container">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="index.php" class="btn btn-white border shadow-sm rounded-pill px-4 py-2 fw-bold text-dark text-decoration-none d-inline-flex align-items-center gap-2" style="background: white;">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali Ke Beranda
            </a>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Beranda</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Profil Kawasan</li>
                </ol>
            </nav>
        </div>

        <div class="position-relative rounded-4 overflow-hidden shadow-lg mb-5" style="height: 420px;">
            <img src="<?= $hero_path ?>" onerror="this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';" class="w-100 h-100 object-fit-cover" alt="Hero Banner">
            <div class="position-absolute top-0 start-0 w-100 h-100 hero-overlay"></div>
            <div class="position-absolute bottom-0 start-0 w-100 p-5">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3 border border-white border-opacity-25" style="background-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px);">
                    <i data-lucide="award" class="w-4 h-4 text-white"></i>
                    <span class="text-white small fw-bold tracking-widest uppercase">Destinasi Budaya Unggulan</span>
                </div>
                <h1 class="font-cinzel text-white fw-bold display-3 mb-0" style="text-shadow: 2px 4px 12px rgba(0,0,0,0.4);">CITRA NIAGA SAMARINDA</h1>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="custom-card p-4 p-md-5 h-100">
                    <h2 class="font-cinzel text-uppercase fw-bold text-dark title-border-left mb-2">Sejarah & Filosofi</h2>
                    <p class="text-brand small fw-bold mb-4 ms-3 tracking-widest uppercase">Ikon Kebanggaan Kalimantan Timur</p>
                    
                    <p class="text-secondary lh-lg mb-4" style="text-align: justify; font-size: 1.05rem;">
                        <strong>Citra Niaga</strong> diresmikan pada akhir 1980-an sebagai pusat perdagangan yang modern namun tetap mempertahankan kearifan lokal. Desainnya yang revolusioner berhasil menyatukan pedagang kaki lima ke dalam lingkungan yang tertata rapi tanpa menghilangkan jiwa pasar rakyatnya.
                    </p>

                    <h4 class="font-cinzel text-uppercase fw-bold text-dark mt-5 mb-4">Linimasa Perkembangan</h4>
                    <div class="ps-2">
                        <div class="timeline-item">
                            <div class="timeline-badge"></div>
                            <h6 class="fw-bold text-dark mb-1">1987 - Pembangunan Dimulai</h6>
                            <p class="text-muted small">Didesain oleh Arsitek Adhi Moersid untuk mengubah wajah pusat kota Samarinda.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-badge"></div>
                            <h6 class="fw-bold text-dark mb-1">1989 - Aga Khan Award</h6>
                            <p class="text-muted small">Menerima penghargaan arsitektur dunia paling bergengsi atas desain ruang publik terbaik.</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-badge" style="background-color: #22c55e; border-color: #22c55e;"></div>
                            <h6 class="fw-bold text-dark mb-1">2026 - Era Revitalisasi Digital</h6>
                            <p class="text-muted small">Kawasan beralih menjadi Smart Tourism Hub yang mengintegrasikan budaya dan teknologi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-4">
                <div class="custom-card overflow-hidden">
                    <div class="bg-brand p-4 d-flex align-items-center gap-3">
                        <i data-lucide="map" class="w-6 h-6 text-white"></i>
                        <h6 class="mb-0 fw-bold tracking-wider font-cinzel text-white text-uppercase">Informasi Umum</h6>
                    </div>
                    <div class="p-4">
                        <div class="mb-4 d-flex gap-3 align-items-start">
                            <div class="bg-light p-2 rounded-circle"><i data-lucide="map-pin" class="text-brand w-5 h-5"></i></div>
                            <div><p class="text-muted mb-0 small fw-bold tracking-tighter">ALAMAT</p><p class="text-dark small mb-0 fw-medium"><?= $c_address ?></p></div>
                        </div>
                        <div class="mb-4 d-flex gap-3 align-items-start">
                            <div class="bg-light p-2 rounded-circle"><i data-lucide="clock" class="text-brand w-5 h-5"></i></div>
                            <div><p class="text-muted mb-0 small fw-bold tracking-tighter">JAM BUKA</p><p class="text-dark small mb-0 fw-medium">08.00 - 22.30 WITA (Setiap Hari)</p></div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <div class="bg-light p-2 rounded-circle"><i data-lucide="landmark" class="text-brand w-5 h-5"></i></div>
                            <div><p class="text-muted mb-0 small fw-bold tracking-tighter">FASILITAS</p><p class="text-dark small mb-0 fw-medium">UMKM, Souvenir, Kuliner, Area Parkir</p></div>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-4">
                    <h6 class="font-cinzel fw-bold mb-3 uppercase small tracking-widest text-muted border-bottom pb-2">Lokasi Koordinat</h6>
                    <div class="rounded-4 overflow-hidden mb-3 border shadow-sm" style="height: 220px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.66440938361!2d117.14713737496472!3d-0.503463899491617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f0f6f4f4f4f%3A0x4f4f4f4f4f4f4f4f!2sCitra%20Niaga!5e0!3m2!1sid!2sid!4v1713775200000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <a href="https://maps.google.com/?q=Citra+Niaga+Samarinda" target="_blank" class="btn bg-brand w-100 rounded-pill fw-bold py-2 shadow-md">
                        <i data-lucide="navigation" class="w-4 h-4 me-2 text-white-force"></i> <span class="text-white-force">Buka Navigasi Sekarang</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="custom-card p-4 p-md-5 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h4 class="font-cinzel text-uppercase fw-bold text-dark mb-0 title-border-left">Galeri Visual Kawasan</h4>
                            <p class="text-muted mt-2 ms-3 mb-0">Intip potret keindahan arsitektur, ragam kuliner, dan aktivitas menarik yang menanti kunjungan Anda.</p>
                        </div>
                        <a href="gallery.php" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-bold">Lihat Semua Galeri</a>
                    </div>
                    
                    <div id="galleryCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
                        <div class="carousel-inner shadow-lg">
                            <?php if(!empty($gallery_items)): ?>
                                <?php foreach($gallery_items as $index => $item): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" 
                                             onerror="this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';" 
                                             class="w-100 d-block" alt="Galeri Citra Niaga">
                                        <div class="carousel-caption text-start px-4 rounded-bottom" style="background: linear-gradient(transparent, rgba(0,0,0,0.85)); left: 0; right: 0; bottom: 0; padding-bottom: 40px !important;">
                                            <h4 class="text-white-force fw-bold mb-1"><?= htmlspecialchars($item['title'] ?? 'Pesona Citra Niaga') ?></h4>
                                            <p class="text-white-50 small mb-0">
                                                <i data-lucide="camera" class="w-3 h-3 d-inline pb-1"></i> 
                                                Kategori: <?= isset($item['category']) ? str_replace('_', ' ', ucwords(htmlspecialchars($item['category']))) : 'Eksplorasi Kawasan' ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active p-5 text-center bg-white">
                                    <i data-lucide="image-off" class="w-16 h-16 mb-3 text-muted mx-auto"></i>
                                    <h5 class="text-muted">Koleksi Foto Sedang Diperbarui</h5>
                                    <p class="text-muted small">Nantikan potret keindahan Citra Niaga di sini.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                            <span class="btn btn-white rounded-circle shadow p-3 d-flex align-items-center"><i data-lucide="chevron-left" class="text-dark"></i></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                            <span class="btn btn-white rounded-circle shadow p-3 d-flex align-items-center"><i data-lucide="chevron-right" class="text-dark"></i></span>
                        </button>
                    </div>

                    <div class="d-flex gap-3 justify-content-center flex-wrap" id="custom-indicators">
                        <?php foreach($gallery_items as $index => $item): ?>
                            <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="<?= $index ?>" 
                                    class="thumb-btn <?= $index === 0 ? 'active' : '' ?>" title="<?= isset($item['category']) ? str_replace('_', ' ', ucwords(htmlspecialchars($item['category']))) : 'Foto '.($index+1) ?>">
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     onerror="this.src='../assets/img/Gallery/Area_Bangunan/citraniagabackground.png';" alt="Thumbnail">
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-card bg-brand p-5 mt-5 border-0 position-relative overflow-hidden rounded-4">
            <div class="position-relative z-1">
                <h4 class="font-cinzel fw-bold mb-3 text-white-force tracking-widest">SIAP BERKUNJUNG KE CITRA NIAGA?</h4>
                <p class="mb-5 text-white-force opacity-75 max-w-2xl" style="font-size: 1.1rem;">Jangan lewatkan kesempatan berburu oleh-oleh khas Kalimantan Timur dan menikmati suasana sore hari yang estetik di kawasan peninggalan Aga Khan ini.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="kios.php" class="btn btn-light text-brand rounded-pill px-5 py-3 fw-bold shadow-lg">
                        <i data-lucide="shopping-cart" class="w-5 h-5 me-2"></i> Jelajahi Kios UMKM
                    </a>
                    <a href="gallery.php" class="btn btn-outline-light text-white-force rounded-pill px-5 py-3 fw-bold border-white">
                        <i data-lucide="image" class="w-5 h-5 me-2"></i> Galeri Lengkap
                    </a>
                </div>
            </div>
            <i data-lucide="map-pin" class="position-absolute text-white-force opacity-10" style="width: 350px; height: 350px; bottom: -80px; right: -80px; transform: rotate(-15deg);"></i>
        </div>

    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();

        const galleryCarousel = document.getElementById('galleryCarousel');
        if (galleryCarousel) {
            galleryCarousel.addEventListener('slide.bs.carousel', function (e) {
                const indicators = document.querySelectorAll('#custom-indicators button');
                indicators.forEach(btn => btn.classList.remove('active'));
                if (indicators[e.to]) {
                    indicators[e.to].classList.add('active');
                }
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php require_once 'templates/footer.php'; ?>