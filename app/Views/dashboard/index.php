<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-6">
    <h1 class="text-2xl font-bold mb-4 md:mb-0">Alışkanlık Takip Paneli</h1>
    
    <a href="<?= site_url('dashboard/habit/add') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Yeni Alışkanlık Ekle
    </a>
</div>

<?php if (empty($habits)): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h2 class="text-xl font-semibold mb-2">Henüz Alışkanlık Eklenmemiş</h2>
        <p class="text-gray-600 mb-6">Bırakmak istediğiniz alışkanlıkları ekleyerek takip etmeye başlayın.</p>
        <a href="<?= site_url('dashboard/habit/add') ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg inline-block transition">
            İlk Alışkanlığınızı Ekleyin
        </a>
    </div>
<?php else: ?>

    <!-- 🤖 Optimized by O.B. - Enhanced Statistics with Fixed Counters -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <?php 
        $totalHabits = count($habits);
        $totalLevels = 0;
        $activeGoals = 0;
        $totalSuccess = 0;
        $totalStreakDays = 0;
        
        foreach ($habits as $habit) {
            $totalLevels += $habit['level'];
            if (isset($habit['active_goal']) && $habit['active_goal']) {
                $activeGoals++;
            }
            $totalSuccess += $habit['success_percentage'];
            
            // 🎯 Streak calculation
            if (isset($habit['streak_data'])) {
                $totalStreakDays += $habit['streak_data']['current_streak'];
            }
        }
        
        $avgSuccess = $totalHabits > 0 ? $totalSuccess / $totalHabits : 0;
        ?>
        
        <div class="stats-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stats-label">Takip Edilen</p>
                    <h3 class="stats-value"><?= $totalHabits ?></h3>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stats-label">Toplam Seviye</p>
                    <h3 class="stats-value"><?= $totalLevels ?></h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- 🎯 NEW: Total Streak Days Counter -->
        <div class="stats-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stats-label">Toplam Streak</p>
                    <h3 class="streak-counter"><?= $totalStreakDays ?></h3>
                    <p class="text-xs text-gray-500">gün temiz</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stats-label">Aktif Hedefler</p>
                    <h3 class="stats-value"><?= $activeGoals ?></h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="stats-label">Ortalama Başarı</p>
                    <h3 class="stats-value"><?= number_format($avgSuccess, 1) ?>%</h3>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alışkanlık Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($habits as $habit): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden habit-card">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-xl font-semibold"><?= esc($habit['name']) ?></h2>
                        <span class="level-badge">Seviye <?= $habit['level'] ?></span>
                    </div>
                    
                    <!-- 🎯 Streak Display -->
                    <?php if (isset($habit['streak_data'])): ?>
                        <div class="mb-4">
                            <?php 
                            $streakData = $habit['streak_data'];
                            $streakClass = 'streak-badge';
                            
                            switch($streakData['streak_type']) {
                                case 'excellent': $streakClass .= ' streak-excellent'; break;
                                case 'good': $streakClass .= ' streak-good'; break;
                                case 'starting': $streakClass .= ' streak-starting'; break;
                                default: $streakClass .= ' streak-none'; break;
                            }
                            
                            $icon = '🌱';
                            $message = $streakData['current_streak'] . ' gün temiz';
                            
                            if ($streakData['current_streak'] === 0) {
                                $icon = '😔';
                                $message = 'Bugün yapıldı';
                            } elseif ($streakData['current_streak'] >= 30) {
                                $icon = '🏆';
                            } elseif ($streakData['current_streak'] >= 7) {
                                $icon = '🔥';
                            } elseif ($streakData['current_streak'] >= 3) {
                                $icon = '💪';
                            }
                            ?>
                            <div class="flex justify-between items-center">
                                <span class="<?= $streakClass ?>">
                                    <?= $icon ?> <?= $message ?>
                                </span>
                                <span class="text-xs text-gray-500">En yüksek: <?= $streakData['max_streak'] ?> gün</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($habit['description'])): ?>
                        <p class="text-gray-600 text-sm mb-4"><?= esc($habit['description']) ?></p>
                    <?php endif; ?>
                    
                    <!-- Başarı Göstergesi -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-500">Başarı</span>
                            <span class="text-xs font-semibold"><?= number_format($habit['success_percentage'], 1) ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="<?= $habit['success_percentage'] >= 70 ? 'bg-green-600' : ($habit['success_percentage'] >= 40 ? 'bg-yellow-500' : 'bg-red-500') ?> h-2 rounded-full habit-progress" style="width: <?= $habit['success_percentage'] ?>%"></div>
                        </div>
                    </div>
                    
                    <!-- Aktif Hedef Bilgisi -->
                    <?php if (isset($habit['active_goal']) && $habit['active_goal']): ?>
                        <div class="bg-blue-50 rounded-lg p-3 mb-4">
                            <p class="text-xs font-medium text-blue-800 mb-1">Aktif Hedef</p>
                            <?php 
                            $startDate = new DateTime($habit['active_goal']['start_date']);
                            $today = new DateTime();
                            $daysPassed = $startDate->diff($today)->days;
                            $goalDays = $habit['active_goal']['goal_days'];
                            $progress = min(100, ($daysPassed / $goalDays) * 100);
                            ?>
                            <div class="flex justify-between items-center text-sm">
                                <span><?= $daysPassed ?> gün / <?= $goalDays ?> gün</span>
                                <span><?= number_format($progress, 0) ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- İşlem Butonları -->
                    <div class="flex space-x-2">
                        <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded transition">
                            Detaylar
                        </a>
                        <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded transition">
                            Kayıt Ekle
                        </a>
                    </div>
                </div>
                
                <!-- 🤖 Optimized by O.B. - Scrollable Charts System -->
                <?php if (!empty($habit['trend_data'])): ?>
                    <div class="charts-container">
                        <div class="chart-item">
                            <div class="chart-wrapper">
                                <h4 class="text-sm font-semibold mb-2 text-gray-700">30 Günlük Trend</h4>
                                <canvas id="trendChart<?= $habit['id'] ?>" style="height: 140px;"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('trendChart<?= $habit['id'] ?>').getContext('2d');
                            const trendData = <?= json_encode($habit['trend_data']) ?>;
                            
                            const labels = Object.keys(trendData).slice(-15); // Son 15 gün
                            const data = Object.values(trendData).slice(-15);
                            
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels.map(date => {
                                        const d = new Date(date);
                                        return d.getDate() + '/' + (d.getMonth() + 1);
                                    }),
                                    datasets: [{
                                        label: 'Başarı Puanı',
                                        data: data,
                                        borderColor: 'rgba(124, 58, 237, 0.8)',
                                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                        tension: 0.4,
                                        fill: true,
                                        borderWidth: 2
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            display: true,
                                            grid: {
                                                display: false
                                            },
                                            ticks: {
                                                font: {
                                                    size: 10
                                                },
                                                maxTicksLimit: 7
                                            }
                                        },
                                        y: {
                                            display: true,
                                            beginAtZero: true,
                                            max: 100,
                                            grid: {
                                                color: 'rgba(0,0,0,0.1)'
                                            },
                                            ticks: {
                                                font: {
                                                    size: 10
                                                },
                                                callback: function(value) {
                                                    return value + '%';
                                                }
                                            }
                                        }
                                    },
                                    elements: {
                                        point: {
                                            radius: 3,
                                            hoverRadius: 5
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Bu bölüme özel scriptler eklenebilir -->
<?= $this->endSection() ?> 