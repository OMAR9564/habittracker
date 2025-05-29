<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
        <!-- Sol Sütun - Alışkanlık Detayları -->
        <div class="w-full lg:w-2/3">
            <!-- 🎯 Header with Back Button -->
            <div class="flex items-center mb-6">
                <a href="<?= site_url('dashboard') ?>" class="text-purple-600 hover:text-purple-800 mr-4 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center">
                    <?= esc($habit['name']) ?> 
                    <span class="ml-2 text-2xl">🎯</span>
                </h1>
            </div>
            
            <!-- 🎯 Enhanced Habit Stats Card -->
            <div class="habit-card mb-6">
                <div class="p-6">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="stats-card text-center">
                            <span class="text-xs font-medium text-gray-500 uppercase">Seviye</span>
                            <h3 class="text-3xl font-bold text-purple-600"><?= $habit['level'] ?></h3>
                            <span class="text-xl">✨</span>
                        </div>
                        
                        <div class="stats-card text-center">
                            <span class="text-xs font-medium text-gray-500 uppercase">Hedef</span>
                            <h3 class="text-3xl font-bold text-blue-600"><?= $habit['current_goal'] ?></h3>
                            <span class="text-xs text-gray-500">gün</span>
                        </div>
                        
                        <div class="stats-card text-center">
                            <span class="text-xs font-medium text-gray-500 uppercase">Başarı</span>
                            <h3 class="text-3xl font-bold <?= $habit['success_percentage'] >= 70 ? 'text-green-600' : ($habit['success_percentage'] >= 40 ? 'text-yellow-600' : 'text-red-600') ?>">
                                <?= number_format($habit['success_percentage'], 1) ?>%
                            </h3>
                            <span class="text-xl"><?= $habit['success_percentage'] >= 70 ? '🌟' : ($habit['success_percentage'] >= 40 ? '✨' : '💪') ?></span>
                        </div>
                    </div>
                    
                    <!-- 🎯 Streak Information -->
                    <?php 
                    // Calculate streak data for this habit
                    $logs = $this->getData()['logs'] ?? [];
                    $streakData = [
                        'current_streak' => 0,
                        'max_streak' => 0,
                        'streak_type' => 'none',
                        'last_activity' => null
                    ];
                    
                    if (!empty($logs)) {
                        // Sort logs by date descending
                        usort($logs, function($a, $b) {
                            return strtotime($b['date']) - strtotime($a['date']);
                        });
                        
                        $today = new \DateTime();
                        $lastActivityDate = $logs[0]['date'];
                        $lastLogDate = new \DateTime($lastActivityDate);
                        $daysSinceLastLog = (int)$lastLogDate->diff($today)->days;
                        
                        // Current streak calculation
                        if ($lastLogDate->format('Y-m-d') === $today->format('Y-m-d')) {
                            $streakData['current_streak'] = 0;
                        } else {
                            $streakData['current_streak'] = $daysSinceLastLog;
                        }
                        
                        $streakData['last_activity'] = $lastActivityDate;
                        
                        // Calculate max streak
                        $dates = array_map(function($log) { return $log['date']; }, $logs);
                        if (!in_array($today->format('Y-m-d'), $dates)) {
                            array_unshift($dates, $today->format('Y-m-d'));
                        }
                        sort($dates);
                        
                        $tempStreak = 0;
                        $maxStreak = 0;
                        $previousDate = null;
                        
                        foreach ($dates as $dateStr) {
                            $currentDate = new \DateTime($dateStr);
                            if ($previousDate === null) {
                                $tempStreak = 1;
                            } else {
                                $dayDiff = (int)$previousDate->diff($currentDate)->days;
                                if ($dayDiff === 1) {
                                    $tempStreak++;
                                } else {
                                    $maxStreak = max($maxStreak, $tempStreak);
                                    $tempStreak = 1;
                                }
                            }
                            $previousDate = $currentDate;
                        }
                        $streakData['max_streak'] = max($maxStreak, $tempStreak, $streakData['current_streak']);
                        
                        // Determine streak type
                        if ($streakData['current_streak'] >= 30) {
                            $streakData['streak_type'] = 'excellent';
                        } elseif ($streakData['current_streak'] >= 7) {
                            $streakData['streak_type'] = 'good';
                        } elseif ($streakData['current_streak'] >= 1) {
                            $streakData['streak_type'] = 'starting';
                        }
                    }
                    ?>
                    
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 mb-6 border border-orange-200">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 flex items-center">
                            🔥 Streak Bilgileri
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-600 uppercase font-medium">Mevcut Streak</p>
                                <div class="flex items-center justify-center mt-1">
                                    <?php
                                    $streakClass = 'streak-badge';
                                    $icon = '🌱';
                                    $message = $streakData['current_streak'] . ' gün temiz';
                                    
                                    switch($streakData['streak_type']) {
                                        case 'excellent': 
                                            $streakClass .= ' streak-excellent'; 
                                            $icon = '🏆';
                                            break;
                                        case 'good': 
                                            $streakClass .= ' streak-good'; 
                                            $icon = '🔥';
                                            break;
                                        case 'starting': 
                                            $streakClass .= ' streak-starting'; 
                                            $icon = '💪';
                                            break;
                                        default: 
                                            $streakClass .= ' streak-none'; 
                                            break;
                                    }
                                    
                                    if ($streakData['current_streak'] === 0) {
                                        $icon = '😔';
                                        $message = 'Bugün yapıldı';
                                    }
                                    ?>
                                    <span class="<?= $streakClass ?>">
                                        <?= $icon ?> <?= $message ?>
                                    </span>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 uppercase font-medium">En Yüksek Streak</p>
                                <div class="flex items-center justify-center mt-1">
                                    <span class="streak-badge streak-excellent">
                                        🌟 <?= $streakData['max_streak'] ?> gün
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($habit['description'])): ?>
                        <div class="bg-gray-50 rounded-lg p-4 mb-6 border-l-4 border-purple-500">
                            <p class="text-gray-700 italic"><?= esc($habit['description']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="omar-btn-primary flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                            <span>Kayıt Ekle</span>
                            <span class="ml-1">📝</span>
                        </a>
                        
                        <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/refresh') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <span>Verileri Güncelle</span>
                            <span class="ml-1">🔄</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- İlerleme Grafiği -->
            <div class="p-6 border-t border-gray-200 bg-gradient-to-b from-white to-gray-50">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">📊</span>
                    Başarı Puanı Grafiği
                    <span class="ml-1 text-xs text-gray-500 font-normal">(Sıfırdan Başlayan)</span>
                </h2>
                <div class="bg-white p-4 rounded-lg shadow-inner border border-gray-100">
                    <canvas id="progressChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Sağ Sütun - İstatistikler ve Hedefler -->
        <div class="w-full lg:w-1/3">
            <!-- Aktif Hedef -->
            <?php if (isset($activeGoal) && $activeGoal): ?>
                <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <h2 class="text-xl font-semibold mb-5 flex items-center">
                        <span class="mr-2 text-2xl">🎯</span>
                        Aktif Hedef
                    </h2>
                    
                    <?php 
                    $startDate = new DateTime($activeGoal['start_date']);
                    $today = new DateTime(date('Y-m-d')); // Bugünün tarihini alıyoruz, saat bilgisi olmadan
                    
                    // Son günlüğü kontrol et
                    $logAfterStart = false;
                    $lastLogDate = null;
                    
                    if (!empty($logs)) {
                        // Başlangıç tarihinden sonraki tüm logları kontrol et
                        foreach ($logs as $log) {
                            $logDate = new DateTime($log['date']);
                            if ($logDate >= $startDate) {
                                $logAfterStart = true;
                                $lastLogDate = $logDate;
                                break;
                            }
                        }
                        
                        // Eğer başlangıç tarihinden sonra bir log varsa ve bugünün tarihi değilse sorun var demektir
                        if ($logAfterStart && $lastLogDate->format('Y-m-d') != $today->format('Y-m-d')) {
                    ?>
                            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4 border-l-4 border-red-500 shadow-sm">
                                <p class="font-semibold flex items-center">
                                    <span class="text-xl mr-2">⚠️</span>
                                    Dikkat! Veriler güncel değil.
                                </p>
                                <p class="text-sm mt-2">
                                    En son <?= $lastLogDate->format('d/m/Y') ?> tarihinde alışkanlık kaydı eklediniz fakat hedef süresi ve geçen gün sayısı güncel değil. 
                                    <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/refresh') ?>" class="underline font-semibold hover:text-red-600">Verileri güncelle</a> butonuna tıklayarak güncel bilgileri görebilirsiniz.
                                </p>
                            </div>
                    <?php
                        }
                    }
                    
                    // Geçen gün sayısını hesapla - startDate ile today arasındaki fark
                    $interval = $startDate->diff($today);
                    $daysPassed = $interval->days;
                    $goalDays = $activeGoal['goal_days'];
                    
                    // Eğer startDate bugünden sonra ise (geçersiz durum) geçen süreyi 0 yap
                    if ($startDate > $today) {
                        $daysPassed = 0;
                    }
                    
                    // Hedef tamamlandı mı?
                    $goalCompleted = $daysPassed >= $goalDays;
                    
                    $progress = min(100, ($daysPassed / $goalDays) * 100);
                    $daysLeft = max(0, $goalDays - $daysPassed);
                    
                    // Eğer hedef tamamlandıysa otomatik olarak hedefi tamamla
                    if ($goalCompleted) {
                        echo '<script>
                            // Sayfa yüklendiğinde hedef tamamlandıysa, otomatik olarak güncelleme yapalım
                            document.addEventListener("DOMContentLoaded", function() {
                                // Eğer daha önce bu hedef için otomatik güncelleme yapılmadıysa
                                if (!localStorage.getItem("autoUpdatedGoal_' . $habit["id"] . '_' . $activeGoal["id"] . '")) {
                                    // Kullanıcıya bildirim göster
                                    if (confirm("Tebrikler! ' . $goalDays . ' günlük hedefinizi tamamladınız. Yeni hedefe geçmek ister misiniz?")) {
                                        // Local storage\'a bu hedefin güncellendiğini kaydet
                                        localStorage.setItem("autoUpdatedGoal_' . $habit["id"] . '_' . $activeGoal["id"] . '", "true");
                                        // Verileri güncelle sayfasına yönlendir
                                        window.location.href = "' . site_url('dashboard/habit/' . $habit['id'] . '/refresh') . '";
                                    }
                                }
                            });
                        </script>';
                    }
                    
                    // İlerleme rengi
                    $progressColor = 'blue';
                    if ($progress >= 75) $progressColor = 'green';
                    elseif ($progress >= 50) $progressColor = 'blue';
                    elseif ($progress >= 25) $progressColor = 'yellow';
                    else $progressColor = 'red';
                    ?>
                    
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 font-medium">İlerleme</span>
                            <span class="text-sm font-bold text-<?= $progressColor ?>-600"><?= number_format($progress, 0) ?>%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 shadow-inner">
                            <div class="bg-gradient-to-r from-<?= $progressColor ?>-500 to-<?= $progressColor ?>-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <div class="text-xs text-purple-500 uppercase font-semibold">Başlangıç Tarihi</div>
                            <div class="text-lg font-bold text-purple-700 mt-1 flex items-center">
                                <span class="mr-1">📅</span>
                                <?= date('d/m/Y', strtotime($activeGoal['start_date'])) ?>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-xs text-blue-500 uppercase font-semibold">Hedef Süresi</div>
                            <div class="text-lg font-bold text-blue-700 mt-1 flex items-center">
                                <span class="mr-1">⏱️</span>
                                <?= $goalDays ?> gün
                            </div>
                        </div>
                        
                        <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="text-xs text-green-500 uppercase font-semibold">Geçen Süre</div>
                            <div class="text-lg font-bold text-green-700 mt-1 flex items-center">
                                <span class="mr-1">⌛</span>
                                <?= $daysPassed ?> gün
                            </div>
                        </div>
                        
                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="text-xs text-yellow-500 uppercase font-semibold">Kalan Süre</div>
                            <div class="text-lg font-bold text-yellow-700 mt-1 flex items-center">
                                <span class="mr-1">🏁</span>
                                <?= $daysLeft ?> gün
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-amber-50 text-amber-800 p-4 rounded-lg text-sm border-l-4 border-amber-500 shadow-sm">
                        <div class="flex items-start">
                            <span class="text-2xl mr-3">⚠️</span>
                            <div>
                                <p class="font-bold text-base mb-1">Dikkat!</p>
                                <p>Alışkanlığı yapmak ve kaydetmek (günlük eklemek) hedef sürenizi azaltacak ve ilerlemeyi sıfırlayacaktır. Ne kadar az yaparsanız, o kadar başarılı olursunuz!</p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($daysLeft == 0): ?>
                        <div class="mt-4 bg-green-50 text-green-800 p-4 rounded-lg text-sm border-l-4 border-green-500 shadow-sm">
                            <div class="flex items-start">
                                <span class="text-2xl mr-3">🎉</span>
                                <div>
                                    <p class="font-bold text-base mb-1">Tebrikler!</p>
                                    <p>Hedef süreyi tamamladınız. Yeni bir hedefe geçmek için <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/refresh') ?>" class="font-bold underline hover:text-green-600 transition-colors">Verileri Güncelle</a> butonuna tıklayabilirsiniz.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- İstatistikler -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold mb-5 flex items-center">
                    <span class="mr-2 text-2xl">📊</span>
                    İstatistikler
                </h2>
                
                <?php
                $totalLogs = count($logs);
                $totalCount = 0;
                $firstLogDate = $totalLogs > 0 ? new DateTime($logs[count($logs) - 1]['date']) : null;
                $lastLogDate = $totalLogs > 0 ? new DateTime($logs[0]['date']) : null;
                $daysSinceStart = $firstLogDate ? $firstLogDate->diff(new DateTime())->days + 1 : 0;
                
                foreach ($logs as $log) {
                    $totalCount += $log['count'];
                }
                
                $averagePerLog = $totalLogs > 0 ? $totalCount / $totalLogs : 0;
                $logsPerWeek = $daysSinceStart > 0 ? ($totalLogs / $daysSinceStart) * 7 : 0;
                ?>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex flex-col items-center justify-center">
                        <span class="text-3xl mb-1">📝</span>
                        <span class="text-xs text-gray-500 uppercase font-semibold">Toplam Kayıt</span>
                        <span class="text-2xl font-bold text-gray-700 mt-1"><?= $totalLogs ?></span>
                        <span class="text-xs text-gray-500">gün</span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex flex-col items-center justify-center">
                        <span class="text-3xl mb-1">🔢</span>
                        <span class="text-xs text-gray-500 uppercase font-semibold">Toplam Sayı</span>
                        <span class="text-2xl font-bold text-gray-700 mt-1"><?= $totalCount ?></span>
                        <span class="text-xs text-gray-500">kez</span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex flex-col items-center justify-center">
                        <span class="text-3xl mb-1">📈</span>
                        <span class="text-xs text-gray-500 uppercase font-semibold">Gün Başına</span>
                        <span class="text-2xl font-bold text-gray-700 mt-1"><?= number_format($averagePerLog, 1) ?></span>
                        <span class="text-xs text-gray-500">ortalama</span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex flex-col items-center justify-center">
                        <span class="text-3xl mb-1">📅</span>
                        <span class="text-xs text-gray-500 uppercase font-semibold">Haftalık</span>
                        <span class="text-2xl font-bold text-gray-700 mt-1"><?= number_format($logsPerWeek, 1) ?></span>
                        <span class="text-xs text-gray-500">gün</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                        <div class="flex items-center mb-1">
                            <span class="text-lg mr-1">🗓️</span>
                            <span class="text-xs text-gray-500 uppercase font-semibold">İlk Kayıt</span>
                        </div>
                        <span class="text-lg font-bold text-gray-700"><?= $firstLogDate ? $firstLogDate->format('d/m/Y') : '-' ?></span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                        <div class="flex items-center mb-1">
                            <span class="text-lg mr-1">📅</span>
                            <span class="text-xs text-gray-500 uppercase font-semibold">Son Kayıt</span>
                        </div>
                        <span class="text-lg font-bold text-gray-700"><?= $lastLogDate ? $lastLogDate->format('d/m/Y') : '-' ?></span>
                    </div>
                </div>
                
                <div class="mt-4 bg-gradient-to-r from-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-50 to-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-100 rounded-lg p-4 border border-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-700 uppercase font-semibold">Başarı Oranı</span>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-700"><?= number_format($habit['success_percentage'], 1) ?>%</span>
                        </div>
                    </div>
                    <div class="text-4xl">
                        <?= $habit['success_percentage'] >= 90 ? '🏆' : ($habit['success_percentage'] >= 70 ? '🌟' : ($habit['success_percentage'] >= 40 ? '✨' : '💪')) ?>
                    </div>
                </div>
            </div>
            
            <!-- Tamamlanan Hedefler -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Tamamlanan Hedefler</h2>
                
                <?php if (empty($goalHistory) || count($goalHistory) <= 1): ?>
                    <p class="text-gray-600 text-center py-4">Henüz tamamlanmış hedef bulunmamaktadır.</p>
                <?php else: ?>
                    <div class="overflow-y-auto max-h-64">
                        <ul class="space-y-3">
                            <?php 
                            // Aktif hedef hariç, tamamlanmış hedefleri göster
                            $completedGoals = array_filter($goalHistory, function($goal) {
                                return $goal['is_completed'] == 1;
                            });
                            
                            foreach ($completedGoals as $goal): 
                            ?>
                                <li class="border-b border-gray-200 pb-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">
                                            <?= $goal['goal_days'] ?> Günlük Hedef
                                        </span>
                                        <span class="text-xs bg-<?= $goal['completion_percentage'] >= 70 ? 'green' : ($goal['completion_percentage'] >= 40 ? 'yellow' : 'red') ?>-100 text-<?= $goal['completion_percentage'] >= 70 ? 'green' : ($goal['completion_percentage'] >= 40 ? 'yellow' : 'red') ?>-800 px-2 py-1 rounded-full">
                                            <?= number_format($goal['completion_percentage'], 1) ?>%
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?= date('d/m/Y', strtotime($goal['start_date'])) ?> - <?= date('d/m/Y', strtotime($goal['end_date'])) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // İlerleme grafiği
    const ctx = document.getElementById('progressChart').getContext('2d');
    const trendData = <?= json_encode($trendData) ?>;
    
    const labels = Object.keys(trendData);
    const data = Object.values(trendData);
    
    // Gradient arkaplan oluştur
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Başarı Puanı',
                data: data,
                borderColor: 'rgba(16, 185, 129, 1)', // Yeşil ton
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(tooltipItems) {
                            // Tarihi daha okunabilir formata dönüştür
                            const date = new Date(tooltipItems[0].label);
                            return date.toLocaleDateString('tr-TR');
                        },
                        label: function(context) {
                            return `Başarı Puanı: ${context.raw}%`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 10,
                        padding: 10,
                        font: {
                            size: 11
                        },
                        callback: function(value, index, values) {
                            // Tarihi kısaltılmış formatta göster
                            const date = new Date(this.getLabelForValue(value));
                            return date.getDate() + '/' + (date.getMonth() + 1);
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 100,
                    suggestedMin: 0,
                    min: 0,
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)',
                        drawBorder: false
                    },
                    ticks: {
                        precision: 0,
                        stepSize: 10,
                        padding: 10,
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
    
    // Animasyonlu sayaçlar ve kart hover efektleri ekle
    const cards = document.querySelectorAll('.rounded-xl');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('scale-[1.01]', 'shadow-lg');
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('scale-[1.01]', 'shadow-lg');
        });
    });
});
</script>
<?= $this->endSection() ?> 