<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto p-6">
    <!-- 🎯 Revolutionary Dashboard Header -->
    <div class="dashboard-header fade-in">
        <h1 class="dashboard-title">⚡ Habit Tracker 3.0</h1>
        <p class="dashboard-subtitle">Revolutionize your habits with our advanced tracking system</p>
        
        <div class="flex justify-center mt-6">
            <a href="<?= site_url('dashboard/habit/add') ?>" class="add-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add New Habit
            </a>
        </div>
    </div>

    <?php if (empty($habits)): ?>
        <!-- 🎨 Modern Empty State -->
        <div class="empty-state fade-in">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="empty-title">Start Your Journey</h2>
            <p class="empty-subtitle">No habits tracked yet. Create your first habit and begin building a better you.</p>
            <a href="<?= site_url('dashboard/habit/add') ?>" class="add-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create First Habit
            </a>
        </div>
    <?php else: ?>

        <!-- 🤖 Optimized by O.B. - Revolutionary Statistics Grid -->
        <div class="stats-grid fade-in">
            <?php 
            $totalHabits = count($habits);
            $totalLevels = 0;
            $activeGoals = 0;
            $totalSuccess = 0;
            $totalStreakDays = 0;
            
            foreach ($habits as $habit) {
                $totalLevels += $habit['level'];
                
                // 🔧 Fixed Goal Progress calculation
                if (isset($habit['goals']) && !empty($habit['goals'])) {
                    foreach ($habit['goals'] as $goal) {
                        if (isset($goal['status']) && $goal['status'] === 'active') {
                            $activeGoals++;
                            break;
                        }
                    }
                }
                
                $totalSuccess += $habit['success_percentage'];
                
                if (isset($habit['streak_data'])) {
                    $totalStreakDays += $habit['streak_data']['current_streak'];
                }
            }
            
            $avgSuccess = $totalHabits > 0 ? $totalSuccess / $totalHabits : 0;
            ?>
            
            <div class="stat-card pulse">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="stat-value"><?= $totalHabits ?></div>
                <div class="stat-label">Tracked Habits</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="stat-value"><?= $totalLevels ?></div>
                <div class="stat-label">Total Levels</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                </div>
                <div class="stat-value"><?= $totalStreakDays ?></div>
                <div class="stat-label">Streak Days</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="stat-value"><?= $activeGoals ?></div>
                <div class="stat-label">Active Goals</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="stat-value"><?= number_format($avgSuccess, 0) ?>%</div>
                <div class="stat-label">Success Rate</div>
            </div>
        </div>
        
        <!-- 🎯 Revolutionary Habit Cards Grid -->
        <div class="habits-grid">
            <?php foreach ($habits as $index => $habit): ?>
                <div class="habit-card slide-in" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <!-- Header Section -->
                    <div class="habit-header">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="habit-title"><?= esc($habit['name']) ?></h2>
                            <div class="habit-level">Level <?= $habit['level'] ?></div>
                        </div>
                        
                        <?php if (!empty($habit['description'])): ?>
                            <p class="text-gray-300 text-sm opacity-90"><?= esc($habit['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Streak Section -->
                    <?php if (isset($habit['streak_data'])): ?>
                        <div class="streak-section">
                            <div class="streak-container">
                                <div class="streak-current">
                                    <div class="streak-current-value"><?= $habit['streak_data']['current_streak'] ?></div>
                                    <div class="streak-current-label">Current Streak</div>
                                </div>
                                <div class="text-2xl opacity-60">
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
                    
                    <!-- Progress Section -->
                    <div class="progress-section">
                        <div class="progress-header">
                            <span class="progress-label">Success Rate</span>
                            <span class="progress-value"><?= number_format($habit['success_percentage'], 1) ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $habit['success_percentage'] ?>%"></div>
                        </div>
                    </div>
                    
                    <!-- 🔧 Fixed Goal Section -->
                    <?php if (isset($habit['goals']) && !empty($habit['goals'])): ?>
                        <?php 
                        $activeGoal = null;
                        foreach ($habit['goals'] as $goal) {
                            if (isset($goal['status']) && $goal['status'] === 'active') {
                                $activeGoal = $goal;
                                break;
                            }
                        }
                        ?>
                        <?php if ($activeGoal): ?>
                            <div class="progress-section">
                                <?php 
                                $startDate = new DateTime($activeGoal['start_date']);
                                $today = new DateTime();
                                $daysPassed = $startDate->diff($today)->days;
                                $goalDays = $activeGoal['goal_days'];
                                $progress = min(100, ($daysPassed / $goalDays) * 100);
                                ?>
                                <div class="progress-header">
                                    <span class="progress-label">Goal Progress</span>
                                    <span class="progress-value"><?= $daysPassed ?>/<?= $goalDays ?> days</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- 📊 Enhanced Chart Section - Mobile First -->
                    <div class="chart-section" style="display: block !important;">
                        <div class="chart-controls">
                            <h3 class="text-sm font-semibold text-white/80 flex items-center gap-2">
                                📈 Progress Trend
                            </h3>
                            <div class="chart-period-selector">
                                <button class="chart-period-btn active" onclick="updateChartPeriod(<?= $habit['id'] ?>, 7, this)">7D</button>
                                <button class="chart-period-btn" onclick="updateChartPeriod(<?= $habit['id'] ?>, 14, this)">14D</button>
                                <button class="chart-period-btn" onclick="updateChartPeriod(<?= $habit['id'] ?>, 30, this)">30D</button>
                            </div>
                        </div>
                        <div class="chart-container" id="chartContainer<?= $habit['id'] ?>">
                            <canvas id="trendChart<?= $habit['id'] ?>"></canvas>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="habit-actions">
                        <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="btn-modern btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Details
                        </a>
                        <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="btn-modern btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Log Entry
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// 🤖 Optimized by O.B. - Revolutionary Dashboard Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add staggered animations
    const cards = document.querySelectorAll('.habit-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Enhanced hover effects
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Stat cards pulse effect
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('pulse');
        }, index * 200);
    });
    
    // 🔧 Enhanced Chart System with real data
    <?php foreach ($habits as $habit): ?>
        <?php if (!empty($habit['trend_data'])): ?>
            (function() {
                const ctx = document.getElementById('trendChart<?= $habit['id'] ?>');
                if (ctx && typeof Chart !== 'undefined') {
                    const chartContext = ctx.getContext('2d');
                    
                    // Use real trend_data from habit
                    let trendData = <?= json_encode($habit['trend_data']) ?>;
                    
                    if (trendData && Object.keys(trendData).length > 0) {
                        const labels = Object.keys(trendData).slice(-14); // Last 14 days
                        const data = Object.values(trendData).slice(-14);
                        
                        const chart = new Chart(chartContext, {
                            type: 'bar',
                            data: {
                                labels: labels.map(date => {
                                    const d = new Date(date);
                                    return d.getDate() + '/' + (d.getMonth() + 1);
                                }),
                                datasets: [{
                                    label: 'Günlük Kayıt',
                                    data: data,
                                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                    borderColor: '#3b82f6',
                                    borderWidth: 2,
                                    borderRadius: 6,
                                    borderSkipped: false
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleColor: '#ffffff',
                                        bodyColor: '#ffffff',
                                        borderColor: '#3b82f6',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            title: function(tooltipItems) {
                                                return 'Tarih: ' + tooltipItems[0].label;
                                            },
                                            label: function(context) {
                                                return 'Kayıt: ' + context.parsed.y;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        grid: { 
                                            display: false
                                        },
                                        ticks: {
                                            color: 'rgba(255, 255, 255, 0.7)',
                                            font: { size: 10, weight: '500' },
                                            maxTicksLimit: 7
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        grid: { 
                                            display: true,
                                            color: 'rgba(255, 255, 255, 0.08)',
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: 'rgba(255, 255, 255, 0.7)',
                                            font: { size: 10, weight: '500' },
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                        
                        // Store chart instance for period switching
                        window['habitChart<?= $habit['id'] ?>'] = chart;
                    }
                }
            })();
        <?php else: ?>
            // No trend_data available for habit <?= $habit['id'] ?>, show empty state
            (function() {
                const container = document.getElementById('chartContainer<?= $habit['id'] ?>');
                if (container) {
                    container.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.5);font-size:12px;">📊 Henüz veri yok</div>';
                }
            })();
        <?php endif; ?>
    <?php endforeach; ?>
    
    // 🔧 Chart period switching function - simplified for real data
    window.updateChartPeriod = function(habitId, days, button) {
        // Update button states
        const buttons = button.parentElement.querySelectorAll('.chart-period-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Get the chart instance
        const chart = window['habitChart' + habitId];
        if (!chart) {
            OmarNotifications.info('📊 Detaylı görünüm için Raporlar sayfasını kullanın');
            return;
        }
        
        // Get existing data and slice to match days
        const allLabels = chart.data.labels;
        const allData = chart.data.datasets[0].data;
        
        // Show last N days from available data
        const startIndex = Math.max(0, allLabels.length - days);
        chart.data.labels = allLabels.slice(startIndex);
        chart.data.datasets[0].data = allData.slice(startIndex);
        
        // Animate the update
        chart.update('active');
        
        OmarNotifications.info(`📊 ${days} günlük görünüm`);
    };
});
</script>
<?= $this->endSection() ?> 