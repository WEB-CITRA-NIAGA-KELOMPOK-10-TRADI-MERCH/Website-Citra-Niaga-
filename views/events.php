<?php 
require_once '../config/koneksi.php';
/** @var mysqli $conn */

// === PANGGIL PENGATURAN CMS BIAR DINAMIS ===
require_once '../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';

// Gunakan $web_setting BUKAN $global_setting
$c_address = (!empty($web_setting['contact_address'])) ? nl2br(htmlspecialchars($web_setting['contact_address'])) : 'Jl. Niaga Selatan No. 1, Pasar Pagi,<br>Samarinda Kota, Kota Samarinda,<br>Kalimantan Timur 75111';
$c_phone = (!empty($web_setting['contact_phone'])) ? htmlspecialchars($web_setting['contact_phone']) : '+62 822-1949-7715';
$c_email = (!empty($web_setting['contact_email'])) ? htmlspecialchars($web_setting['contact_email']) : 'info@citra-niaga-samarinda.id';
$c_ig = (!empty($web_setting['contact_ig'])) ? htmlspecialchars($web_setting['contact_ig']) : '@citraniagasamarinda';

$wa_clean = preg_replace('/[^0-9]/', '', $c_phone);
if (substr($wa_clean, 0, 1) === '0') {
    $wa_clean = '62' . substr($wa_clean, 1);
}
$wa_link = "https://wa.me/" . $wa_clean;

$ig_clean = str_replace('@', '', $c_ig);
$ig_link = "https://www.instagram.com/" . $ig_clean;

$query_events = mysqli_query($conn, "SELECT * FROM events ORDER BY start_date ASC");
$today = date('Y-m-d');

$events_all = [];
$events_ongoing = [];
$events_upcoming = [];
$events_past = [];

if($query_events) {
    while($ev = mysqli_fetch_assoc($query_events)) {
        $events_all[] = $ev; 
        
        if ($today >= $ev['start_date'] && $today <= $ev['end_date']) {
            $events_ongoing[] = $ev;
        } elseif ($ev['start_date'] > $today) {
            $events_upcoming[] = $ev;
        } else {
            $events_past[] = $ev;
        }
    }
}

usort($events_upcoming, function($a, $b) { return strtotime($a['start_date']) - strtotime($b['start_date']); });

function formatEventDate(string $start, string $end): string {
    $s = date('d M Y', strtotime($start));
    $e = date('d M Y', strtotime($end));
    return ($s === $e) ? $s : "$s - $e";
}

