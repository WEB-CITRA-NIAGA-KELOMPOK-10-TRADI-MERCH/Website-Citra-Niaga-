<?php require_once 'templates/header.php'; ?>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<main id="app" v-cloak class="w-100 bg-light pb-5 font-plus-jakarta-sans min-vh-100">
    
    <section class="bg-brand-blue pt-5 pb-5 text-center position-relative z-1 mt-5">
        <div class="container py-5 fade-in-up">
            <div class="mb-4 text-white d-flex justify-content-center">
                <i data-lucide="calendar" style="width: 40px; height: 40px; stroke-width: 1.5;"></i>
            </div>
            <h1 class="font-cinzel display-5 fw-bold text-white mb-3 text-uppercase tracking-widest">Gelar Acaramu</h1>
            <p class="text-white-50 fs-6 mx-auto mb-0" style="max-width: 700px;">
                Plaza terbuka Citra Niaga menyediakan latar belakang yang sempurna untuk pertunjukan budaya, pameran, dan pertemuan komunitas.
            </p>
        </div>
    </section>

    <div class="position-relative w-100 z-0" style="margin-top: -1px;">
        <svg viewBox="0 0 1440 100" class="w-100 h-auto" preserveAspectRatio="none">
            <path fill="#1e3a8a" d="M0,0 C320,80 720,80 1440,0 L1440,0 L0,0 Z"></path>
        </svg>
    </div>

    <div class="container mt-4 position-relative z-1">
        <div class="row g-4 items-start">
            
            <div class="col-lg-8 fade-in-up">
                <h2 class="font-cinzel h4 fw-bold text-dark text-uppercase tracking-wider mb-4">Pedoman Perizinan</h2>
                
                <transition-group name="list" tag="div" class="d-flex flex-column gap-3">
                    <div v-for="(guide, index) in guidelines" :key="index" 
                         class="bg-white rounded-4 p-3 shadow-sm border d-flex align-items-center gap-4 hover-scale transition">
                        <div class="rounded-circle bg-primary-subtle text-brand-blue fw-bold d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 35px; height: 35px; font-size: 0.8rem;">
                            {{ index + 1 }}
                        </div>
                        <p class="text-secondary mb-0 fw-medium" style="font-size: 0.9rem;">{{ guide }}</p>
                    </div>
                </transition-group>
            </div>

            <div class="col-lg-4 fade-in-up">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h3 class="font-cinzel h6 fw-bold text-dark text-uppercase mb-4">Kantor Pengelola</h3>
                    
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary-subtle text-brand-blue d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-1">Alamat</p>
                                <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.5;">
                                    Jl. Niaga Selatan No. 1, Pasar Pagi,<br>Samarinda Kota, Kota Samarinda,<br>Kalimantan Timur 75111
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary-subtle text-brand-blue d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i data-lucide="phone" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-1">+62 541-123456</p>
                                <p class="text-muted mb-0" style="font-size: 0.7rem;">Senin - Jumat, 08:00 - 16:00</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary-subtle text-brand-blue d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <p class="small fw-bold text-dark mb-1">Email</p>
                                <p class="text-brand-blue mb-0" style="font-size: 0.75rem;">info@citra-niaga-samarinda.id</p>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-2">
                            <p class="text-muted mb-1" style="font-size: 0.65rem;">Kontak Person:</p>
                            <p class="small fw-bold text-dark mb-0">Pengelola Citra Niaga Samarinda</p>
                            <p class="text-muted" style="font-size: 0.65rem;">Koordinator Acara</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h3 class="font-cinzel h6 fw-bold text-dark text-uppercase mb-3">FAQ</h3>
                    <p class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">Masih ada pertanyaan?</p>
                    <p class="text-muted small mb-0">Silakan hubungi kontak yang tertera untuk informasi lebih lanjut mengenai ketersediaan lokasi dan biaya.</p>
                </div>
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
        }
    }).mount('#app');
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php require_once 'templates/footer.php'; ?>