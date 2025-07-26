<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto p-6">
    <!-- 🎨 Revolutionary Hero Section with Glassmorphism -->
    <div class="glass-container p-12 mb-12 text-center fade-in">
        <h1 class="text-5xl font-black mb-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-cyan-500 bg-clip-text text-transparent">
            Habit Tracker 3.0
        </h1>
        <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
            The perfect tool to break bad habits and track your progress with revolutionary design
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8">
            <?php if (!session()->get('isLoggedIn')): ?>
                <a href="<?= site_url('auth/register') ?>" class="add-button text-lg px-8 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Get Started Now
                </a>
                <a href="<?= site_url('auth') ?>" class="btn-modern btn-secondary text-lg px-8 py-1 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Sign In
                </a>
            <?php else: ?>
                <a href="<?= site_url('dashboard') ?>" class="add-button text-lg px-8 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    My Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- 🎯 Revolutionary Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="glass-container p-8 text-center hover:scale-105 transition-all duration-300 fade-in">
            <div class="stat-icon mb-6 mx-auto bg-gradient-to-br from-green-400 to-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Simple Tracking</h2>
            <p class="text-gray-300">Easy and intuitive interface to track your habits. Just mark the days you performed the habit.</p>
        </div>
        
        <div class="glass-container p-8 text-center hover:scale-105 transition-all duration-300 fade-in" style="animation-delay: 0.2s;">
            <div class="stat-icon mb-6 mx-auto bg-gradient-to-br from-blue-400 to-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Visual Analytics</h2>
            <p class="text-gray-300">See your progress with beautiful charts. Easily observe drops, rises and stability patterns.</p>
        </div>
        
        <div class="glass-container p-8 text-center hover:scale-105 transition-all duration-300 fade-in" style="animation-delay: 0.4s;">
            <div class="stat-icon mb-6 mx-auto bg-gradient-to-br from-purple-400 to-purple-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Progressive Goals</h2>
            <p class="text-gray-300">Start with short-term goals and gradually progress. Each success provides motivation for the next goal.</p>
        </div>
    </div>
    
    <!-- 🚀 How It Works Section -->
    <div class="glass-container p-12 mb-12 fade-in">
        <h2 class="text-3xl font-bold text-center mb-12 text-white">How It Works?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full text-2xl font-bold">1</div>
                <h3 class="text-xl font-semibold mb-4 text-white">Sign Up</h3>
                <p class="text-gray-300">Secure your account with our ultra-flexible 3-8 digit PIN system.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full text-2xl font-bold">2</div>
                <h3 class="text-xl font-semibold mb-4 text-white">Add Habit</h3>
                <p class="text-gray-300">Define the habit you want to break and start tracking it.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full text-2xl font-bold">3</div>
                <h3 class="text-xl font-semibold mb-4 text-white">Daily Logging</h3>
                <p class="text-gray-300">Mark the days you performed your habit and see your statistics.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full text-2xl font-bold">4</div>
                <h3 class="text-xl font-semibold mb-4 text-white">Achieve Goals</h3>
                <p class="text-gray-300">Level up with your successes and complete your goals.</p>
            </div>
        </div>
    </div>
    
    <!-- 💬 Testimonial Section -->
    <div class="glass-container p-12 mb-12 text-center fade-in">
        <h2 class="text-3xl font-bold mb-8 text-white">What Our Users Say?</h2>
        
        <blockquote class="max-w-3xl mx-auto">
            <p class="text-xl italic mb-6 text-gray-200">"Thanks to this app, I've been struggling to quit smoking for 3 months. The level system and visual tracking really motivate me!"</p>
            <cite class="text-lg font-semibold text-white">- John D., New York</cite>
        </blockquote>
    </div>
    
    <!-- 🎯 CTA Section -->
    <div class="glass-container p-12 text-center fade-in">
        <h2 class="text-3xl font-bold text-white mb-6">Start Right Now!</h2>
        <p class="text-xl text-gray-300 mb-8">Take the first step to break your bad habits and track your progress.</p>
        
        <?php if (!session()->get('isLoggedIn')): ?>
            <a href="<?= site_url('auth/register') ?>" class="add-button text-lg px-8 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Create Free Account
            </a>
        <?php else: ?>
            <a href="<?= site_url('dashboard') ?>" class="add-button text-lg px-8 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Go to Dashboard
            </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?> 