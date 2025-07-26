<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Habit Tracker 3.0') ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- 🧠 Omar BYA - Revolutionary Design System -->
    <link rel="stylesheet" href="<?= base_url('assets/css/omar-style.css') ?>">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Habit Tracker 3.0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
</head>
<body class="min-h-screen">
    
    <!-- 🎨 Revolutionary Navigation Bar -->
    <?php if (session()->get('isLoggedIn')): ?>
        <nav class="glass-container m-6 p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="<?= site_url('dashboard') ?>" class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                        ⚡ Habit Tracker 3.0
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-3">
                        <span class="text-white/80 text-sm">Welcome back,</span>
                        <span class="text-cyan-400 font-semibold"><?= esc(session()->get('username')) ?></span>
                    </div>
                    
                    <a href="<?= site_url('auth/logout') ?>" class="btn-modern btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </a>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    
    <!-- 📢 Modern Toast Notifications - Omar Style -->
    <script>
    // 🤖 Optimized by O.B. - Auto-show flash messages as toasts
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            setTimeout(() => {
                OmarNotifications.success('<?= addslashes(session()->getFlashdata('success')) ?>');
            }, 300);
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            setTimeout(() => {
                OmarNotifications.error('<?= addslashes(session()->getFlashdata('error')) ?>');
            }, 300);
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('warning')): ?>
            setTimeout(() => {
                OmarNotifications.warning('<?= addslashes(session()->getFlashdata('warning')) ?>');
            }, 300);
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('info')): ?>
            setTimeout(() => {
                OmarNotifications.info('<?= addslashes(session()->getFlashdata('info')) ?>');
            }, 300);
        <?php endif; ?>
        
        // Show validation errors as toasts
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <?php foreach ($validation->getErrors() as $error): ?>
                setTimeout(() => {
                    OmarNotifications.error('<?= addslashes($error) ?>');
                }, 300);
            <?php endforeach; ?>
        <?php endif; ?>
    });
    </script>
    
    <!-- 🎯 Main Content Area -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- 🌟 Footer -->
    <footer class="mt-12 mb-6">
        <div class="max-w-6xl mx-auto px-6">
            <div class="glass-container p-6 text-center">
                <p class="text-gray-300 text-sm">
                    © 2025 Habit Tracker 3.0 | Powered by 
                    <span class="text-cyan-400 font-semibold">Omar BYA Development</span>
                </p>
                <p class="text-gray-400 text-xs mt-2">
                    🚀 Revolutionary Design v3.0 | Built with ❤️ and advanced glassmorphism
                </p>
            </div>
        </div>
    </footer>

    <!-- 🧠 Omar BYA - Revolutionary JavaScript Stack -->
    <script src="<?= base_url('assets/js/omar-utils.js') ?>"></script>
    <script src="<?= base_url('assets/js/omar-notifications.js') ?>"></script>
    
    <!-- Scripts -->
    <?= $this->renderSection('scripts') ?>
    
    <script>
    // 🤖 Optimized by O.B. - Global interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide flash messages
        const flashMessages = document.querySelectorAll('.fade-in');
        flashMessages.forEach(message => {
            if (message.textContent.includes('successfully') || message.textContent.includes('Successfully')) {
                setTimeout(() => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(() => message.remove(), 300);
                }, 5000);
            }
        });
    });
    </script>
</body>
</html> 