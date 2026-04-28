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

$c_address = (!empty($web_setting['contact_address'])) ? nl2br(htmlspecialchars($web_setting['contact_address'])) : 'Jl. Niaga Selatan, Kel. Pelabuhan, Kec. Samarinda Kota, Kota Samarinda, Kalimantan Timur 75112';
$c_phone = (!empty($web_setting['contact_phone'])) ? htmlspecialchars($web_setting['contact_phone']) : '+62 822-1949-7715';
$c_email = (!empty($web_setting['contact_email'])) ? htmlspecialchars($web_setting['contact_email']) : 'info@citraniaga.com';
$c_ig = (!empty($web_setting['contact_ig'])) ? htmlspecialchars($web_setting['contact_ig']) : '@citraniagasamarinda';

$wa_clean = preg_replace('/[^0-9]/', '', $c_phone);
if (strpos($wa_clean, '0') === 0) {
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

<style>
    :root {
        --theme-color: <?= $theme_color ?>;
        --theme-color-light: color-mix(in srgb, var(--theme-color) 10%, white);
        --font-custom: '<?= $font_family ?>', sans-serif;
        --text-color: <?= $text_color ?>;
        --header-text-color: <?= $header_text_color ?>;
    }

    main { font-family: var(--font-custom) !important; background-color: #fafafa; }

    h1, h2, h3, h4, h5, h6, .text-dark { color: var(--header-text-color) !important; }
    p, .text-muted, .text-secondary { color: var(--text-color) !important; }

    .text-theme { color: var(--theme-color) !important; }
    .bg-theme { background-color: var(--theme-color) !important; }
    .faq-box h3, .faq-box p { color: #ffffff !important; } 
    .timeline-container { position: relative; padding-left: 10px; }
    .timeline-line {
        position: absolute; left: 34px; top: 20px; bottom: 30px;
        width: 2px; background-color: #e2e8f0; z-index: 0;
    }
    .timeline-num {
        width: 48px; height: 48px; border-radius: 50%;
        background-color: var(--theme-color) !important; 
        color: #ffffff !important; 
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.25rem; z-index: 1; flex-shrink: 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .timeline-card {
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.3s ease;
        margin-bottom: 1.2rem; padding: 20px;
    }
    .timeline-card:hover { border-color: var(--theme-color); box-shadow: 0 8px 16px rgba(0,0,0,0.06); }
    .timeline-icon-box {
        width: 48px; height: 48px; border-radius: 50%;
        background-color: var(--theme-color-light); color: var(--theme-color);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    .contact-box { background: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9; }
    .icon-wrapper {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        color: #ffffff; 
    }
    
    .faq-box {
        background-color: var(--theme-color);
        border-radius: 16px; position: relative; overflow: hidden;
    }

    .nav-pills .nav-link { 
        color: var(--text-color); border-radius: 50px; padding: 10px 24px; 
        font-weight: 600; margin: 0 4px; border: 1px solid transparent;
    }
    .nav-pills .nav-link:hover:not(.active) { background-color: #f1f5f9; }
    .nav-pills .nav-link.active { background-color: var(--theme-color) !important; color: #fff !important; }
    
    .event-card-img { height: 220px; object-fit: cover; border-top-left-radius: 16px; border-top-right-radius: 16px; }
    .clean-card { background: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9; transition: transform 0.2s; }
    .clean-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

    @media (max-width: 768px) {
        .timeline-line { left: 24px; }
        .timeline-num { width: 40px; height: 40px; font-size: 1rem; }
        .timeline-icon-box { width: 40px; height: 40px; }
    }
</style>

<main id="app" v-cloak class="w-100 min-vh-100 pb-5 pt-5">
    
    <div class="container mt-5 pt-5 mb-5 text-center fade-in-up">
        <div class="d-flex justify-content-center mb-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: var(--theme-color-light);">
                <svg style="color: var(--theme-color); width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            </div>
        </div>
        <h1 class="font-cinzel display-5 fw-bold text-dark text-uppercase tracking-widest mb-3">GELAR ACARAMU</h1>
        <p class="text-secondary fs-6 mx-auto mb-0" style="max-width: 650px;">
            Plaza terbuka Citra Niaga menyediakan latar belakang yang sempurna untuk pertunjukan budaya, pameran, dan pertemuan komunitas.
        </p>
    </div>

    <div class="container py-2">
        <div class="row g-4 items-start">
            
            <div class="col-lg-8 fade-in-up">
                <div class="bg-white p-4 p-md-5 rounded-[24px] shadow-sm border border-gray-100 h-100">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-theme-light text-theme p-2 rounded-xl">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        </div>
                        <h2 class="font-cinzel h5 fw-bold text-theme text-uppercase mb-0 tracking-wider">PEDOMAN PERIZINAN</h2>
                    </div>
                    
                    <p class="text-secondary mb-4 pb-3 border-bottom small">Berikut adalah langkah-langkah yang harus dilakukan untuk mengajukan izin acara di area kantor pengelola Citra Niaga.</p>
                    
                    <div class="timeline-container mt-4">
                        <div class="timeline-line"></div>
                        <div v-for="(item, index) in guidelines" :key="index" class="d-flex gap-3 gap-md-4 mb-1 position-relative z-1 align-items-center">
                            <div class="timeline-num">{{ index + 1 }}</div>
                            <div class="timeline-card flex-grow-1">
                                <div class="d-flex align-items-center gap-3 gap-md-4">
                                    <div class="timeline-icon-box" v-html="item.icon"></div>
                                    <div>
                                        <h3 class="h6 fw-bold text-theme mb-1">{{ item.title }}</h3>
                                        <p class="small text-muted mb-0 lh-sm">{{ item.desc }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex gap-2 align-items-center">
                            <svg class="text-theme" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <p class="small text-theme fw-bold mb-0">Catatan Penting</p>
                        </div>
                        <p class="small text-muted mb-0 mt-1 ms-4">Pastikan semua berkas lengkap agar proses perizinan dapat berjalan dengan lancar.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 fade-in-up">
                
                <div class="contact-box p-4 p-md-5 mb-4 shadow-sm">
                    <h3 class="font-cinzel h6 fw-bold text-theme text-uppercase border-bottom pb-3 mb-4 tracking-wider">HUBUNGI PENGELOLA</h3>
                    
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-wrapper" style="background-color: #e0e7ff; color: var(--theme-color);">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-theme mb-1">Alamat Kantor</p>
                                <p class="small text-muted mb-0 lh-sm"><?= $c_address ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-wrapper" style="background-color: #25D366; color: #ffffff;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-theme mb-1"><?= $c_phone ?></p>
                                <a href="<?= $wa_link ?>" target="_blank" class="small text-muted text-decoration-none hover-theme transition">Klik untuk Chat WhatsApp</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-wrapper" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);">
                                <svg width="22" height="22" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-theme mb-1"><?= $c_ig ?></p>
                                <a href="<?= $ig_link ?>" target="_blank" class="small text-muted text-decoration-none hover-theme transition">Follow Instagram Kami</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-wrapper bg-theme text-white">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            <div>
                                <p class="small fw-bold text-theme mb-1"><?= $c_email ?></p>
                                <a href="mailto:<?= $c_email ?>" class="small text-muted text-decoration-none hover-theme transition">Kirim Email Resmi</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faq-box p-4 p-md-5 text-center shadow-sm">
                    <svg class="position-absolute" style="width: 180px; height: 180px; right: -40px; top: -40px; opacity: 0.1; color: white;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                    
                    <div class="position-relative z-10">
                        <h3 class="font-cinzel h4 fw-bold text-white mb-3">FAQ</h3>
                        <p class="fw-bold text-white mb-3 small">Masih ada pertanyaan?</p>
                        <p class="text-white-50 small mb-4 lh-sm">Silakan hubungi kami untuk informasi lebih lanjut mengenai ketersediaan lokasi dan biaya perizinan.</p>
                        <a href="<?= $wa_link ?>" target="_blank" class="btn bg-white text-theme fw-bold rounded-pill w-100 py-3 shadow-sm">Hubungi Kami</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container py-5 mt-3 fade-in-up">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-3 border-bottom border-light">
            <h2 class="font-cinzel h4 fw-bold text-dark text-uppercase tracking-wider mb-3 mb-md-0">Agenda Acara</h2>
            
            <ul class="nav nav-pills" id="eventTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all">Semua</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#ongoing">Aktif</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#upcoming">Mendatang</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#past">Selesai</button></li>
            </ul>
        </div>

        <div class="tab-content" id="eventTabsContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <?php if(count($events_all) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_all as $ev): 
                            $status_badge = ""; $is_past = false;
                            if ($today >= $ev['start_date'] && $today <= $ev['end_date']) {
                                $status_badge = '<span class="badge bg-success mb-2 px-3 py-2 rounded-pill"><svg class="d-inline me-1" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"/></svg> Sedang Berlangsung</span>';
                            } elseif ($ev['start_date'] > $today) {
                                $status_badge = '<span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill"><svg class="d-inline me-1" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Akan Datang</span>';
                            } else {
                                $status_badge = '<span class="badge bg-secondary mb-2 px-3 py-2 rounded-pill">Telah Selesai</span>';
                                $is_past = true;
                            }
                        ?>
                        <div class="col">
                            <div class="clean-card h-100 d-flex flex-column" <?= $is_past ? 'style="opacity: 0.85;"' : '' ?>>
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>" <?= $is_past ? 'style="filter: grayscale(100%);"' : '' ?>>
                                <div class="p-4 flex-grow-1">
                                    <?= $status_badge ?>
                                    <h5 class="fw-bold text-dark mt-2 mb-2"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="text-muted small line-clamp-2 mb-0"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="p-3 px-4 border-top border-light bg-light mt-auto rounded-bottom-4">
                                    <div class="d-flex align-items-center fw-bold <?= $is_past ? 'text-muted' : 'text-theme' ?> small">
                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted clean-card"><p class="mb-0">Belum ada acara yang didaftarkan.</p></div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="ongoing" role="tabpanel">
                <?php if(count($events_ongoing) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_ongoing as $ev): ?>
                        <div class="col">
                            <div class="clean-card h-100 d-flex flex-column">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>">
                                <div class="p-4 flex-grow-1">
                                    <span class="badge bg-success mb-2 px-3 py-2 rounded-pill"><svg class="d-inline me-1" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"/></svg> Sedang Berlangsung</span>
                                    <h5 class="fw-bold text-dark mt-2 mb-2"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="text-muted small line-clamp-2 mb-0"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="p-3 px-4 border-top border-light bg-light mt-auto rounded-bottom-4">
                                    <div class="d-flex align-items-center fw-bold text-theme small">
                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted clean-card"><p class="mb-0">Tidak ada acara yang sedang berlangsung saat ini.</p></div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="upcoming" role="tabpanel">
                <?php if(count($events_upcoming) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_upcoming as $ev): ?>
                        <div class="col">
                            <div class="clean-card h-100 d-flex flex-column">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>">
                                <div class="p-4 flex-grow-1">
                                    <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill"><svg class="d-inline me-1" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Akan Datang</span>
                                    <h5 class="fw-bold text-dark mt-2 mb-2"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="text-muted small line-clamp-2 mb-0"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="p-3 px-4 border-top border-light bg-light mt-auto rounded-bottom-4">
                                    <div class="d-flex align-items-center fw-bold text-theme small">
                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted clean-card"><p class="mb-0">Belum ada jadwal acara baru dalam waktu dekat.</p></div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="past" role="tabpanel">
                <?php if(count($events_past) > 0): ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach($events_past as $ev): ?>
                        <div class="col">
                            <div class="clean-card h-100 d-flex flex-column" style="opacity: 0.85;">
                                <img src="../assets/img/Events/<?= htmlspecialchars($ev['image']) ?>" class="event-card-img" alt="<?= htmlspecialchars($ev['title']) ?>" style="filter: grayscale(100%);">
                                <div class="p-4 flex-grow-1">
                                    <span class="badge bg-secondary mb-2 px-3 py-2 rounded-pill">Telah Selesai</span>
                                    <h5 class="fw-bold text-dark mt-2 mb-2"><?= htmlspecialchars($ev['title']) ?></h5>
                                    <p class="text-muted small line-clamp-2 mb-0"><?= htmlspecialchars($ev['description']) ?></p>
                                </div>
                                <div class="p-3 px-4 border-top border-light bg-light mt-auto rounded-bottom-4">
                                    <div class="d-flex align-items-center text-muted fw-bold small">
                                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                                        <?= formatEventDate($ev['start_date'], $ev['end_date']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted clean-card"><p class="mb-0">Belum ada catatan riwayat acara.</p></div>
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
                    { title: "Ajukan permohonan izin", desc: "Ajukan permohonan izin acara minimal 14 hari sebelum acara berlangsung.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
                    { title: "Sertakan proposal acara", desc: "Sertakan proposal acara lengkap dengan rincian kegiatan, jumlah peserta, dan jadwal.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>' },
                    { title: "Lampirkan fotokopi KTP", desc: "Lampirkan fotokopi KTP penanggung jawab acara.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>' },
                    { title: "Sertakan surat rekomendasi", desc: "Sertakan surat rekomendasi dari instansi terkait jika diperlukan.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>' },
                    { title: "Pembayaran biaya administrasi", desc: "Pembayaran biaya administrasi dapat dilakukan di kantor pengelola Citra Niaga.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>' },
                    { title: "Pengajuan izin", desc: "Pengajuan izin dapat dilakukan secara langsung di kantor atau melalui email.", icon: '<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>' }
                ]
            }
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>