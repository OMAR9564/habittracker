<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-md mx-auto p-6">
    <div class="glass-container p-8 fade-in">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Sign Up</h2>
            <p class="text-gray-300">Join Habit Tracker 3.0 Community</p>
        </div>
        
        <form action="<?= site_url('auth/register') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="username" class="block text-white font-medium mb-3">Username</label>
                <input type="text" name="username" id="username" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('username')) ? '!border-red-400' : '' ?>"
                       value="<?= old('username') ?>" placeholder="Choose a username" required autofocus>
                
                <?php if (isset($validation) && $validation->hasError('username')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('username') ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-gray-300">Must be at least 3 characters long.</p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="pin" class="block text-white font-medium mb-3">PIN Code (3-8 digits)</label>
                <input type="password" name="pin" id="pin" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('pin')) ? '!border-red-400' : '' ?>"
                       minlength="3" maxlength="8" inputmode="numeric" pattern="[0-9]{3,8}" placeholder="Create your PIN" required>
                
                <?php if (isset($validation) && $validation->hasError('pin')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('pin') ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-gray-300">Ultra-flexible PIN system: 3-8 digits.</p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="confirm_pin" class="block text-white font-medium mb-3">Confirm PIN</label>
                <input type="password" name="confirm_pin" id="confirm_pin" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('confirm_pin')) ? '!border-red-400' : '' ?>"
                       minlength="3" maxlength="8" inputmode="numeric" pattern="[0-9]{3,8}" placeholder="Confirm your PIN" required>
                
                <?php if (isset($validation) && $validation->hasError('confirm_pin')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('confirm_pin') ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-gray-300">Re-enter your PIN for confirmation.</p>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="w-full add-button py-3 text-lg font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Create Account
            </button>
            
            <p class="text-center text-gray-300 mt-6">
                Already have an account? 
                <a href="<?= site_url('auth') ?>" class="text-cyan-400 hover:text-cyan-300 font-semibold underline transition-colors">Sign In</a>
            </p>
        </form>
    </div>
    
    <!-- Security Tips -->
    <div class="mt-8 grid grid-cols-1 gap-4">
        <div class="glass-container p-6 fade-in" style="animation-delay: 0.2s;">
            <div class="flex items-start space-x-3">
                <div class="stat-icon bg-gradient-to-br from-green-400 to-green-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-2">Ultra-Flexible PIN System</h3>
                    <p class="text-sm text-gray-300">
                        Choose between 3-8 digits for your PIN. Shorter PINs are more convenient but less secure. 
                        Longer PINs provide better security.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-container p-6 fade-in" style="animation-delay: 0.4s;">
            <div class="flex items-start space-x-3">
                <div class="stat-icon bg-gradient-to-br from-yellow-400 to-yellow-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-2">Security Recommendation</h3>
                    <p class="text-sm text-gray-300">
                        For better security, we recommend using at least 6 digits. 
                        Never share your PIN with anyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 