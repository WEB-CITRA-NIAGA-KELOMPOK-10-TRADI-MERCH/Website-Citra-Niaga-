<?php
session_start();

require_once '../config/koneksi.php';
/** @var mysqli $conn */

// === PANGGIL PENGATURAN CMS BIAR LOGIN NYA DINAMIS ===
require_once '../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';
// ======================================================

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') { 
        header("Location: admin/dashboard.php"); 
    } else { 
        header("Location: index.php"); 
    }
    exit;
}

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
if (strpos($referer, 'login.php') !== false || strpos($referer, 'register.php') !== false) {
    $referer = 'index.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lora:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* SIHIR CSS DINAMIS */
        :root {
            --theme-color: <?= $theme_color ?>;
            --font-custom: '<?= $font_family ?>', sans-serif;
        }
        body { font-family: var(--font-custom) !important; }
        .font-cinzel { font-family: 'Cinzel', serif !important; }
        
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .focus-ring-theme:focus { 
            --tw-ring-color: var(--theme-color) !important; 
            border-color: transparent !important;
        }
        .hover-text-theme:hover { color: var(--theme-color) !important; }

        /* ======================================================== */
        /* --- FIX RESPONSIVE KHUSUS LAYAR HP (MOBILE DEVICES) ---  */
        /* ======================================================== */
        @media (max-width: 768px) {
            /* Menyesuaikan ukuran font biar gak tumpang tindih */
            .login-title { font-size: 1.75rem !important; }
            .login-desc { font-size: 0.9rem !important; }
            
            /* Kurangi padding form di HP biar lebih lega ke samping */
            .form-container { padding: 2.5rem 1.5rem !important; }
            
            /* Input form dibuat sedikit lebih compact */
            .input-field { padding-top: 0.85rem !important; padding-bottom: 0.85rem !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    <div class="hidden lg:flex lg:w-5/12 relative flex-col justify-center items-center text-white p-12 overflow-hidden bg-theme">
        <div class="absolute inset-0 bg-[url('../assets/img/login-bg.jpg')] opacity-20 bg-cover bg-center mix-blend-overlay"></div>
        
        <div class="relative z-10 w-full max-w-md text-center">
            <div class="mx-auto w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 shadow-sm">
                <i data-lucide="map-pin" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="font-cinzel text-4xl font-bold mb-2 tracking-wider">CITRA NIAGA</h1>
            <p class="text-white/80 mb-12 text-lg">Pusat Kebudayaan & Perdagangan</p>
            
            <div class="grid grid-cols-2 gap-4 text-left">
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">5+</div>
                    <div class="text-sm text-white/70">Destinasi</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">12+</div>
                    <div class="text-sm text-white/70">Total Kios</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">20+</div>
                    <div class="text-sm text-white/70">Total Galeri</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">4.6★</div>
                    <div class="text-sm text-white/70">Avg Rating</div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-7/12 flex items-center justify-center p-6 sm:p-12 lg:p-24 bg-white form-container">
        <div class="w-full max-w-md">
            
            <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-100 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                <span class="text-sm font-medium">Email atau Password salah!</span>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
            <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span class="text-sm font-medium">Akun berhasil dibuat! Silakan masuk menggunakan Email.</span>
            </div>
            <?php endif; ?>

            <div class="mb-8 md:mb-10 text-center lg:text-left">
                <div class="lg:hidden mx-auto w-16 h-16 bg-theme rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <i data-lucide="map-pin" class="w-8 h-8 text-white"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 md:mb-3 login-title">SELAMAT DATANG</h2>
                <p class="text-gray-500 login-desc">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <form action="../controllers/AuthController.php" method="POST" class="space-y-5 md:space-y-6">
                
                <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($referer); ?>">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" class="w-full pl-11 pr-4 py-3 md:py-4 bg-gray-50 border border-gray-200 rounded-xl focus-ring-theme transition input-field" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="login-password" name="password" class="w-full pl-11 pr-12 py-3 md:py-4 bg-gray-50 border border-gray-200 rounded-xl focus-ring-theme transition input-field" placeholder="••••••••" required>
                        
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('login-password', 'login-eye')">
                            <i data-lucide="eye" id="login-eye" class="h-5 w-5 text-gray-400 hover-text-theme transition-colors"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" name="login" class="w-full py-3 md:py-4 px-6 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2 bg-theme mt-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i> Masuk / Login
                </button>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-600 mb-4">
                    Belum punya akun? 
                    <a href="register.php" class="font-bold hover-text-theme text-theme hover:underline transition-colors">Daftar di sini</a>
                </p>
                <a href="index.php" class="text-sm text-gray-500 hover-text-theme font-medium inline-flex items-center gap-2 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke website publik
                </a>
            </div>
            
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("data-lucide", "eye-off");
            } else {
                input.type = "password";
                icon.setAttribute("data-lucide", "eye");
            }
            lucide.createIcons();
        }
        lucide.createIcons();
    </script>
</body>
</html>