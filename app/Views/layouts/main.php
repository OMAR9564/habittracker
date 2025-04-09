<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Alışkanlık Takipçisi' ?> - Alışkanlık Bırakma Takip Uygulaması</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Özel Stiller -->
    <style>
        .habit-progress {
            transition: width 0.5s ease-in-out;
        }
        
        .habit-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .habit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-purple-700 text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?= site_url('/') ?>" class="text-xl font-bold">Alışkanlık Takipçisi</a>
            
            <?php if (session()->get('isLoggedIn')): ?>
                <div class="flex items-center space-x-4">
                    <span>Merhaba, <?= session()->get('username') ?></span>
                    <a href="<?= site_url('dashboard') ?>" class="hover:underline">Kontrol Paneli</a>
                    <a href="<?= site_url('auth/logout') ?>" class="bg-purple-800 px-4 py-2 rounded hover:bg-purple-900 transition">Çıkış</a>
                </div>
            <?php else: ?>
                <div class="space-x-4">
                    <a href="<?= site_url('auth') ?>" class="hover:underline">Giriş</a>
                    <a href="<?= site_url('auth/register') ?>" class="bg-purple-800 px-4 py-2 rounded hover:bg-purple-900 transition">Kayıt Ol</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Ana İçerik -->
    <main class="container mx-auto px-4 py-6 flex-grow">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-purple-800 text-white py-4">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?= date('Y') ?> Alışkanlık Takipçisi - Alışkanlık Bırakma Takip Uygulaması</p>
        </div>
    </footer>
    
    <!-- Özel JavaScript -->
    <script>
        // Genel JavaScript fonksiyonları burada olabilir
        document.addEventListener('DOMContentLoaded', function() {
            // Uyarı mesajlarını otomatik kapat
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 1s ease';
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 5000);
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 