<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/koneksi.php';

/** @var mysqli $conn */

require_once '../models/ReviewsModel.php'; 
require_once '../models/SettingsModel.php'; 

$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 
$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';

$text_color = !empty($web_setting['text_color']) ? htmlspecialchars($web_setting['text_color']) : '#333333';
$header_text_color = !empty($web_setting['header_text_color']) ? htmlspecialchars($web_setting['header_text_color']) : '#333333';

$reviewsModel = new ReviewsModel($conn);
$reviewsList = $reviewsModel->getAllReviews(); 

$reviewsArray = [];

if ($reviewsList && mysqli_num_rows($reviewsList) > 0) {
    while($row = mysqli_fetch_assoc($reviewsList)){
        $reviewsArray[] = [
            'id' => $row['id'], 
            'name' => htmlspecialchars($row['visitor_name'] ?? 'Pengunjung'),
            'date' => date('d M Y', strtotime($row['visit_date'] ?? date('Y-m-d'))),
            'rating' => (int)($row['rating'] ?? 5),
            'comment' => htmlspecialchars($row['comment'] ?? '')
        ];
    }
}

require_once 'templates/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    :root {
        --text-color: <?= $text_color ?>;
        --header-text-color: <?= $header_text_color ?>;
    }

    [v-cloak] { display: none !important; }
    
    h1, h2, h3, h4, h5, h6, .text-dark {
        color: var(--header-text-color) !important;
    }
    
    p, label, .text-muted, .text-secondary {
        color: var(--text-color) !important;
    }

    .text-white { color: #ffffff !important; }
    .text-warning { color: #ffc107 !important; } 
    
    .star-icon { fill: currentColor; }
    .cursor-pointer { cursor: pointer; }
    .star-hover { transition: transform 0.2s ease, color 0.2s ease; }
    .star-hover:hover { transform: scale(1.15); }
    
    .list-enter-active, .list-leave-active { transition: all 0.4s ease; }
    .list-enter-from, .list-leave-to { opacity: 0; transform: translateY(15px); }
    .list-move { transition: transform 0.4s ease; }
    
    .filter-btn { transition: all 0.3s ease; border: 1px solid #dee2e6; background-color: white; color: var(--text-color); font-weight: 600; font-size: 0.8rem;}
    .filter-btn:hover { background-color: #f8f9fa; border-color: #ced4da; }
    
    .filter-btn.active-all { 
        background-color: <?= $theme_color ?>; 
        color: white !important; 
        border-color: <?= $theme_color ?>; 
        box-shadow: 0 4px 10px rgba(37,71,148,0.3); 
    }
    
    .filter-btn.active-star { 
        background-color: #d97706; 
        color: white !important; 
        border-color: #d97706; 
        box-shadow: 0 4px 10px rgba(217,119,6,0.3); 
    }
    
    .page-nav-btn {
        width: 50px; height: 50px; border-radius: 50%;
        background-color: <?= $theme_color ?>; 
        color: white !important; border: none;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 15px rgba(37, 71, 148, 0.4);
    }
    .page-nav-btn:disabled {
        background: #e2e8f0; color: #94a3b8 !important; box-shadow: none; cursor: not-allowed; transform: scale(0.95);
    }
    .page-nav-btn:not(:disabled):hover {
        transform: translateY(-3px) scale(1.05); box-shadow: 0 8px 20px rgba(37, 71, 148, 0.5);
    }
    
    .page-numbers-container {
        background-color: #1e293b; 
        border-radius: 50px; padding: 6px 16px;
        display: flex; gap: 8px; align-items: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
    }
    .page-num-btn {
        background: transparent; border: none; 
        color: rgba(255,255,255,0.7) !important; 
        font-weight: 700; font-size: 1rem;
        width: 38px; height: 38px; border-radius: 50%; transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center;
    }
    .page-num-btn:hover { color: white !important; }
    
    .page-num-btn.active {
        background-color: <?= $theme_color ?>; 
        color: white !important;
        box-shadow: 0 4px 10px rgba(37,71,148,0.3); transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 1.8rem !important; letter-spacing: 1px !important; }
        .hero-subtitle { font-size: 1rem !important; }
        .filter-btn { padding: 6px 14px !important; font-size: 0.75rem !important; }
        .review-card { padding: 1.25rem !important; }
        .write-review-card { margin-top: 2rem !important; padding: 1.5rem !important; }
        .page-nav-btn { width: 40px !important; height: 40px !important; }
        .page-num-btn { width: 32px !important; height: 32px !important; font-size: 0.9rem !important; }
        .page-numbers-container { padding: 4px 12px !important; }
    }
</style>

<main id="app" v-cloak class="w-100 bg-light pb-5 font-plus-jakarta-sans min-vh-100">
    
    <section class="container pt-5 pb-4 text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in-up mt-4">
                <h1 class="font-cinzel fw-bold text-dark text-uppercase mb-3 hero-title" style="font-size: 2.5rem; letter-spacing: 2px;">
                    Pengalaman Pengunjung
                </h1>
                <p class="text-secondary fs-5 hero-subtitle">
                    Baca apa kata mereka tentang kunjungan ke Citra Niaga, atau bagikan pengalaman serumu sendiri di sini.
                </p>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row g-5">
            
            <div class="col-lg-8 fade-in-up">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center gap-3">
                        <i data-lucide="message-square" style="width: 24px; height: 24px; color: <?= $theme_color ?>;"></i>
                        <h2 class="font-cinzel h5 fw-bold text-dark text-uppercase mb-0" style="letter-spacing: 1px;">Ulasan Pengunjung</h2>
                    </div>
                    <span class="badge text-white rounded-pill px-3 py-2" style="background-color: <?= $theme_color ?>;">{{ filteredReviews.length }} Ulasan</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button @click="filterRating = 'all'" :class="filterRating === 'all' ? 'active-all' : ''" class="btn rounded-pill px-4 py-2 text-uppercase filter-btn">
                        Semua
                    </button>
                    
                    <button v-for="star in [5,4,3,2,1]" :key="'filter-'+star" @click="filterRating = star" :class="filterRating === star ? 'active-star' : ''" class="btn rounded-pill px-4 py-2 d-flex align-items-center gap-1 filter-btn">
                        {{ star }} 
                        <svg style="width:14px;height:14px;margin-bottom:2px" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </button>
                </div>

                <transition-group name="list" tag="div" class="d-flex flex-column gap-4" appear>
                    <div v-for="rev in paginatedReviews" :key="rev.id" class="bg-white rounded-4 p-4 border shadow-sm transition hover-shadow position-relative overflow-hidden review-card">
                        
                        <div class="position-absolute" style="top: -10px; right: 10px; color: <?= $theme_color ?>; opacity: 0.03; transform: rotate(10deg);">
                            <svg width="100" height="100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-3 position-relative z-1">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm shrink-0" style="width: 45px; height: 45px; background: linear-gradient(135deg, <?= $theme_color ?>, #3b82f6); color: #ffffff !important; font-size: 1.1rem; border: 2px solid #e2e8f0;">
                                    {{ rev.name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <h3 class="h6 fw-bold text-dark mb-0">{{ rev.name }}</h3>
                                    <p class="small text-muted text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        {{ rev.date }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-1 mt-2 mt-sm-0 stars-display" style="color: #f59e0b;">
                                <svg v-for="star in 5" :key="'star-'+rev.id+'-'+star" :class="star <= rev.rating ? 'text-warning' : 'text-light'" class="star-icon" style="width: 18px; height: 18px;" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-secondary mb-0 position-relative z-1" style="font-size: 1rem; line-height: 1.6;">
                            "{{ rev.comment }}"
                        </p>
                    </div>
                </transition-group>
                
                <div v-if="filteredReviews.length === 0" class="text-center py-5 bg-white rounded-4 border shadow-sm mt-3">
                    <p class="text-muted fst-italic mb-0">
                        Belum ada ulasan untuk kategori <span v-if="filterRating !== 'all'">bintang {{ filterRating }}</span> ini.
                    </p>
                </div>

                <div v-if="totalPages > 1" class="d-flex justify-content-center align-items-center gap-3 md:gap-4 mt-5">
                    
                    <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="page-nav-btn">
                        <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i>
                    </button>

                    <div class="page-numbers-container">
                        <button v-for="page in totalPages" :key="'page-'+page" 
                                @click="goToPage(page)" 
                                :class="['page-num-btn', { active: currentPage === page }]">
                            {{ page }}
                        </button>
                    </div>

                    <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="page-nav-btn">
                        <i data-lucide="arrow-right" style="width: 20px; height: 20px;"></i>
                    </button>

                </div>
            </div>

            <div class="col-lg-4 fade-in-up">
                <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top write-review-card" style="top: 100px; z-index: 10; border-radius: 16px !important;">
                    <h2 class="font-cinzel h5 fw-bold text-dark text-uppercase mb-4 text-center border-bottom pb-3">Tulis Ulasan</h2>
                    
                    <?php if(isset($_SESSION['role'])) : ?>
                        <form action="../controllers/ReviewsController.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Anda</label>
                                <input type="text" name="visitor_name" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" class="form-control form-control-lg fs-6 rounded-3" readonly required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Penilaian</label>
                                <div class="d-flex gap-2" @mouseleave="hoverRating = 0" style="color: #f59e0b;">
                                    <input type="hidden" name="rating" :value="userRating">
                                    <svg v-for="n in 5" :key="'form-star-'+n" 
                                         @click="setRating(n)" 
                                         @mouseover="hoverRating = n"
                                         :class="n <= (hoverRating || userRating) ? 'text-warning' : 'text-light'" 
                                         class="cursor-pointer star-hover star-icon" style="width: 28px; height: 28px;" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Tanggal Kunjungan</label>
                                <input type="date" name="visit_date" class="form-control rounded-3 bg-light" value="<?= date('Y-m-d'); ?>" readonly required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Pengalaman Anda</label>
                                <textarea name="comment" placeholder="Apa yang paling Anda sukai dari Citra Niaga?" rows="4" class="form-control rounded-3 fs-6 resize-none" required></textarea>
                            </div>

                            <button type="submit" name="tambah" class="btn w-100 rounded-pill py-3 fw-bold text-uppercase shadow-lg text-white" style="background-color: <?= $theme_color ?>; border: none; transition: transform 0.3s;">
                                Kirim Ulasan
                            </button>
                        </form>

                    <?php else : ?>
                        <div class="text-center py-4">
                            <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                                <i data-lucide="lock" class="text-secondary" style="width: 32px; height: 32px;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Akses Terkunci</h6>
                            <p class="text-secondary small mb-4">Silakan login sebagai pengunjung untuk dapat membagikan pengalaman Anda.</p>
                            <a href="login.php" class="btn w-100 rounded-pill fw-bold text-white shadow-sm" style="background-color: <?= $theme_color ?>;">
                                Login Sekarang
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</main>

<script>
    Vue.createApp({
        data() {
            return {
                reviews: <?php echo json_encode($reviewsArray); ?>,
                userRating: 5,
                hoverRating: 0,
                filterRating: 'all',
                currentPage: 1,
                itemsPerPage: 10 
            }
        },
        computed: {
            filteredReviews() {
                if (this.filterRating === 'all') {
                    return this.reviews;
                }
                return this.reviews.filter(rev => rev.rating === this.filterRating);
            },
            totalPages() {
                return Math.ceil(this.filteredReviews.length / this.itemsPerPage);
            },
            paginatedReviews() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredReviews.slice(start, end);
            }
        },
        watch: {
            filterRating() {
                this.currentPage = 1;
            }
        },
        methods: {
            setRating(val) {
                this.userRating = val;
            },
            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    window.scrollTo({ top: 300, behavior: 'smooth' });
                }
            }
        },
        mounted() {
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        },
        updated() {
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>