require_once 'templates/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* ========================================== */
    /* SIHIR CSS DINAMIS NGIKUTIN DASHBOARD       */
    /* ========================================== */
    :root {
        --theme-color: <?= $theme_color ?>;
        --font-custom: '<?= $font_family ?>', sans-serif;
        --theme-color-light: color-mix(in srgb, var(--theme-color) 15%, white);
    }

    main { font-family: var(--font-custom) !important; }

    .bg-theme-dynamic { background-color: var(--theme-color) !important; }
    .text-theme-dynamic { color: var(--theme-color) !important; }
    .bg-theme-light { background-color: var(--theme-color-light) !important; }
    
    .nav-pills .nav-link { color: #6c757d; border-radius: 50px; padding: 10px 24px; transition: all 0.3s; margin: 0 5px; }
    .nav-pills .nav-link.active { background-color: var(--theme-color) !important; color: #fff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .nav-pills .nav-link:hover:not(.active) { background-color: rgba(0,0,0,0.05); }
    
    .event-card-img { height: 220px; object-fit: cover; }

    .contact-link-card { transition: all 0.3s ease; border-radius: 12px; padding: 8px; margin-left: -8px; }
    .contact-link-card:hover { background-color: #f8fafc; transform: translateX(5px); }

    /* ======================================================== */
    /* --- FIX RESPONSIVE KHUSUS LAYAR HP (MOBILE DEVICES) ---  */
    /* ======================================================== */
    @media (max-width: 768px) {
        /* Ukuran judul hero disesuaikan biar gak patah */
        .hero-title { font-size: 2rem !important; }
        .hero-desc { font-size: 0.95rem !important; padding: 0 1rem; }

        /* Tombol filter acara (Pills) dikecilkan dikit padding dan fontnya */
        .nav-pills .nav-link { padding: 8px 16px; font-size: 0.85rem; margin: 0 2px 8px 2px; }

        /* Tinggi gambar kartu acara disusutkan biar gak kepanjangan */
        .event-card-img { height: 180px; }

        /* Area padding pada panduan diperbaiki biar lega */
        .guide-container { padding: 1rem !important; }
        
        /* Tombol kontak diperlebar paddingnya biar gampang dipencet jempol */
        .contact-link-card { padding: 12px; margin-left: 0; border: 1px solid #f1f5f9; }
    }
</style>

<main id="app" v-cloak class="w-100 bg-light pb-5 min-vh-100">
    
    <section class="bg-theme-dynamic pt-5 pb-5 text-center position-relative z-1 mt-5">
        <div class="container py-5 fade-in-up">
            <div class="mb-4 text-white d-flex justify-content-center">
                <i data-lucide="calendar" style="width: 40px; height: 40px; stroke-width: 1.5;"></i>
            </div>
            <h1 class="font-cinzel display-5 fw-bold text-white mb-3 text-uppercase tracking-widest hero-title">Gelar Acaramu</h1>
            <p class="text-white-50 fs-6 mx-auto mb-0 hero-desc" style="max-width: 700px;">
                Plaza terbuka Citra Niaga menyediakan latar belakang yang sempurna untuk pertunjukan budaya, pameran, dan pertemuan komunitas.
            </p>
        </div>
    </section>

    <div class="position-relative w-100 z-0" style="margin-top: -1px;">
        <svg viewBox="0 0 1440 100" class="w-100 h-auto" preserveAspectRatio="none">
            <path fill="var(--theme-color)" d="M0,0 C320,80 720,80 1440,0 L1440,0 L0,0 Z"></path>
        </svg>
    </div>

    <div class="container mt-4 position-relative z-1">
        <div class="row g-4 items-start">
            
            <div class="col-lg-8 fade-in-up">
                <h2 class="font-cinzel h4 fw-bold text-dark text-uppercase tracking-wider mb-4">Pedoman Perizinan</h2>
                
                <transition-group name="list" tag="div" class="d-flex flex-column gap-3">
                    <div v-for="(guide, index) in guidelines" :key="index" 
                         class="bg-white rounded-4 p-3 guide-container shadow-sm border d-flex align-items-center gap-3 gap-md-4 hover-scale transition">
                        <div class="rounded-circle bg-theme-light text-theme-dynamic fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 35px; height: 35px; font-size: 0.8rem;">
                            {{ index + 1 }}
                        </div>
                        <p class="text-secondary mb-0 fw-medium" style="font-size: 0.9rem;">{{ guide }}</p>
                    </div>
                </transition-group>
            </div>

            <div class="col-lg-4 fade-in-up">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h3 class="font-cinzel h6 fw-bold text-dark text-uppercase mb-4">Hubungi Pengelola</h3>
                    
                    <div class="d-flex flex-column gap-3">
                        
                        <div class="d-flex align-items-start gap-3 px-2">
                            <div class="rounded-circle bg-theme-light text-theme-dynamic d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-1">Alamat Kantor</p>
                                <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.5;">
                                    <?= $c_address ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-2 text-light">

                        <a href="<?= $wa_link ?>" target="_blank" class="contact-link-card text-decoration-none d-flex align-items-start gap-3">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 36px; height: 36px; background-color: #25D366;">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-0"><?= $c_phone ?></p>
                                <p class="text-muted mb-0" style="font-size: 0.7rem;">Klik untuk Chat WhatsApp</p>
                            </div>
                        </a>

                        <a href="<?= $ig_link ?>" target="_blank" class="contact-link-card text-decoration-none d-flex align-items-start gap-3">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 36px; height: 36px; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);">
                                <svg class="text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                                </svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-0"><?= $c_ig ?></p>
                                <p class="text-muted mb-0" style="font-size: 0.7rem;">Follow Instagram Kami</p>
                            </div>
                        </a>

                        <a href="mailto:<?= $c_email ?>" class="contact-link-card text-decoration-none d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-theme-light text-theme-dynamic d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 36px; height: 36px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-theme-dynamic mb-0"><?= $c_email ?></p>
                                <p class="text-muted mb-0" style="font-size: 0.7rem;">Kirim Email Resmi</p>
                            </div>
                        </a>

                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h3 class="font-cinzel h6 fw-bold text-dark text-uppercase mb-3">FAQ</h3>
                    <p class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">Masih ada pertanyaan?</p>
                    <p class="text-muted small mb-0">Silakan klik kontak di atas untuk informasi lebih lanjut mengenai ketersediaan lokasi dan biaya perizinan.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="container mt-5 pt-4 pt-md-5 mb-5 border-top border-light border-2 fade-in-up">
        
        <div class="text-center mb-4 mb-md-5">
            <h2 class="font-cinzel h3 fw-bold text-dark text-uppercase tracking-wider">Jadwal Acara</h2>
            <p class="text-muted">Jangan lewatkan berbagai momen seru di kawasan kami</p>
        </div>

        <ul class="nav nav-pills justify-content-center flex-wrap gap-2 mb-4 mb-md-5" id="eventTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">Semua Acara</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab">Sedang Berjalan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Akan Datang</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">Telah Selesai</button>
            </li>
        </ul>

        <div class="tab-content" id="eventTabsContent">
            
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <?php if(count($events_all) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_all as $ev): 
                            $status_badge = "";
                            $is_past = false;
                            if ($today >= $ev['start_date'] && $today <= $ev['end_date']) {
                                $status_badge = '<span class="badge bg-success mb-3 px-3 py-2 rounded-pill">🔴 Sedang Berlangsung</span>';
                            } elseif ($ev['start_date'] > $today) {
                                $status_badge = '<span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill">✨ Akan Datang</span>';
                            } else {
                                $status_badge = '<span class="badge bg-secondary mb-3 px-3 py-2 rounded-pill">Telah Selesai</span>';
                                $is_past = true;
                            }
                        ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden <?= $is_past ? '' : 'hover-scale transition' ?>" <?= $is_past ? 'style="opacity: 0.75;"' : '' ?>>
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="card-img-top event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>" <?= $is_past ? 'style="filter: grayscale(80%);"' : '' ?>>
                                <div class="card-body p-4 pb-2">
                                    <?= $status_badge ?>
                                    <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="card-text text-muted small line-clamp-3"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-3">
                                    <div class="d-flex align-items-center fw-bold <?= $is_past ? 'text-muted' : 'text-theme-dynamic' ?> small">
                                        <i data-lucide="<?= $is_past ? 'calendar-check' : 'calendar' ?>" class="me-2" style="width: 16px; height: 16px;"></i>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="calendar-x" class="mb-3" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                        <p>Belum ada acara yang didaftarkan.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="ongoing" role="tabpanel">
                <?php if(count($events_ongoing) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_ongoing as $ev): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-scale transition">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="card-img-top event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>">
                                <div class="card-body p-4 pb-2">
                                    <span class="badge bg-success mb-3 px-3 py-2 rounded-pill">🔴 Sedang Berlangsung</span>
                                    <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="card-text text-muted small line-clamp-3"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-3">
                                    <div class="d-flex align-items-center fw-bold text-theme-dynamic small">
                                        <i data-lucide="calendar" class="me-2" style="width: 16px; height: 16px;"></i>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="calendar-x" class="mb-3" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                        <p>Tidak ada acara yang sedang berlangsung saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="upcoming" role="tabpanel">
                <?php if(count($events_upcoming) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_upcoming as $ev): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-scale transition">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="card-img-top event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>">
                                <div class="card-body p-4 pb-2">
                                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill">✨ Akan Datang</span>
                                    <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="card-text text-muted small line-clamp-3"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-3">
                                    <div class="d-flex align-items-center fw-bold text-theme-dynamic small">
                                        <i data-lucide="calendar" class="me-2" style="width: 16px; height: 16px;"></i>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="calendar-clock" class="mb-3" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                        <p>Belum ada jadwal acara baru dalam waktu dekat.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="past" role="tabpanel">
                <?php if(count($events_past) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_past as $ev): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="opacity: 0.75;">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="card-img-top event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>" style="filter: grayscale(80%);">
                                <div class="card-body p-4 pb-2">
                                    <span class="badge bg-secondary mb-3 px-3 py-2 rounded-pill">Telah Selesai</span>
                                    <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="card-text text-muted small line-clamp-3"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="card-footer bg-white border-0 p-4 pt-3">
                                    <div class="d-flex align-items-center text-muted fw-bold small">
                                        <i data-lucide="calendar-check" class="me-2" style="width: 16px; height: 16px;"></i>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i data-lucide="history" class="mb-3" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                        <p>Belum ada catatan riwayat acara.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<script>
    Vue.createApp({
        data() {
            return {
                guidelines: [
                    "Ajukan permohonan izin acara minimal 14 hari sebelum acara berlangsung",
                    "Sertakan proposal acara lengkap dengan rincian kegiatan, jumlah peserta, dan jadwal",
                    "Lampirkan fotokopi KTP penanggung jawab acara",
                    "Sertakan surat rekomendasi dari instansi terkait jika diperlukan",
                    "Pembayaran biaya administrasi dapat dilakukan di kantor pengelola Citra Niaga",
                    "Pengajuan izin dapat dilakukan secara langsung di kantor atau melalui email",
                    "Keputusan persetujuan akan diberikan dalam 7 hari kerja setelah dokumen lengkap diterima"
                ]
            }
        },
        mounted() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        },
        updated() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>