<?php
session_start();
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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-50 h-screen overflow-hidden flex">

    <div class="hidden lg:flex lg:w-5/12 relative flex-col justify-center items-center text-white p-12 overflow-hidden" style="background-color: #254794;">
        <div class="absolute inset-0 bg-[url('../assets/img/login-bg.jpg')] opacity-20 bg-cover bg-center mix-blend-overlay"></div>
        
        <div class="relative z-10 w-full max-w-md text-center">
            <div class="mx-auto w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20">
                <i data-lucide="map-pin" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="font-cinzel text-4xl font-bold mb-2 tracking-wider">CITRA NIAGA</h1>
            <p class="text-blue-100 mb-12 text-lg">Pusat Kebudayaan & Perdagangan</p>
            
            <div class="grid grid-cols-2 gap-4 text-left">
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">5+</div>
                    <div class="text-sm text-blue-200">Destinasi</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">12+</div>
                    <div class="text-sm text-blue-200">Total Kios</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">20+</div>
                    <div class="text-sm text-blue-200">Total Galeri</div>
                </div>
                <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20">
                    <div class="text-2xl font-bold">4.6★</div>
                    <div class="text-sm text-blue-200">Avg Rating</div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-7/12 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white">
        <div class="w-full max-w-md">
            
            <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-red-100 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Username atau Password salah!</span>
            </div>
            <?php endif; ?>

            <?php if(isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
            <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 flex items-center gap-3 border border-green-100 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="text-sm font-medium">Akun berhasil dibuat! Silakan login.</span>
            </div>
            <?php endif; ?>

            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">SELAMAT DATANG</h2>
                <p class="text-gray-500">Masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <form action="../controllers/AuthController.php" method="POST" class="space-y-6">
                
                <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($referer); ?>">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="username" class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#254794] focus:border-transparent outline-none transition" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="login-password" name="password" class="w-full pl-11 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#254794] focus:border-transparent outline-none transition" placeholder="••••••••" required>
                        
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('login-password', 'login-eye')">
                            <i data-lucide="eye" id="login-eye" class="h-5 w-5 text-gray-400 hover:text-[#254794] transition-colors"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" name="login" class="w-full py-4 px-6 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2" style="background-color: #254794;">
                    <i data-lucide="log-in" class="w-5 h-5"></i> Masuk / Login
                </button>
            </form>

            <div class="mt-8 text-center border-t pt-6">
                <p class="text-sm text-gray-600 mb-4">
                    Belum punya akun? 
                    <a href="register.php" class="font-bold hover:underline" style="color: #254794;">Daftar di sini</a>
                </p>
                <a href="index.php" class="text-sm text-gray-500 hover:text-[#254794] font-medium inline-flex items-center gap-2 transition">
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