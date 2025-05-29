<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto p-6">
    <div class="glass-container p-8 mb-8 fade-in">
        <div class="flex items-center mb-8">
            <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="btn-modern btn-secondary mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white">Log Entry</h1>
                <p class="text-gray-300 mt-1">Record when you performed: <span class="text-cyan-400 font-semibold"><?= esc($habit['name']) ?></span></p>
            </div>
        </div>
        
        <form action="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="date" class="block text-white font-medium mb-3">Date</label>
                <input type="date" name="date" id="date" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('date')) ? '!border-red-400' : '' ?>"
                       value="<?= old('date') ?? date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                
                <?php if (isset($validation) && $validation->hasError('date')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('date') ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-gray-300">Select the date when you performed this habit.</p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="count" class="block text-white font-medium mb-3">Count (How many times?)</label>
                <input type="number" name="count" id="count" 
                       class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?= (isset($validation) && $validation->hasError('count')) ? '!border-red-400' : '' ?>"
                       value="<?= old('count') ?? 1 ?>" min="1" max="100" placeholder="1" required>
                
                <?php if (isset($validation) && $validation->hasError('count')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('count') ?></p>
                <?php else: ?>
                    <p class="mt-2 text-sm text-gray-300">Specify how many times you performed the habit on the selected date.</p>
                <?php endif; ?>
            </div>
            
            <div>
                <label for="notes" class="block text-white font-medium mb-3">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl py-3 px-4 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none <?= (isset($validation) && $validation->hasError('notes')) ? '!border-red-400' : '' ?>"
                          placeholder="Any notes about this entry..."><?= old('notes') ?></textarea>
                
                <?php if (isset($validation) && $validation->hasError('notes')): ?>
                    <p class="mt-2 text-sm text-red-400"><?= $validation->getError('notes') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="btn-modern btn-secondary flex-1 py-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="add-button flex-1 py-3 text-lg font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Entry
                </button>
            </div>
        </form>
    </div>
    
    <!-- Warning Sections -->
    <div class="space-y-4">
        <div class="glass-container p-6 fade-in" style="animation-delay: 0.2s;">
            <div class="flex items-start space-x-4">
                <div class="stat-icon bg-gradient-to-br from-yellow-400 to-yellow-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Important Information</h3>
                    <p class="text-gray-300">
                        Each habit log entry will slightly decrease your level and affect your success rate. 
                        This is a natural mechanism for progressing in the habit-breaking process.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-container p-6 fade-in" style="animation-delay: 0.4s;">
            <div class="flex items-start space-x-4">
                <div class="stat-icon bg-gradient-to-br from-red-400 to-red-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Attention: Goal Will Be Affected!</h3>
                    <p class="text-gray-300">
                        <strong>Adding this entry will reduce your goal duration by 1 day and reset your progress.</strong>
                        Every time you perform the bad habit, your goal duration shortens and you start over.
                        To successfully complete the goal, you must avoid this habit.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-container p-6 fade-in" style="animation-delay: 0.6s;">
            <div class="flex items-start space-x-4">
                <div class="stat-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">💡 Pro Tip</h3>
                    <p class="text-gray-300 leading-relaxed">
                        You're trying to break this habit - so you should <strong>only log the days you performed the habit</strong>.
                        Days you didn't perform it (successful days) are tracked automatically. The fewer entries you add, the more successful you are!
                        To complete your goal, avoid the habit and endure for the goal duration.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 