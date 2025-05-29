<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto p-6">
    <div class="glass-container p-8 mb-8 fade-in">
        <div class="flex items-center mb-8">
            <a href="<?= site_url('dashboard') ?>" class="btn-modern btn-secondary mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white">Add New Habit</h1>
                <p class="text-gray-300 mt-1">Start tracking a new habit to break</p>
            </div>
        </div>
        
        <form action="<?= site_url('dashboard/habit/add') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="name" class="block text-white font-medium mb-3">Habit Name</label>
                <input type="text" name="name" id="name" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('name')) ? '!border-red-400' : '' ?>"
                       value="<?= old('name') ?>" placeholder="e.g., Smoking, Overeating, etc." required autofocus>
                
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('name') ?></p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="description" class="block text-white font-medium mb-3">Description (Optional)</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none <?= (isset($validation) && $validation->hasError('description')) ? '!border-red-400' : '' ?>"
                          placeholder="Why do you want to break this habit?"><?= old('description') ?></textarea>
                
                <?php if (isset($validation) && $validation->hasError('description')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('description') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= site_url('dashboard') ?>" class="btn-modern btn-secondary flex-1 py-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="add-button flex-1 py-3 text-lg font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Habit
                </button>
            </div>
        </form>
    </div>
    
    <!-- Initial Information Panel -->
    <div class="glass-container p-8 mb-8 fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-start space-x-4 mb-6">
            <div class="stat-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-white mb-2">Initial Settings</h3>
                <p class="text-gray-300 mb-4">Your new habit tracking will start with these default values:</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div class="text-center p-4 bg-white/10 rounded-xl">
                <div class="text-2xl font-bold text-white mb-1">Level 0</div>
                <div class="text-sm text-gray-300">Starting Level</div>
            </div>
            <div class="text-center p-4 bg-white/10 rounded-xl">
                <div class="text-2xl font-bold text-white mb-1">3 Days</div>
                <div class="text-sm text-gray-300">First Goal Duration</div>
            </div>
            <div class="text-center p-4 bg-white/10 rounded-xl">
                <div class="text-2xl font-bold text-white mb-1">100%</div>
                <div class="text-sm text-gray-300">Starting Success Rate</div>
            </div>
            <div class="text-center p-4 bg-white/10 rounded-xl">
                <div class="text-2xl font-bold text-white mb-1"><?= date('M d') ?></div>
                <div class="text-sm text-gray-300">Start Date</div>
            </div>
        </div>
    </div>
    
    <!-- Tips Section -->
    <div class="glass-container p-8 fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-start space-x-4">
            <div class="stat-icon bg-gradient-to-br from-yellow-400 to-yellow-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-white mb-3">💡 Pro Tip</h3>
                <p class="text-gray-300 leading-relaxed">
                    Only log the days you performed the habit. You don't need to add entries for days you successfully avoided it. 
                    The system will track your logged days and calculate your progress. The fewer entries you make, the more successful you are!
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 