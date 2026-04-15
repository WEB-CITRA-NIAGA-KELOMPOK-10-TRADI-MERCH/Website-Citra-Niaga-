<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/koneksi.php';

/** @var mysqli $conn */

require_once '../models/ReviewsModel.php'; 

$reviewsModel = new ReviewsModel($conn);
$reviewsList = $reviewsModel->getAllReviews(); 

$reviewsArray = [];

if ($reviewsList && mysqli_num_rows($reviewsList) > 0) {
    while($row = mysqli_fetch_assoc($reviewsList)){
        $reviewsArray[] = [
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

<style>
    [v-cloak] { display: none !important; }
    
    .star-icon { fill: currentColor; }
    .cursor-pointer { cursor: pointer; }
    .star-hover { transition: transform 0.2s ease, color 0.2s ease; }
    .star-hover:hover { transform: scale(1.15); }
    .list-enter-active, .list-leave-active { transition: all 0.5s ease; }
    .list-enter-from, .list-leave-to { opacity: 0; transform: translateY(20px); }
</style>

<main id="app" v-cloak class="w-100 bg-light pb-5 font-plus-jakarta-sans min-vh-100">
    
    <section class="container pt-5 pb-4 text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in-up mt-4">
                <h1 class="font-cinzel fw-bold text-dark text-uppercase mb-3" style="font-size: 2.5rem; letter-spacing: 2px;">
                    Pengalaman Pengunjung
                </h1>
                <p class="text-secondary fs-5">
                    Baca apa kata mereka tentang kunjungan ke Citra Niaga, atau bagikan pengalaman serumu sendiri di sini.
                </p>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row g-5">
            
            <div class="col-lg-8 fade-in-up">
                <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                    <i data-lucide="message-square" class="text-brand-blue" style="width: 24px; height: 24px;"></i>
                    <h2 class="font-cinzel h5 fw-bold text-dark text-uppercase mb-0" style="letter-spacing: 1px;">Ulasan Terbaru</h2>
                </div>

                <transition-group name="list" tag="div" class="d-flex flex-column gap-4" appear>
                    <div v-for="(rev, index) in reviews" :key="'rev-'+index" class="bg-white rounded-4 p-4 border shadow-sm transition hover-shadow">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="h6 fw-bold text-dark mb-1">{{ rev.name }}</h3>
                                <p class="small text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    {{ rev.date }}
                                </p>
                            </div>
                            
                            <div class="d-flex gap-1 text-danger mt-2 mt-sm-0">
                                <svg v-for="star in 5" :key="'star-'+index+'-'+star" :class="star <= rev.rating ? 'text-danger' : 'text-light'" class="star-icon" style="width: 16px; height: 16px;" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-secondary mb-0" style="font-size: 0.95rem; line-height: 1.6;">
                            "{{ rev.comment }}"
                        </p>
                    </div>
                </transition-group>
                
                <div v-if="reviews.length === 0" class="text-center py-5 bg-white rounded-4 border shadow-sm mt-3">
                    <i data-lucide="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                    <p class="text-muted fst-italic mb-0">Belum ada ulasan. Jadilah yang pertama!</p>
                </div>
            </div>

            <div class="col-lg-4 fade-in-up">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px; z-index: 10;">
                    <h2 class="font-cinzel h5 fw-bold text-dark text-uppercase mb-4 text-center border-bottom pb-3">Tulis Ulasan</h2>
                    
                    <?php 
                    if(isset($_SESSION['role'])) : 
                    ?>
                        <form action="../controllers/ReviewsController.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Nama Anda</label>
                                <input type="text" name="visitor_name" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" class="form-control form-control-lg fs-6 rounded-3" readonly required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Penilaian</label>
                                <div class="d-flex gap-2" @mouseleave="hoverRating = 0">
                                    <input type="hidden" name="rating" :value="userRating">
                                    <svg v-for="n in 5" :key="'form-star-'+n" 
                                         @click="setRating(n)" 
                                         @mouseover="hoverRating = n"
                                         :class="n <= (hoverRating || userRating) ? 'text-danger' : 'text-light'" 
                                         class="cursor-pointer star-hover star-icon" style="width: 28px; height: 28px;" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Tanggal Kunjungan</label>
                                <input type="date" name="visit_date" class="form-control rounded-3 bg-light" value="<?= date('Y-m-d'); ?>" readonly required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Pengalaman Anda</label>
                                <textarea name="comment" placeholder="Apa yang paling Anda sukai dari Citra Niaga?" rows="4" class="form-control rounded-3 fs-6 resize-none" required></textarea>
                            </div>

                            <button type="submit" name="tambah" class="btn btn-dark w-100 rounded-pill py-3 fw-bold text-uppercase shadow-sm" style="background-color: #254794;">
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
                            <a href="login.php" class="btn w-100 rounded-pill fw-bold" style="border: 2px solid #254794; color: #254794;">
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
                hoverRating: 0
            }
        },
        methods: {
            setRating(val) {
                this.userRating = val;
            }
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>