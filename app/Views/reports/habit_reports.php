<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-6 py-8">
    
    <!-- 🧠 Omar BYA - Habit Reports Header -->
    <div class="glass-container p-8 mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent mb-2">
                    📊 <?= esc($habit['name']) ?> - Performans Raporu
                </h1>
                <p class="text-gray-300 text-lg">
                    <?= esc($habit['description'] ?? 'Detaylı performans analizi ve trend grafikleri') ?>
                </p>
                <div class="flex items-center gap-4 mt-4">
                    <span class="text-sm text-gray-400">Seviye:</span>
                    <div class="flex items-center gap-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="w-3 h-3 rounded-full <?= $i <= $habit['level'] ? 'bg-gradient-to-r from-yellow-400 to-orange-400' : 'bg-gray-600' ?>"></div>
                        <?php endfor; ?>
                        <span class="text-yellow-400 font-semibold ml-2"><?= $habit['level'] ?>/5</span>
                    </div>
                </div>
            </div>
            
            <!-- Period Selector -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-white/80 text-sm font-medium">Zaman Dilimi:</label>
                    <select id="periodSelector" class="bg-gray-800/50 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <?php foreach ($availablePeriods as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $period === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Export Buttons -->
                <div class="flex gap-2">
                    <button onclick="exportReport('pdf')" class="btn-modern btn-secondary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF İndir
                    </button>
                    <button onclick="exportReport('excel')" class="btn-modern btn-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel İndir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 📈 Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="glass-container p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 bg-gradient-to-r from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-green-400 mb-2"><?= $reportData['stats']['success_rate'] ?>%</div>
            <div class="text-gray-300 text-xs sm:text-sm">Başarı Oranı</div>
            <div class="w-full bg-gray-700 rounded-full h-1.5 sm:h-2 mt-3">
                <div class="bg-gradient-to-r from-green-400 to-emerald-400 h-1.5 sm:h-2 rounded-full" style="width: <?= $reportData['stats']['success_rate'] ?>%"></div>
            </div>
        </div>
        
        <div class="glass-container p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-blue-400 mb-2"><?= $reportData['stats']['active_days'] ?></div>
            <div class="text-gray-300 text-xs sm:text-sm">Aktif Gün</div>
            <div class="text-[10px] sm:text-xs text-gray-400 mt-1"><?= $reportData['stats']['total_days'] ?> günden</div>
        </div>
        
        <div class="glass-container p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 bg-gradient-to-r from-purple-400 to-pink-500 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-purple-400 mb-2"><?= $reportData['stats']['longest_streak'] ?></div>
            <div class="text-gray-300 text-xs sm:text-sm">En Uzun Seri</div>
            <div class="text-[10px] sm:text-xs text-gray-400 mt-1">gün üst üste</div>
        </div>
        
        <div class="glass-container p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-3 bg-gradient-to-r from-orange-400 to-red-500 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                </svg>
            </div>
            <div class="text-2xl sm:text-3xl font-bold text-orange-400 mb-2"><?= $reportData['stats']['current_streak'] ?></div>
            <div class="text-gray-300 text-xs sm:text-sm">Mevcut Seri</div>
            <div class="text-[10px] sm:text-xs text-gray-400 mt-1">gün devam ediyor</div>
        </div>
    </div>

    <!-- 📊 Chart Section -->
    <div class="glass-container p-4 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-white">📈 Trend Analizi</h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Kayıt yapılmayan günlerde artan (+), yapılan günlerde azalan (-) grafik
                </p>
            </div>
            <div class="text-xs sm:text-sm text-gray-400">
                <?= date('d.m.Y', strtotime($dateRange['start'])) ?> - <?= date('d.m.Y', strtotime($dateRange['end'])) ?>
            </div>
        </div>
        
        <div class="relative h-64 sm:h-96">
            <canvas id="performanceChart"></canvas>
        </div>
        
        <!-- Chart Legend -->
        <div class="mt-4 flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-gray-300">Trend Skoru</span>
            </div>
            <div class="text-gray-400">
                📉 Düşen çizgi = Düzenli alışkanlık tekrarı
            </div>
        </div>
    </div>

    <!-- 📋 Detailed Logs -->
    <div class="glass-container p-8">
        <h2 class="text-2xl font-bold text-white mb-6">Günlük Kayıtlar</h2>
        
        <?php if (!empty($reportData['logs'])): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Tarih</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Kayıt Sayısı</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Notlar</th>
                            <th class="text-left py-3 px-4 text-gray-300 font-medium">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['logs'] as $log): ?>
                            <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition-colors">
                                <td class="py-3 px-4 text-white"><?= date('d.m.Y', strtotime($log['date'])) ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= $log['count'] ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-300"><?= esc($log['notes'] ?? '-') ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Tamamlandı
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="text-gray-400 text-lg mb-4">📊</div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">Henüz kayıt bulunmuyor</h3>
                <p class="text-gray-400">Bu zaman diliminde henüz alışkanlık kaydı yapılmamış.</p>
                <a href="<?= site_url('dashboard/habit/' . $habit['id'] . '/log') ?>" class="btn-modern btn-primary mt-4 inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    İlk Kaydı Ekle
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- 🔄 Back to Habit -->
    <div class="mt-8 text-center">
        <a href="<?= site_url('dashboard/habit/' . $habit['id']) ?>" class="btn-modern btn-secondary inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Alışkanlığa Dön
        </a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// 🧠 Omar BYA - Habit Reports JavaScript
document.addEventListener('DOMContentLoaded', function() {
    let chart = null;
    
    // Initialize chart
    initChart();
    
    // Period selector change handler
    document.getElementById('periodSelector').addEventListener('change', function() {
        const period = this.value;
        const url = new URL(window.location);
        url.searchParams.set('period', period);
        window.location.href = url.toString();
    });
    
    function initChart() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        // Fetch chart data
        fetch(`<?= site_url('dashboard/habit/' . $habit['id'] . '/reports/chart-data') ?>?period=<?= $period ?>`)
            .then(response => response.json())
            .then(data => {
                if (chart) {
                    chart.destroy();
                }
                
                chart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#ef4444',
                                borderWidth: 1,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return 'Skor: ' + context.parsed.y;
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
                                    font: { size: window.innerWidth < 640 ? 9 : 11 },
                                    maxTicksLimit: window.innerWidth < 640 ? 7 : 14
                                }
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                grid: { 
                                    display: true,
                                    color: 'rgba(255, 255, 255, 0.08)'
                                },
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    font: { size: window.innerWidth < 640 ? 9 : 11 },
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Chart data fetch error:', error);
                OmarNotifications.error('Grafik verileri yüklenirken hata oluştu.');
            });
    }
});

// Export report function
function exportReport(format) {
    const period = document.getElementById('periodSelector').value;
    const url = `<?= site_url('dashboard/habit/' . $habit['id'] . '/reports/export') ?>?period=${period}&format=${format}`;
    
    // Show loading notification
    OmarNotifications.info(`${format.toUpperCase()} raporu hazırlanıyor...`);
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Success notification
    setTimeout(() => {
        OmarNotifications.success(`${format.toUpperCase()} raporu başarıyla indirildi!`);
    }, 1000);
}
</script>
<?= $this->endSection() ?>
