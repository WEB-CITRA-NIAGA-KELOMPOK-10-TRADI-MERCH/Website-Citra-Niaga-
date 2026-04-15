<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Daftar Akun - Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-brand-blue p-6 text-center">
            <div class="inline-flex p-3 bg-white/20 rounded-full mb-3 backdrop-blur-sm">
                <i data-lucide="user-plus" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="font-cinzel text-2xl font-bold text-white tracking-wide">CITRA NIAGA</h2>
            <p class="text-blue-100 mt-1 text-sm">Bergabung untuk membagikan pengalaman Anda</p>
        </div>

        <div class="p-8">
            
            <?php if(isset($_GET['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-start gap-3 border border-red-100 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                    <div class="text-sm font-medium">
                        <?php 
                            if($_GET['error'] == 'exists') echo 'Username atau Email sudah terdaftar! Silakan gunakan yang lain.';
                            elseif($_GET['error'] == 'short') echo 'Password terlalu pendek! Minimal harus 8 karakter.';
                            else echo 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="../controllers/AuthController.php?action=register" method="POST">
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-xs tracking-wider">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" name="username" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="Pilih username" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-xs tracking-wider">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-xs tracking-wider">Password (Min. 8 Karakter)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="reg-password" name="password" minlength="8" class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors" placeholder="Minimal 8 karakter" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePassword('reg-password', 'reg-eye')">
                            <i data-lucide="eye" id="reg-eye" class="h-5 w-5 text-gray-400 hover:text-brand-blue transition-colors"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" name="register" class="w-full bg-brand-blue text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-800 transition-colors shadow-md flex justify-center items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="login.php" class="text-brand-blue font-bold hover:underline">Login di sini</a>
                </p>
            </div>
            <div class="mt-4 text-center">
                <a href="index.php" class="text-sm text-gray-400 hover:text-gray-600 flex items-center justify-center gap-1 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
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