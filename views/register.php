<?php
session_start();

require_once '../config/koneksi.php';
/** @var mysqli $conn */

require_once '../models/SettingsModel.php'; 
$settingsModel = new SettingsModel($conn);
$web_setting = $settingsModel->getSettings(); 

$theme_color = !empty($web_setting['theme_color']) ? htmlspecialchars($web_setting['theme_color']) : '#254794';
$font_family = !empty($web_setting['font_family']) ? htmlspecialchars($web_setting['font_family']) : 'Plus Jakarta Sans';
$text_color = !empty($web_setting['text_color']) ? htmlspecialchars($web_setting['text_color']) : '#333333';
$header_text_color = !empty($web_setting['header_text_color']) ? htmlspecialchars($web_setting['header_text_color']) : '#333333';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Daftar Akun - Citra Niaga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lora:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --theme-color: <?= $theme_color ?>;
            --font-custom: '<?= $font_family ?>', sans-serif;
            --text-color: <?= $text_color ?>;
            --header-text-color: <?= $header_text_color ?>;
        }
        
        body { font-family: var(--font-custom) !important; }
        .font-cinzel { font-family: 'Cinzel', serif !important; }
        
        label, .text-gray-700 { color: var(--header-text-color) !important; }
        p.text-gray-600, a.text-gray-400, i.text-gray-400, input { color: var(--text-color) !important; }
        
        .text-white { color: #ffffff !important; }
        .text-white\/80 { color: rgba(255, 255, 255, 0.8) !important; }
        .text-red-600 { color: #dc2626 !important; } 
        
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        
        .focus-ring-theme:focus { 
            --tw-ring-color: var(--theme-color) !important; 
            border-color: transparent !important;
        }
        .hover-text-theme:hover { color: var(--theme-color) !important; }
        .hover-bg-theme:hover { filter: brightness(0.9); }

        @media (max-width: 768px) {
            .form-container { padding: 1.5rem !important; }
            .header-title { font-size: 1.5rem !important; }
            .header-desc { font-size: 0.8rem !important; }
            .input-field { padding-top: 0.85rem !important; padding-bottom: 0.85rem !important; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gray-50">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-theme p-6 text-center">
            <div class="inline-flex p-3 bg-white/20 rounded-full mb-3 backdrop-blur-sm shadow-sm">
                <i data-lucide="user-plus" class="w-6 h-6 md:w-8 md:h-8 text-white"></i>
            </div>
            <h2 class="font-cinzel text-xl md:text-2xl font-bold text-white tracking-wide header-title">CITRA NIAGA</h2>
            <p class="text-white/80 mt-1 text-xs md:text-sm header-desc">Bergabung untuk membagikan pengalaman Anda</p>
        </div>

        <div class="p-8 form-container">
            
            <?php if(isset($_GET['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-start gap-3 border border-red-100 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5 shrink-0"></i>
                    <div class="text-sm font-medium">
                        <?php 
                            if($_GET['error'] == 'email_exists') echo 'Email ini sudah terdaftar! Silakan gunakan email lain atau login.';
                            else echo 'Terjadi kesalahan sistem saat registrasi. Silakan coba lagi.';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="../controllers/AuthController.php?action=register" method="POST" class="space-y-4 md:space-y-5">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-[10px] tracking-wider">Nama / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" id="reg-username" name="username" class="w-full pl-11 pr-4 py-3 md:py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus-ring-theme outline-none transition input-field" placeholder="Nama panggilan Anda" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-[10px] tracking-wider">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="email" id="reg-email" name="email" class="w-full pl-11 pr-4 py-3 md:py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus-ring-theme outline-none transition input-field" placeholder="nama@gmail.com" required>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-[10px] tracking-wider">Kata Sandi (Min. 8 Karakter)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="reg-password" name="password" minlength="8" class="w-full pl-11 pr-12 py-3 md:py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus-ring-theme outline-none transition input-field" placeholder="Minimal 8 karakter" required>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer" onclick="togglePassword('reg-password', 'reg-eye')">
                            <i data-lucide="eye" id="reg-eye" class="h-5 w-5 text-gray-400 hover-text-theme transition-colors"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" name="register" class="w-full bg-theme text-white font-bold py-3 md:py-3.5 px-4 rounded-xl hover-bg-theme transition-colors shadow-md hover:shadow-lg flex justify-center items-center gap-2 mt-4">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-6">
                <p class="text-sm text-gray-600">
                    Sudah memiliki akun? 
                    <a href="login.php" class="text-theme font-bold hover-text-theme hover:underline">Masuk di sini</a>
                </p>
            </div>
            <div class="mt-4 text-center">
                <a href="index.php" class="text-sm text-gray-400 hover-text-theme font-medium flex items-center justify-center gap-1 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const usernameInput = document.getElementById("reg-username");
            const emailInput = document.getElementById("reg-email");
            const passwordInput = document.getElementById("reg-password");

            function periksaUsername() {
                if (usernameInput.validity.valueMissing) {
                    usernameInput.setCustomValidity("Nama atau Username wajib diisi.");
                } else {
                    usernameInput.setCustomValidity(""); 
                }
            }

            function periksaEmail() {
                const polaEmail = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
                
                if (emailInput.value.trim() === "") {
                    emailInput.setCustomValidity("Alamat email wajib diisi.");
                } else if (!polaEmail.test(emailInput.value)) {
                    emailInput.setCustomValidity("Format email tidak lengkap. Harap sertakan domain yang valid (contoh: nama@gmail.com).");
                } else {
                    emailInput.setCustomValidity(""); 
                }
            }

            function periksaPassword() {
                if (passwordInput.validity.valueMissing) {
                    passwordInput.setCustomValidity("Kata sandi wajib diisi.");
                } else if (passwordInput.validity.tooShort) {
                    passwordInput.setCustomValidity("Kata sandi minimal harus terdiri dari 8 karakter.");
                } else {
                    passwordInput.setCustomValidity(""); 
                }
            }

            if (usernameInput) {
                usernameInput.addEventListener("input", periksaUsername);
                usernameInput.addEventListener("invalid", periksaUsername);
            }
            if (emailInput) {
                emailInput.addEventListener("input", periksaEmail);
                emailInput.addEventListener("invalid", periksaEmail);
            }
            if (passwordInput) {
                passwordInput.addEventListener("input", periksaPassword);
                passwordInput.addEventListener("invalid", periksaPassword);
            }
        });

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