<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Alışkanlık Takipçisi') ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- 🧠 Omar BYA - Custom Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/omar-style.css') ?>">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#7c3aed">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Alışkanlık Takipçisi">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= site_url('dashboard') ?>" class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-purple-600">🎯 Alışkanlık Takipçisi</h1>
                    </a>
                </div>
                
                <?php if (session()->get('isLoggedIn')): ?>
                <div class="flex items-center space-x-4">
                    <a href="<?= site_url('dashboard') ?>" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">
                        Kontrol Paneli
                    </a>
                    <a href="<?= site_url('dashboard/habit/add') ?>" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium transition">
                        Yeni Alışkanlık
                    </a>
                    <a href="<?= site_url('auth/logout') ?>" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition">
                        Çıkış
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('message')): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg fade-in">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg fade-in">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Content Section -->
        <div class="px-4 sm:px-0">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; <?= date('Y') ?> Alışkanlık Takipçisi. 
                <!-- 🤖 Optimized by O.B. -->
                <span class="text-purple-600">Omar BYA</span> tarafından geliştirildi.</p>
            </div>
        </div>
    </footer>

    <!-- 🧠 Omar BYA - Custom JavaScript -->
    <script src="<?= base_url('assets/js/omar-utils.js') ?>"></script>
    <script src="<?= base_url('assets/js/omar-notifications.js') ?>"></script>
    
    <!-- Additional Scripts Section -->
    <?= $this->renderSection('scripts') ?>

    <!-- Flash Messages to Toast Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Convert flash messages to toast notifications
            <?php if (session()->getFlashdata('message')): ?>
                OmarNotifications.success('<?= addslashes(session()->getFlashdata('message')) ?>');
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                OmarNotifications.error('<?= addslashes(session()->getFlashdata('error')) ?>');
            <?php endif; ?>
            
            // Hide flash message containers since we're using toasts
            document.querySelectorAll('.fade-in').forEach(el => {
                if (el.textContent.includes('<?= session()->getFlashdata('message') ?>') || 
                    el.textContent.includes('<?= session()->getFlashdata('error') ?>')) {
                    el.style.display = 'none';
                }
            });
        });
    </script>

    <!-- Service Worker Registration -->
    <script>
        // TODO: PWA Service Worker implementation
        if ('serviceWorker' in navigator) {
            // NOTE: Service worker will be implemented later
            OmarUtils.logTrace('Service Worker support detected');
        }
    </script>
</body>
</html> 