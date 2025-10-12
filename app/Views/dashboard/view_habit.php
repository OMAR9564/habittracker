<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- 🤖 Optimized by O.B. - Auto data refresh on page load -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh data when page loads
    setTimeout(function() {
        console.log('[OMAR-TRACE] Auto refreshing habit data...');
        // Automatically trigger data refresh in background
        fetch('<?= site_url('dashboard/habit/' . $habit['id'] . '/refresh-data') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                console.log('[OMAR-TRACE] Data refreshed successfully');
            }
        }).catch(error => {
            console.log('[OMAR-TRACE] Auto refresh failed:', error);
        });
    }, 1000);
});
</script>

<div class="max-w-7xl mx-auto p-6">
    <!-- 🎨 Modern Header with Glassmorphism -->
    <div class="glass-container p-6 mb-8 fade-in">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-4">
                <a href="<?= site_url('dashboard') ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl hover:shadow-lg transition-all hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        <?= esc($habit['name']) ?>
                    </h1>
                    <p class="text-gray-600 mt-1"><?= esc($habit['description'] ?? 'No description') ?></p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- 🧠 Omar BYA - Reports Button -->
                <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/reports') ?>" class="btn-modern btn-secondary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    📊 Raporlar
                </a>
                
                <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="add-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Log Entry
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 📊 Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 📈 Stats Overview -->
            <div class="stats-grid fade-in">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= $habit['level'] ?></div>
                    <div class="stat-label">Level</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= number_format($habit['success_percentage'], 1) ?>%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        </svg>
                    </div>
                    <div class="stat-value"><?= count($logs) ?></div>
                    <div class="stat-label">Total Logs</div>
                </div>
            </div>

            <!-- 🔥 Streak Information -->
            <?php if (isset($habit['streak_data'])): ?>
                <div class="glass-container p-6 fade-in">
                    <h2 class="text-xl font-semibold mb-4 text-white">🔥 Streak Information</h2>
                    <div class="streak-container">
                        <div class="streak-current">
                            <div class="streak-current-value"><?= $habit['streak_data']['current_streak'] ?></div>
                            <div class="streak-current-label">Current Streak</div>
                        </div>
                        <div class="text-4xl opacity-80">
                            <?php 
                            $streakData = $habit['streak_data'];
                            if ($streakData['current_streak'] === 0) {
                                echo '😞';
                            } elseif ($streakData['current_streak'] >= 30) {
                                echo '🏆';
                            } elseif ($streakData['current_streak'] >= 7) {
                                echo '🔥';
                            } elseif ($streakData['current_streak'] >= 3) {
                                echo '💪';
                            } else {
                                echo '🌱';
                            }
                            ?>
                        </div>
                        <div class="streak-max">
                            <div class="streak-max-value"><?= $habit['streak_data']['max_streak'] ?></div>
                            <div class="streak-current-label">Best Streak</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- 📊 Enhanced Success Chart with Scroll -->
            <div class="glass-container p-6 fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        📊 Success Chart
                        <span class="ml-2 text-sm text-gray-300 font-normal">(Scrollable)</span>
                    </h2>
                    <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/refresh') ?>" class="btn-modern btn-secondary text-sm px-3 py-1">
                        🔄 Refresh Data
                    </a>
                </div>
                
                <div class="chart-container" style="height: 300px; overflow-x: auto; overflow-y: hidden; background: rgba(255, 255, 255, 0.2);">
                    <canvas id="progressChart" style="min-width: 600px; height: 100%;"></canvas>
                </div>
                
                <p class="text-sm text-gray-300 mt-3 opacity-80">
                    💡 Tip: Scroll horizontally to view more data points
                </p>
            </div>
        </div>
        
        <!-- 📋 Sidebar -->
        <div class="space-y-6">
            <!-- 🎯 Active Goal -->
            <?php if (isset($activeGoal) && $activeGoal): ?>
                <div class="glass-container p-6 fade-in">
                    <h2 class="text-xl font-semibold mb-4 text-white flex items-center">
                        🎯 Active Goal
                    </h2>
                    
                    <?php 
                    $startDate = new DateTime($activeGoal['start_date']);
                    $today = new DateTime(date('Y-m-d'));
                    
                    $interval = $startDate->diff($today);
                    $daysPassed = $interval->days;
                    $goalDays = $activeGoal['goal_days'];
                    
                    if ($startDate > $today) {
                        $daysPassed = 0;
                    }
                    
                    $goalCompleted = $daysPassed >= $goalDays;
                    $progress = min(100, ($daysPassed / $goalDays) * 100);
                    $daysLeft = max(0, $goalDays - $daysPassed);
                    ?>
                    
                    <div class="mb-4">
                        <div class="progress-header mb-2">
                            <span class="progress-label text-white">Progress</span>
                            <span class="progress-value text-white"><?= number_format($progress, 0) ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-white/10 rounded-lg">
                            <div class="text-2xl font-bold text-white"><?= $goalDays ?></div>
                            <div class="text-xs text-gray-300">Goal Days</div>
                        </div>
                        <div class="text-center p-3 bg-white/10 rounded-lg">
                            <div class="text-2xl font-bold text-white"><?= $daysPassed ?></div>
                            <div class="text-xs text-gray-300">Days Passed</div>
                        </div>
                        <div class="text-center p-3 bg-white/10 rounded-lg">
                            <div class="text-2xl font-bold text-white"><?= $daysLeft ?></div>
                            <div class="text-xs text-gray-300">Days Left</div>
                        </div>
                        <div class="text-center p-3 bg-white/10 rounded-lg">
                            <div class="text-xl font-bold text-white"><?= date('d/m', strtotime($activeGoal['start_date'])) ?></div>
                            <div class="text-xs text-gray-300">Start Date</div>
                        </div>
                    </div>
                    
                    <?php if ($goalCompleted): ?>
                        <div class="mt-4 p-3 bg-green-500/20 border border-green-500/30 rounded-lg">
                            <p class="text-green-300 text-sm">🎉 Goal completed! Set a new challenge.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- 📈 Quick Stats -->
            <div class="glass-container p-6 fade-in">
                <h2 class="text-xl font-semibold mb-4 text-white">📈 Quick Stats</h2>
                
                <?php
                $totalLogs = count($logs);
                $totalCount = 0;
                foreach ($logs as $log) {
                    $totalCount += $log['count'];
                }
                $averagePerLog = $totalLogs > 0 ? $totalCount / $totalLogs : 0;
                ?>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Total Logs:</span>
                        <span class="text-white font-semibold"><?= $totalLogs ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Total Count:</span>
                        <span class="text-white font-semibold"><?= $totalCount ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Average per Log:</span>
                        <span class="text-white font-semibold"><?= number_format($averagePerLog, 1) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Current Level:</span>
                        <span class="text-white font-semibold">Level <?= $habit['level'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// 🤖 Optimized by O.B. - Enhanced Progress Chart with Scrolling
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('progressChart');
    if (ctx && typeof Chart !== 'undefined') {
        const chartContext = ctx.getContext('2d');
        
        // Generate extended data for scrolling (last 30 days)
        const logs = <?= json_encode($logs) ?>;
        const extendedData = {};
        
        // Generate last 30 days
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            const dateStr = date.toISOString().split('T')[0];
            extendedData[dateStr] = 0;
        }
        
        // Fill with actual log data
        logs.forEach(log => {
            if (extendedData.hasOwnProperty(log.date)) {
                extendedData[log.date] = log.count;
            }
        });
        
        const labels = Object.keys(extendedData);
        const data = Object.values(extendedData);
        
        new Chart(chartContext, {
            type: 'line',
            data: {
                labels: labels.map(date => {
                    const d = new Date(date);
                    return d.getDate() + '/' + (d.getMonth() + 1);
                }),
                datasets: [{
                    label: 'Success Count',
                    data: data,
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: { 
                            display: true,
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: { size: 11 },
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: { 
                            display: true,
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: { size: 11 }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?> 