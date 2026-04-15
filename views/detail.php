<?php require_once 'templates/header.php'; ?>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<main class="w-100 font-plus-jakarta-sans pb-5 mt-5 pt-4">
    <div class="container">
        
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="index.php" class="btn btn-white border shadow-sm rounded-pill px-4 py-2 fw-bold text-dark text-decoration-none d-inline-flex align-items-center gap-2" style="background: white;">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
            
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted"><i data-lucide="home" class="w-4 h-4 d-inline pb-1"></i> Beranda</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Citra Niaga Samarinda</li>
                </ol>
            </nav>
        </div>

        <div class="position-relative rounded-4 overflow-hidden shadow-sm mb-5" style="height: 350px;">
            <img src="../assets/img/Bangunan/citraniagabackground.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" alt="Hero">
            <div class="position-absolute top-0 start-0 w-100 h-100 hero-overlay"></div>
            <div class="position-absolute bottom-0 start-0 w-100 p-4 p-md-5">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3 border border-white border-opacity-25" style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(4px);">
                    <i data-lucide="info" class="w-4 h-4 text-white"></i>
                    <span class="text-white small fw-bold tracking-wider">INFORMASI PUSAT</span>
                </div>
                <h1 class="font-cinzel text-white fw-bold mb-0" style="font-size: 3rem; letter-spacing: 1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">DETAIL CITRA NIAGA</h1>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="custom-card p-4 p-md-5 h-100">
                    <h2 class="font-cinzel text-uppercase fw-bold text-dark title-border-left mb-1">Tentang Citra Niaga Samarinda</h2>
                    <p class="text-brand small fw-bold mb-4 ms-3">Sejarah & Profil Destinasi</p>
                    
                    <p class="text-secondary lh-lg" style="text-align: justify;">
                        <strong>Citra Niaga</strong> adalah pusat perdagangan dan wisata budaya yang terletak di jantung Kota Samarinda, Kalimantan Timur. Dibangun antara tahun 1987 hingga 1994, kawasan ini dirancang oleh arsitek Ir. Adhi Moersid sebagai upaya meremajakan kawasan perdagangan tradisional kota yang kumuh menjadi ruang publik yang humanis dan estetis.
                    </p>
                    <p class="text-secondary lh-lg" style="text-align: justify;">
                        Keunikan arsitekturnya yang memadukan unsur tradisional Dayak dan Kutai dengan modernitas urban menjadikan Citra Niaga meraih penghargaan bergengsi <strong class="text-brand">Aga Khan Award for Architecture</strong> pada tahun 1989 — penghargaan internasional tertinggi di bidang arsitektur dunia Islam.
                    </p>
                    <p class="text-secondary lh-lg mb-5" style="text-align: justify;">
                        Hingga kini, Citra Niaga menjadi salah satu ikon kota yang menjadi tempat bertemunya ratusan pedagang lokal, pengrajin, dan masyarakat umum dalam satu ekosistem perdagangan yang harmonis. Pengunjung dapat menikmati berbagai produk UMKM khas Kalimantan, kuliner lokal, dan berbagai kegiatan budaya yang rutin diselenggarakan di kawasan ini.
                    </p>

                    <h4 class="font-cinzel text-uppercase fw-bold text-dark mb-4">Linimasa Sejarah</h4>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-badge">1988</div>
                            <h6 class="fw-bold text-dark mb-1">Berdirinya Citra Niaga</h6>
                            <p class="text-muted small">Citra Niaga Samarinda didirikan pada tahun 1988 sebagai kawasan perdagangan dan wisata terpadu pertama di Kalimantan Timur...</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-badge">1995</div>
                            <h6 class="fw-bold text-dark mb-1">Renovasi Pertama</h6>
                            <p class="text-muted small">Pada tahun 1995, Citra Niaga menjalani renovasi besar pertamanya untuk meningkatkan fasilitas dan kapasitas...</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-badge">2002</div>
                            <h6 class="fw-bold text-dark mb-1">Penghargaan Nasional</h6>
                            <p class="text-muted small">Tahun 2002 menjadi tonggak bersejarah ketika Citra Niaga Samarinda menerima penghargaan sebagai kawasan perdagangan terbaik...</p>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-badge">2010</div>
                            <h6 class="fw-bold text-dark mb-1">Pengembangan Zona Wisata</h6>
                            <p class="text-muted small">Memasuki era baru, pada 2010 Citra Niaga mengembangkan zona wisata khusus dengan menambahkan pertunjukan seni budaya...</p>
                        </div>
                    </div>
                    <a href="profile.php" class="text-brand fw-bold text-decoration-none small mt-3 d-inline-block">Lihat seluruh sejarah <i data-lucide="chevron-right" class="w-4 h-4 d-inline"></i></a>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-4">
                <div class="custom-card overflow-hidden">
                    <div class="bg-brand text-white p-3 d-flex align-items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5"></i>
                        <h6 class="mb-0 fw-bold tracking-wider font-cinzel">INFORMASI UMUM</h6>
                    </div>
                    <div class="p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-4">
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="map-pin"></i></div>
                                <div><p class="small text-muted mb-0">ALAMAT</p><p class="fw-medium text-dark mb-0 small">Jl. Niaga Selatan, Pasar Pagi, Samarinda Kota, Kalimantan Timur 75112</p></div>
                            </li>
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="maximize"></i></div>
                                <div><p class="small text-muted mb-0">LUAS AREA</p><p class="fw-medium text-dark mb-0 small">±5 Hektar</p></div>
                            </li>
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="calendar"></i></div>
                                <div><p class="small text-muted mb-0">TAHUN DIBANGUN</p><p class="fw-medium text-dark mb-0 small">1987 - 1994</p></div>
                            </li>
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="ticket"></i></div>
                                <div><p class="small text-muted mb-0">TIKET MASUK</p><p class="fw-medium text-dark mb-0 small">Gratis (Akses Umum)</p></div>
                            </li>
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="clock"></i></div>
                                <div><p class="small text-muted mb-0">JAM OPERASIONAL</p><p class="fw-medium text-dark mb-0 small">Senin - Minggu, 08.00 - 21.00 WITA</p></div>
                            </li>
                            <li class="d-flex gap-3 align-items-start">
                                <div class="w-8 h-8 rounded-circle bg-light text-brand d-flex align-items-center justify-content-center shrink-0 p-2"><i data-lucide="tag"></i></div>
                                <div><p class="small text-muted mb-0">KATEGORI</p><p class="fw-medium text-dark mb-0 small">Pusat Perdagangan & Wisata Budaya</p></div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="custom-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-cinzel fw-bold mb-0">LOKASI DI PETA</h6>
                        <a href="https://www.google.com/maps/place/Komplek+Citra+Niaga+Samarinda/@-0.5027488,117.1471374,17z/data=!3m1!4b1!4m6!3m5!1s0x2df67f9df3bafceb:0x53ce39152b36c908!8m2!3d-0.5027542!4d117.1497123!16s%2Fg%2F11n8qq9m8l?entry=ttu&g_ep=EgoyMDI2MDMzMC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="text-brand small text-decoration-none">Perbesar <i data-lucide="external-link" class="w-3 h-3 d-inline"></i></a>
                    </div>
                    
                    <div class="rounded overflow-hidden mb-3 border shadow-sm" style="height: 200px;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.683317793732!2d117.1429990757342!3d-0.4965825995000574!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67f0b0c6114d9%3A0x651fc0571cbbcb78!2sCitra%20Niaga%20Samarinda!5e0!3m2!1sen!2sid!4v1712056000000!5m2!1sen!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <a href="https://www.google.com/maps/place/Komplek+Citra+Niaga+Samarinda/@-0.5027488,117.1471374,17z/data=!3m1!4b1!4m6!3m5!1s0x2df67f9df3bafceb:0x53ce39152b36c908!8m2!3d-0.5027542!4d117.1497123!16s%2Fg%2F11n8qq9m8l?entry=ttu&g_ep=EgoyMDI2MDMzMC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="btn bg-brand text-white w-100 rounded-pill fw-bold">
                        <i data-lucide="navigation" class="w-4 h-4 me-2"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="custom-card p-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="title-border-left h-100"></div>
                        <h4 class="font-cinzel text-uppercase fw-bold text-dark mb-0">Galeri Foto</h4>
                    </div>
                    <p class="text-muted small mb-3 ms-3">6 foto tersedia</p>
                    
                    <div id="galleryCarousel" class="carousel slide mb-3" data-bs-ride="false">
                        <div class="carousel-inner rounded-3 overflow-hidden shadow-sm" style="height: 400px;">
                            <div class="carousel-item active h-100">
                                <img src="../assets/img/Gallery/Gambar_Lainnya.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Gerbang Utama">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Gerbang Utama Citra Niaga</h5>
                                </div>
                            </div>

                            <div class="carousel-item h-100">
                                <img src="../assets/img/Gallery_Lainnya/area_pengunjung.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Suasana Kawasan">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Suasana Kawasan Pejalan Kaki</h5>
                                </div>
                            </div>

                            <div class="carousel-item h-100">
                                <img src="../assets/img/Kios_Kerajinan/souvenir_berkah.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Pusat Kerajinan">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Pusat Kerajinan</h5>
                                </div>
                            </div>

                            <div class="carousel-item h-100">
                                <img src="../assets/img/Gallery_Kuliner/warkop_starbud.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Kawasan Kuliner">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Kawasan Kuliner</h5>
                                </div>
                            </div>

                            <div class="carousel-item h-100">
                                <img src="../assets/img/Kios/Kerajinan_Borneo.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Kerajinan Borneo">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Kerajinan Khas Kalimantan</h5>
                                </div>
                            </div>

                            <div class="carousel-item h-100">
                                <img src="../assets/img/Events/insevent2.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Event">
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white fw-bold mb-0">Pertunjukan Event</h5>
                                </div>
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                            <span class="btn btn-light rounded-circle shadow p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i data-lucide="chevron-left" class="text-dark"></i>
                            </span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                            <span class="btn btn-light rounded-circle shadow p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i data-lucide="chevron-right" class="text-dark"></i>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex gap-2 justify-content-center" id="custom-indicators">
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="0" class="active border-primary rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Gallery/Gambar_Lainnya.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 1">
                        </button>
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="1" class="rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Gallery_Lainnya/area_pengunjung.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 2">
                        </button>
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="2" class="rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Kios_Kerajinan/souvenir_berkah.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 3">
                        </button>
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="3" class="rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Gallery_Kuliner/warkop_starbud.jpg" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 4">
                        </button>
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="4" class="rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Kios/Kerajinan_Borneo.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 5">
                        </button>
                        <button type="button" data-bs-target="#galleryCarousel" data-bs-slide-to="5" class="rounded border border-2 p-0 overflow-hidden" style="width: 70px; height: 50px;">
                            <img src="../assets/img/Events/insevent2.png" onerror="this.onerror=null;this.src='../assets/img/default-placeholder.png';" class="w-100 h-100 object-fit-cover" alt="Thumb 6">
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-4">
                <div class="custom-card p-4">
                    <h6 class="font-cinzel fw-bold mb-4 tracking-wider">JELAJAHI LEBIH LANJUT</h6>
                    <div class="d-flex flex-column gap-3">
                        <a href="gallery.php" class="d-flex align-items-center justify-content-between text-decoration-none text-dark p-2 hover-bg-light rounded">
                            <div class="d-flex align-items-center gap-3"><div class="w-8 h-8 rounded bg-light text-success d-flex items-center justify-content-center p-1"><i data-lucide="image"></i></div> <span class="fw-medium small">Galeri Foto</span></div>
                            <i data-lucide="chevron-right" class="text-muted w-4 h-4"></i>
                        </a>
                        <a href="kios.php" class="d-flex align-items-center justify-content-between text-decoration-none text-dark p-2 hover-bg-light rounded">
                            <div class="d-flex align-items-center gap-3"><div class="w-8 h-8 rounded bg-light text-primary d-flex items-center justify-content-center p-1"><i data-lucide="shopping-bag"></i></div> <span class="fw-medium small">Daftar Kios & UMKM</span></div>
                            <i data-lucide="chevron-right" class="text-muted w-4 h-4"></i>
                        </a>
                        <a href="reviews.php" class="d-flex align-items-center justify-content-between text-decoration-none text-dark p-2 hover-bg-light rounded">
                            <div class="d-flex align-items-center gap-3"><div class="w-8 h-8 rounded bg-light text-warning d-flex items-center justify-content-center p-1"><i data-lucide="star"></i></div> <span class="fw-medium small">Review Pengunjung</span></div>
                            <i data-lucide="chevron-right" class="text-muted w-4 h-4"></i>
                        </a>
                        <a href="profile.php" class="d-flex align-items-center justify-content-between text-decoration-none text-dark p-2 hover-bg-light rounded">
                            <div class="d-flex align-items-center gap-3"><div class="w-8 h-8 rounded bg-light text-info d-flex items-center justify-content-center p-1"><i data-lucide="book-open"></i></div> <span class="fw-medium small">Profil & Sejarah</span></div>
                            <i data-lucide="chevron-right" class="text-muted w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <div class="custom-card p-4 bg-danger text-white border-0">
                    <h6 class="font-cinzel fw-bold mb-2">INGIN MENGADAKAN ACARA?</h6>
                    <p class="small mb-4 text-white-50">Urus izin event dan pameran di Citra Niaga dengan mudah.</p>
                    <a href="events.php" class="btn btn-light text-danger w-100 rounded-pill fw-bold">Ajukan Izin Event</a>
                </div>
            </div>
        </div>

        <div class="custom-card bg-brand text-white p-4 p-md-5 mb-5 border-0 position-relative overflow-hidden">
            <h4 class="font-cinzel fw-bold mb-3 tracking-wider position-relative z-1">RENCANAKAN KUNJUNGAN ANDA</h4>
            <p class="mb-5 position-relative z-1">Siapkan perjalanan yang menyenangkan ke Citra Niaga. Temukan kios UMKM terbaik, nikmati kuliner lokal, dan abadikan momen indah.</p>
            
            <div class="d-flex gap-3 position-relative z-1">
                <a href="https://www.google.com/maps/place/Komplek+Citra+Niaga+Samarinda/@-0.5027488,117.1471374,17z/data=!3m1!4b1!4m6!3m5!1s0x2df67f9df3bafceb:0x53ce39152b36c908!8m2!3d-0.5027542!4d117.1497123!16s%2Fg%2F11n8qq9m8l?entry=ttu&g_ep=EgoyMDI2MDMzMC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="btn btn-light text-brand rounded-pill px-4 fw-bold">
                    <i data-lucide="navigation" class="w-4 h-4 me-2"></i> Rute ke Lokasi
                </a>
                <a href="kios.php" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                    <i data-lucide="search" class="w-4 h-4 me-2"></i> Lihat Daftar Kios
                </a>
            </div>
            
            <i data-lucide="map-pin" class="position-absolute text-white opacity-10" style="width: 300px; height: 300px; bottom: -50px; right: -50px; transform: rotate(-15deg);"></i>
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
                indicators.forEach(btn => {
                    btn.classList.remove('active', 'border-primary');
                });
                if (indicators[e.to]) {
                    indicators[e.to].classList.add('active', 'border-primary');
                }
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>