<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start gap-6">
    <!-- Sol Sütun - Alışkanlık Detayları -->
    <div class="w-full md:w-2/3">
        <div class="flex items-center mb-6">
            <a href="<?= site_url('dashboard') ?>" class="text-purple-600 hover:text-purple-800 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold"><?= esc($habit['name']) ?></h1>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Alışkanlık Başlık ve Durum -->
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="text-sm font-medium bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Seviye <?= $habit['level'] ?></span>
                    <span class="text-sm font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Hedef: <?= $habit['current_goal'] ?> gün</span>
                    <span class="text-sm font-medium bg-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-100 text-<?= $habit['success_percentage'] >= 70 ? 'green' : ($habit['success_percentage'] >= 40 ? 'yellow' : 'red') ?>-800 px-2 py-1 rounded-full">Başarı: <?= number_format($habit['success_percentage'], 1) ?>%</span>
                </div>
                
                <?php if (!empty($habit['description'])): ?>
                    <p class="text-gray-600 text-sm mb-4"><?= esc($habit['description']) ?></p>
                <?php endif; ?>
                
                <div class="flex flex-wrap gap-3">
                    <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        Kayıt Ekle
                    </a>
                    
                    <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/delete') ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition" onclick="return confirm('Bu alışkanlığı silmek istediğinizden emin misiniz?');">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Sil
                    </a>
                </div>
            </div>
            
            <!-- İlerleme Grafiği -->
            <div class="p-6 border-t border-gray-200">
                <h2 class="text-lg font-semibold mb-4">İlerleme Grafiği (Son 90 Gün)</h2>
                <canvas id="progressChart" height="200"></canvas>
            </div>
        </div>
        
        <!-- Günlük Kayıtlar -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Günlük Kayıtlar</h2>
                
                <?php if (empty($logs)): ?>
                    <div class="text-center bg-gray-50 p-8 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600">Henüz hiç kayıt eklenmemiş.</p>
                        <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="mt-4 inline-block text-purple-600 hover:text-purple-800 font-semibold">
                            İlk kaydı ekle
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-4 text-left">Tarih</th>
                                    <th class="py-3 px-4 text-center">Sayı</th>
                                    <th class="py-3 px-4 text-left">Notlar</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                <?php foreach ($logs as $log): ?>
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <?= date('d/m/Y', strtotime($log['date'])) ?>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                                                <?= $log['count'] ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?= empty($log['notes']) ? '<span class="text-gray-400">Not yok</span>' : esc($log['notes']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Sağ Sütun - İstatistikler ve Hedefler -->
    <div class="w-full md:w-1/3">
        <!-- Aktif Hedef -->
        <?php if (isset($activeGoal) && $activeGoal): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold mb-3">Aktif Hedef</h2>
                
                <?php 
                $startDate = new DateTime($activeGoal['start_date']);
                $today = new DateTime();
                $daysPassed = $startDate->diff($today)->days;
                $goalDays = $activeGoal['goal_days'];
                $progress = min(100, ($daysPassed / $goalDays) * 100);
                $daysLeft = max(0, $goalDays - $daysPassed);
                ?>
                
                <div class="mb-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">İlerleme</span>
                        <span class="text-sm font-semibold"><?= number_format($progress, 0) ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $progress ?>%"></div>
                    </div>
                </div>
                
                <ul class="mt-4 space-y-2 text-sm">
                    <li class="flex justify-between">
                        <span class="text-gray-600">Başlangıç Tarihi:</span>
                        <span class="font-semibold"><?= date('d/m/Y', strtotime($activeGoal['start_date'])) ?></span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Hedef Süresi:</span>
                        <span class="font-semibold"><?= $goalDays ?> gün</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Geçen Süre:</span>
                        <span class="font-semibold"><?= $daysPassed ?> gün</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Kalan Süre:</span>
                        <span class="font-semibold"><?= $daysLeft ?> gün</span>
                    </li>
                </ul>
                
                <?php if ($daysLeft == 0): ?>
                    <div class="mt-4 bg-green-100 text-green-800 p-3 rounded-lg text-sm">
                        <p class="font-semibold">Tebrikler!</p>
                        <p>Hedef süreyi tamamladınız. Yeni bir alışkanlık kaydı ekleyerek bir sonraki hedefe geçebilirsiniz.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- İstatistikler -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">İstatistikler</h2>
            
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
            
            <ul class="space-y-3">
                <li class="flex justify-between">
                    <span class="text-gray-600">Toplam Kayıt:</span>
                    <span class="font-semibold"><?= $totalLogs ?> gün</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Toplam Sayı:</span>
                    <span class="font-semibold"><?= $totalCount ?> kez</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Gün Başına Ortalama:</span>
                    <span class="font-semibold"><?= number_format($averagePerLog, 1) ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Haftalık Ortalama:</span>
                    <span class="font-semibold"><?= number_format($logsPerWeek, 1) ?> gün</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">İlk Kayıt:</span>
                    <span class="font-semibold"><?= $firstLogDate ? $firstLogDate->format('d/m/Y') : '-' ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Son Kayıt:</span>
                    <span class="font-semibold"><?= $lastLogDate ? $lastLogDate->format('d/m/Y') : '-' ?></span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-600">Başarı Oranı:</span>
                    <span class="font-semibold"><?= number_format($habit['success_percentage'], 1) ?>%</span>
                </li>
            </ul>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // İlerleme grafiği
    const ctx = document.getElementById('progressChart').getContext('2d');
    const trendData = <?= json_encode($trendData) ?>;
    
    const labels = Object.keys(trendData);
    const data = Object.values(trendData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Alışkanlık Kaydı',
                data: data,
                borderColor: 'rgba(124, 58, 237, 1)',
                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            // Tarihi daha okunabilir formata dönüştür
                            const date = new Date(tooltipItems[0].label);
                            return date.toLocaleDateString('tr-TR');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 10,
                        callback: function(value, index, values) {
                            // Tarihi kısaltılmış formatta göster
                            const date = new Date(this.getLabelForValue(value));
                            return date.getDate() + '/' + (date.getMonth() + 1);
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 5,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?> 