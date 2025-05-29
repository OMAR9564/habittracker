<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto p-6">
    <div class="glass-container p-8 fade-in">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Sign In</h2>
            <p class="text-gray-300">Welcome back to Habit Tracker 3.0</p>
        </div>
        
        <form action="<?= site_url('auth/login') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="username" class="block text-white font-medium mb-3">Username</label>
                <input type="text" name="username" id="username" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       placeholder="Enter your username" required autofocus>
            </div>
            
            <div>
                <label for="pin" class="block text-white font-medium mb-3">PIN Code (3-8 digits)</label>
                <input type="password" name="pin" id="pin" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                       minlength="3" maxlength="8" inputmode="numeric" pattern="[0-9]{3,8}" placeholder="Enter your PIN" required>
                <p class="mt-2 text-sm text-gray-300">Enter your PIN code (3-8 digits).</p>
            </div>
            
            <button type="submit" class="w-full add-button py-3 text-lg font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Sign In
            </button>
            
            <p class="text-center text-gray-300 mt-6">
                Don't have an account? 
                <a href="<?= site_url('auth/register') ?>" class="text-cyan-400 hover:text-cyan-300 font-semibold underline transition-colors">Sign Up</a>
            </p>
        </form>
    </div>
    
    <!-- Security Information -->
    <div class="mt-8 glass-container p-6 fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-start space-x-3">
            <div class="stat-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-2">Security Information</h3>
                <p class="text-sm text-gray-300">
                    For your security, your account will be temporarily locked after 3 failed login attempts.
                    Never share your PIN code with anyone.
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 