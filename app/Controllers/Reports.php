<?php

namespace App\Controllers;

use App\Models\HabitModel;
use App\Models\HabitLogModel;
use App\Models\GoalHistoryModel;

/**
 * 🧠 Omar BYA - Reports Controller
 * Code aligned with Omar BYA's Personal Style Sheet
 */
class Reports extends BaseController
{
    protected $habitModel;
    protected $habitLogModel;
    protected $goalHistoryModel;
    
    public function __construct()
    {
        $this->habitModel = new HabitModel();
        $this->habitLogModel = new HabitLogModel();
        $this->goalHistoryModel = new GoalHistoryModel();
        
        // 🤖 Optimized by O.B. - Enhanced security + direct helper loading
        helper(['security', 'form']);
        
        // Direct helper loading as fallback
        if (!function_exists('omarTrace')) {
            require_once APPPATH . 'Helpers/omar_helper.php';
        }
    }
    
    public function index()
    {
        // ✅ Rule 6: Separation of Concerns
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        // omarTrace('Reports accessed', ['user_id' => $userId]); // Temporarily disabled
        log_message('info', 'Reports accessed by user: ' . $userId);
        
        // Get user habits for filters
        $habits = $this->habitModel->getUserHabits($userId);
        
        // Get date range from request or default to last 30 days
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $habitId = $this->request->getGet('habit_id');
        
        // Generate comprehensive report data
        $reportData = $this->generateReportData($userId, $startDate, $endDate, $habitId);
        
        $data = [
            'title' => 'Raporlar ve Analiz',
            'habits' => $habits,
            'reportData' => $reportData,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'habit_id' => $habitId
            ]
        ];
        
        return view('reports/index', $data);
    }
    
    public function exportCsv()
    {
        // ✅ Rule 6: Separation of Concerns
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $habitId = $this->request->getGet('habit_id');
        
        // omarTrace('CSV Export requested', ['user_id' => $userId, 'date_range' => [$startDate, $endDate], 'habit_id' => $habitId]); // Temporarily disabled
        log_message('info', 'CSV Export requested by user: ' . $userId . ' for date range: ' . $startDate . ' to ' . $endDate);
        
        // Generate CSV data
        $csvData = $this->generateCsvData($userId, $startDate, $endDate, $habitId);
        
        // Set CSV headers
        $filename = 'aliskanlık_raporu_' . date('Y-m-d_H-i-s') . '.csv';
        
        $this->response
            ->setContentType('text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');
        
        return $this->response->setBody($csvData);
    }
    
    // NOTE: Generate comprehensive report data
    private function generateReportData($userId, $startDate, $endDate, $habitId = null)
    {
        $habits = $this->habitModel->getUserHabits($userId);
        $reportData = [
            'summary' => [],
            'trends' => [],
            'achievements' => [],
            'streaks' => []
        ];
        
        foreach ($habits as $habit) {
            // Skip if specific habit filter is applied
            if ($habitId && $habit['id'] != $habitId) {
                continue;
            }
            
            $logs = $this->habitLogModel->getLogsByDateRange($habit['id'], $startDate, $endDate);
            $allLogs = $this->habitLogModel->getHabitLogs($habit['id']);
            
            // Calculate metrics
            $totalDays = (new \DateTime($endDate))->diff(new \DateTime($startDate))->days + 1;
            $activeDays = count($logs);
            $successRate = $totalDays > 0 ? ($activeDays / $totalDays) * 100 : 0;
            
            // Simple streak calculation
            $currentStreak = 0;
            if (!empty($allLogs)) {
                $lastLog = $allLogs[0]; // Assuming logs are sorted by date desc
                $lastLogDate = new \DateTime($lastLog['date']);
                $today = new \DateTime();
                $currentStreak = $lastLogDate->diff($today)->days;
            }
            
            $habitData = [
                'id' => $habit['id'],
                'name' => $habit['name'],
                'level' => $habit['level'],
                'total_days' => $totalDays,
                'active_days' => $activeDays,
                'success_rate' => $successRate,
                'current_streak' => $currentStreak,
                'trend_data' => [] // Simplified for now
            ];
            
            $reportData['summary'][] = $habitData;
            $reportData['trends'][$habit['name']] = $habitData['trend_data'];
        }
        
        // Calculate overall statistics
        $reportData['overall'] = $this->calculateOverallStats($reportData['summary']);
        
        return $reportData;
    }
    
    // NOTE: Generate CSV export data
    private function generateCsvData($userId, $startDate, $endDate, $habitId = null)
    {
        $habits = $this->habitModel->getUserHabits($userId);
        $csvLines = [];
        
        // CSV Headers
        $csvLines[] = 'Alışkanlık Adı,Tarih,Kayıt Sayısı,Notlar,Seviye,Başarı Oranı';
        
        foreach ($habits as $habit) {
            if ($habitId && $habit['id'] != $habitId) {
                continue;
            }
            
            $logs = $this->habitLogModel->getLogsByDateRange($habit['id'], $startDate, $endDate);
            
            foreach ($logs as $log) {
                $csvLines[] = sprintf(
                    '"%s","%s",%d,"%s",%d,%.2f',
                    $habit['name'],
                    $log['date'],
                    $log['count'],
                    $log['notes'] ?? '',
                    $habit['level'],
                    $habit['success_percentage']
                );
            }
        }
        
        return implode("\n", $csvLines);
    }
    
    // NOTE: Calculate overall statistics
    private function calculateOverallStats($summaryData)
    {
        if (empty($summaryData)) {
            return [
                'total_habits' => 0,
                'avg_success_rate' => 0,
                'total_active_days' => 0,
                'avg_level' => 0
            ];
        }
        
        $totalHabits = count($summaryData);
        $totalSuccessRate = array_sum(array_column($summaryData, 'success_rate'));
        $totalActiveDays = array_sum(array_column($summaryData, 'active_days'));
        $totalLevels = array_sum(array_column($summaryData, 'level'));
        
        return [
            'total_habits' => $totalHabits,
            'avg_success_rate' => $totalHabits > 0 ? $totalSuccessRate / $totalHabits : 0,
            'total_active_days' => $totalActiveDays,
            'avg_level' => $totalHabits > 0 ? $totalLevels / $totalHabits : 0
        ];
    }
    
    /**
     * 🧠 Omar BYA - Habit Specific Reports
     * Belirli bir alışkanlık için detaylı raporlama sayfası
     */
    public function habitReports($habitId)
    {
        // ✅ Rule 6: Separation of Concerns
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        $habit = $this->habitModel->find($habitId);
        
        // Kullanıcının alışkanlığına erişim kontrolü
        if (!$habit || $habit['user_id'] != $userId) {
            return redirect()->to('/dashboard')->with('error', 'Alışkanlık bulunamadı.');
        }
        
        // Zaman dilimi seçimi (varsayılan: 1 aylık)
        $period = $this->request->getGet('period') ?? '1month';
        $dateRange = $this->calculateDateRange($period);
        
        // omarTrace('Habit report accessed', ['habit_id' => $habitId, 'period' => $period, 'user_id' => $userId]);
        log_message('info', 'Habit report accessed by user: ' . $userId . ' for habit: ' . $habitId . ' period: ' . $period);
        
        // Rapor verilerini oluştur
        $reportData = $this->generateHabitReportData($habitId, $dateRange['start'], $dateRange['end']);
        
        $data = [
            'title' => $habit['name'] . ' - Performans Raporu',
            'habit' => $habit,
            'reportData' => $reportData,
            'period' => $period,
            'dateRange' => $dateRange,
            'availablePeriods' => [
                '1month' => '1 Aylık',
                '3months' => '3 Aylık', 
                '6months' => '6 Aylık',
                '1year' => '1 Yıllık',
                'all' => 'Tümü'
            ]
        ];
        
        return view('reports/habit_reports', $data);
    }
    
    /**
     * 🧠 Omar BYA - Habit Chart Data API
     * Grafik verilerini JSON olarak döndürür
     */
    public function getHabitChartData($habitId)
    {
        // ✅ Rule 6: Separation of Concerns
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }
        
        $userId = session()->get('user_id');
        $habit = $this->habitModel->find($habitId);
        
        if (!$habit || $habit['user_id'] != $userId) {
            return $this->response->setJSON(['error' => 'Habit not found'])->setStatusCode(404);
        }
        
        $period = $this->request->getGet('period') ?? '1month';
        $dateRange = $this->calculateDateRange($period);
        
        $chartData = $this->generateChartData($habitId, $dateRange['start'], $dateRange['end']);
        
        return $this->response->setJSON($chartData);
    }
    
    /**
     * 🧠 Omar BYA - Export Habit Report
     * Alışkanlık raporunu PDF/Excel olarak export eder
     */
    public function exportHabitReport($habitId)
    {
        // ✅ Rule 6: Separation of Concerns
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        $habit = $this->habitModel->find($habitId);
        
        if (!$habit || $habit['user_id'] != $userId) {
            return redirect()->to('/dashboard')->with('error', 'Alışkanlık bulunamadı.');
        }
        
        $period = $this->request->getGet('period') ?? '1month';
        $format = $this->request->getGet('format') ?? 'pdf';
        $dateRange = $this->calculateDateRange($period);
        
        // omarTrace('Habit report export', ['habit_id' => $habitId, 'format' => $format, 'period' => $period]);
        log_message('info', 'Habit report export by user: ' . $userId . ' format: ' . $format . ' period: ' . $period);
        
        $reportData = $this->generateHabitReportData($habitId, $dateRange['start'], $dateRange['end']);
        
        if ($format === 'pdf') {
            return $this->exportHabitReportPdf($habit, $reportData, $dateRange, $period);
        } elseif ($format === 'excel') {
            return $this->exportHabitReportExcel($habit, $reportData, $dateRange, $period);
        }
        
        return redirect()->back()->with('error', 'Geçersiz format.');
    }
    
    /**
     * NOTE: Calculate date range based on period
     * 🧠 Omar BYA - Enhanced date range calculation with "All" option
     */
    private function calculateDateRange($period)
    {
        $endDate = date('Y-m-d');
        
        switch ($period) {
            case '1month':
                $startDate = date('Y-m-d', strtotime('-1 month'));
                break;
            case '3months':
                $startDate = date('Y-m-d', strtotime('-3 months'));
                break;
            case '6months':
                $startDate = date('Y-m-d', strtotime('-6 months'));
                break;
            case '1year':
                $startDate = date('Y-m-d', strtotime('-1 year'));
                break;
            case 'all':
                // Tüm zamanlar için alışkanlığın ilk kaydından başla
                $startDate = null; // Bu durumda alışkanlığın ilk kaydından başlayacağız
                break;
            default:
                $startDate = date('Y-m-d', strtotime('-1 month'));
        }
        
        return [
            'start' => $startDate,
            'end' => $endDate,
            'period' => $period
        ];
    }
    
    /**
     * NOTE: Generate habit-specific report data
     * 🧠 Omar BYA - Enhanced report data with "All" period support
     */
    private function generateHabitReportData($habitId, $startDate, $endDate)
    {
        // Eğer startDate null ise (tümü seçeneği), alışkanlığın ilk kaydından başla
        if ($startDate === null) {
            $firstLog = $this->habitLogModel->where('habit_id', $habitId)
                                          ->orderBy('date', 'ASC')
                                          ->first();
            if ($firstLog) {
                $startDate = $firstLog['date'];
            } else {
                $startDate = date('Y-m-d', strtotime('-30 days')); // Varsayılan
            }
        }
        
        $logs = $this->habitLogModel->getLogsByDateRange($habitId, $startDate, $endDate);
        $habit = $this->habitModel->find($habitId);
        
        // Logları en yeni önce olacak şekilde sırala (görüntüleme için)
        $logs = array_reverse($logs);
        
        // Tarih aralığındaki toplam gün sayısı
        $totalDays = (new \DateTime($endDate))->diff(new \DateTime($startDate))->days + 1;
        $activeDays = count($logs);
        
        // Başarı oranı: Aktif günler / Toplam günler * 100
        $successRate = $totalDays > 0 ? ($activeDays / $totalDays) * 100 : 0;
        
        // Trend verisi oluştur
        $trendData = $this->generateTrendData($logs, $startDate, $endDate);
        
        // Seri hesaplamaları için logları tekrar doğru sırala
        $logsForStreak = array_reverse($logs); // ASC sıralama için
        
        // İstatistikler - Düzeltilmiş hesaplamalar
        $stats = [
            'total_days' => $totalDays,
            'active_days' => $activeDays,
            'success_rate' => round($successRate, 2),
            'total_count' => array_sum(array_column($logs, 'count')),
            'avg_daily_count' => $activeDays > 0 ? round(array_sum(array_column($logs, 'count')) / $activeDays, 2) : 0,
            'longest_streak' => $this->calculateLongestStreak($logsForStreak),
            'current_streak' => $this->calculateCurrentStreak($logsForStreak)
        ];
        
        // Debug için log ekle
        log_message('info', 'Habit Report Stats - ID: ' . $habitId . ', Total Days: ' . $totalDays . ', Active Days: ' . $activeDays . ', Success Rate: ' . $successRate);
        
        return [
            'habit' => $habit,
            'logs' => $logs,
            'stats' => $stats,
            'trend_data' => $trendData,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }
    
    /**
     * NOTE: Generate chart data for visualization - Inverse Trend (Stress/Load)
     * 🧠 Omar BYA - Kayıt yoksa artan (+), varsa azalan (-) grafik
     */
    private function generateChartData($habitId, $startDate, $endDate)
    {
        // Eğer startDate null ise (tümü seçeneği), alışkanlığın ilk kaydından başla
        if ($startDate === null) {
            $firstLog = $this->habitLogModel->where('habit_id', $habitId)
                                          ->orderBy('date', 'ASC')
                                          ->first();
            if ($firstLog) {
                $startDate = $firstLog['date'];
            } else {
                // Eğer hiç kayıt yoksa ve "tümü" seçildiyse varsayılan 30 gün
                $startDate = date('Y-m-d', strtotime('-30 days'));
            }
        }
        
        $logs = $this->habitLogModel->getLogsByDateRange($habitId, $startDate, $endDate);
        
        // Tarih aralığını oluştur
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('+1 day'); // Include end date
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);
        
        $labels = [];
        $data = [];
        
        // Inverse Trend Logic
        $currentScore = 0;
        
        foreach ($dateRange as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            
            // Bu tarihte kayıt var mı kontrol et
            $dayLogs = array_filter($logs, function($log) use ($dateStr) {
                return $log['date'] === $dateStr;
            });
            
            if (!empty($dayLogs)) {
                // Kayıt VARSA düş (Down)
                // Her kayıt için -1 (veya sabit -1 mi? Kullanıcı 'bir tane dussun' dedi)
                // Basit mantık: Kayıt olan gün -1
                $currentScore -= 1;
            } else {
                // Kayıt YOKSA art (Up)
                $currentScore += 1;
            }
            
            // Add to data points
            $data[] = $currentScore;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Trend Skoru',
                    'data' => $data,
                    'borderColor' => '#ef4444', // Red-500 (Stress/Load indicator color)
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)', // Red-500 with opacity
                    'borderWidth' => 3,
                    'tension' => 0.4, // Smooth curves
                    'fill' => true,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 6
                ]
            ]
        ];
    }
    
    /**
     * NOTE: Generate trend data for analysis
     */
    private function generateTrendData($logs, $startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);
        
        $trendData = [];
        $loggedDates = array_column($logs, 'date');
        
        foreach ($dateRange as $date) {
            $dateStr = $date->format('Y-m-d');
            $trendData[$dateStr] = in_array($dateStr, $loggedDates) ? 1 : 0;
        }
        
        return $trendData;
    }
    
    /**
     * NOTE: Calculate longest streak - Completely rewritten
     * 🧠 Omar BYA - Tamamen yeniden yazılmış en uzun seri hesaplama
     */
    private function calculateLongestStreak($logs)
    {
        if (empty($logs)) return 0;
        
        // Tarihleri al ve sırala
        $dates = array_column($logs, 'date');
        sort($dates);
        
        $longestStreak = 1;
        $currentStreak = 1;
        
        // Her tarih için ardışık gün kontrolü yap
        for ($i = 1; $i < count($dates); $i++) {
            $prevDate = new \DateTime($dates[$i-1]);
            $currDate = new \DateTime($dates[$i]);
            $diff = $prevDate->diff($currDate)->days;
            
            if ($diff === 1) {
                // Ardışık günler - seri devam ediyor
                $currentStreak++;
            } else {
                // Seri kırıldı - en uzun seriyi güncelle
                $longestStreak = max($longestStreak, $currentStreak);
                $currentStreak = 1; // Yeni seri başladı
            }
        }
        
        // Son seriyi de kontrol et
        $longestStreak = max($longestStreak, $currentStreak);
        
        // Debug log
        log_message('info', 'Longest Streak Calculation - Dates: ' . implode(', ', $dates) . ', Longest: ' . $longestStreak);
        
        return $longestStreak;
    }
    
    /**
     * NOTE: Calculate current streak - Completely rewritten
     * 🧠 Omar BYA - Tamamen yeniden yazılmış mevcut seri hesaplama
     */
    private function calculateCurrentStreak($logs)
    {
        if (empty($logs)) return 0;
        
        $dates = array_column($logs, 'date');
        rsort($dates); // En yeni tarihten başla
        
        $currentStreak = 0;
        $today = new \DateTime();
        $yesterday = new \DateTime('-1 day');
        $lastDate = new \DateTime($dates[0]);
        
        // Son kayıt bugün veya dün ise streak başlar
        if ($lastDate->format('Y-m-d') === $today->format('Y-m-d') || 
            $lastDate->format('Y-m-d') === $yesterday->format('Y-m-d')) {
            $currentStreak = 1;
            
            // Geriye doğru ardışık günleri say
            for ($i = 1; $i < count($dates); $i++) {
                $currDate = new \DateTime($dates[$i-1]);
                $prevDate = new \DateTime($dates[$i]);
                $diff = $prevDate->diff($currDate)->days;
                
                if ($diff === 1) {
                    $currentStreak++;
                } else {
                    break; // Seri kırıldı
                }
            }
        }
        
        // Debug log
        log_message('info', 'Current Streak Calculation - Last Date: ' . $dates[0] . ', Today: ' . $today->format('Y-m-d') . ', Current Streak: ' . $currentStreak);
        
        return $currentStreak;
    }
    
    /**
     * NOTE: Export habit report as PDF with Chart
     * 🧠 Omar BYA - Real PDF export with Progress Trend chart using DomPDF
     */
    private function exportHabitReportPdf($habit, $reportData, $dateRange, $period)
    {
        // Progress Trend chart data oluştur
        $chartData = $this->generateChartData($habit['id'], $dateRange['start'], $dateRange['end']);
        
        // PDF için HTML oluştur (SVG grafik ile)
        $html = $this->generatePdfHtmlWithChart($habit, $reportData, $dateRange, $period, $chartData);
        
        // DomPDF kullanarak PDF oluştur
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Yatay yapıldı
        $dompdf->render();
        
        $filename = 'aliskanlik_raporu_' . $habit['name'] . '_' . $period . '_' . date('Y-m-d') . '.pdf';
        
        $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');
        
        return $this->response->setBody($dompdf->output());
    }
    
    /**
     * NOTE: Export habit report as Excel
     */
    private function exportHabitReportExcel($habit, $reportData, $dateRange, $period)
    {
        $csvData = $this->generateHabitCsvData($habit, $reportData, $dateRange, $period);
        
        $filename = 'aliskanlik_raporu_' . $habit['name'] . '_' . $period . '_' . date('Y-m-d') . '.csv';
        
        $this->response
            ->setContentType('text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');
        
        return $this->response->setBody($csvData);
    }
    
    /**
     * NOTE: Generate HTML report content with Chart
     * 🧠 Omar BYA - Enhanced HTML report with Progress Trend chart
     */
    private function generateReportHtmlWithChart($habit, $reportData, $dateRange, $period, $chartData)
    {
        $periodNames = [
            '1month' => '1 Aylık',
            '3months' => '3 Aylık',
            '6months' => '6 Aylık',
            '1year' => '1 Yıllık',
            'all' => 'Tümü'
        ];
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $habit['name'] . ' - Performans Raporu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 1.2em;
        }
        .stats { 
            display: flex; 
            justify-content: space-around; 
            margin: 30px 0; 
            flex-wrap: wrap;
        }
        .stat-box { 
            text-align: center; 
            padding: 20px; 
            border: 2px solid #e0e0e0; 
            border-radius: 10px; 
            margin: 10px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-width: 150px;
        }
        .stat-value { 
            font-size: 28px; 
            font-weight: bold; 
            color: #2c3e50; 
            margin-bottom: 5px;
        }
        .stat-label { 
            font-size: 14px; 
            color: #7f8c8d; 
            font-weight: 500;
        }
        .chart-section {
            margin: 40px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }
        .chart-title {
            text-align: center;
            color: #2c3e50;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 30px; 
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td { 
            border: 1px solid #e0e0e0; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e3f2fd;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 ' . $habit['name'] . ' - Performans Raporu</h1>
            <p>Rapor Dönemi: ' . $periodNames[$period] . ' (' . $dateRange['start'] . ' - ' . $dateRange['end'] . ')</p>
        </div>
        
        <div class="stats">
            <div class="stat-box">
                <div class="stat-value">' . $reportData['stats']['success_rate'] . '%</div>
                <div class="stat-label">Başarı Oranı</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">' . $reportData['stats']['active_days'] . '</div>
                <div class="stat-label">Aktif Gün</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">' . $reportData['stats']['longest_streak'] . '</div>
                <div class="stat-label">En Uzun Seri</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">' . $reportData['stats']['current_streak'] . '</div>
                <div class="stat-label">Mevcut Seri</div>
            </div>
        </div>
        
        <div class="chart-section">
            <div class="chart-title">📈 Progress Trend Grafiği</div>
            <div class="chart-container">
                <canvas id="progressChart"></canvas>
            </div>
            <p style="text-align: center; color: #7f8c8d; font-style: italic;">
                0\'dan başlar, her gün artar. Kayıt olan günlerde kayıt sayısı kadar düşer. Yüksek değerler daha iyi performans gösterir.
            </p>
        </div>
        
        <h3 style="color: #2c3e50; margin-top: 40px;">📋 Günlük Kayıtlar</h3>
        <table>
            <tr>
                <th>Tarih</th>
                <th>Kayıt Sayısı</th>
                <th>Notlar</th>
            </tr>';
        
        foreach ($reportData['logs'] as $log) {
            $html .= '<tr>
                <td>' . $log['date'] . '</td>
                <td>' . $log['count'] . '</td>
                <td>' . ($log['notes'] ?? '') . '</td>
            </tr>';
        }
        
        $html .= '</table>
        
        <div class="footer">
            <p>Rapor Oluşturulma Tarihi: ' . date('d.m.Y H:i') . '</p>
            <p>Habit Tracker 3.0 - Omar BYA Development</p>
        </div>
    </div>
    
    <script>
        // Chart.js ile Progress Trend grafiği oluştur
        const ctx = document.getElementById(\'progressChart\').getContext(\'2d\');
        const chartData = ' . json_encode($chartData) . ';
        
        new Chart(ctx, {
            type: \'line\',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: {
                            color: \'#2c3e50\',
                            font: { size: 14 }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: { 
                            display: true,
                            color: \'rgba(0,0,0,0.1)\'
                        },
                        ticks: {
                            color: \'#7f8c8d\',
                            font: { size: 12 }
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: { 
                            display: true,
                            color: \'rgba(0,0,0,0.1)\'
                        },
                        ticks: {
                            color: \'#7f8c8d\',
                            font: { size: 12 }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 5,
                        hoverRadius: 8,
                        backgroundColor: \'#667eea\',
                        borderColor: \'#ffffff\',
                        borderWidth: 2
                    }
                }
            }
        });
    </script>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * NOTE: Generate HTML report content (legacy method)
     */
    private function generateReportHtml($habit, $reportData, $dateRange, $period)
    {
        $periodNames = [
            '1month' => '1 Aylık',
            '3months' => '3 Aylık',
            '6months' => '6 Aylık',
            '1year' => '1 Yıllık'
        ];
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $habit['name'] . ' - Performans Raporu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat-box { text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .stat-value { font-size: 24px; font-weight: bold; color: #2c3e50; }
        .stat-label { font-size: 14px; color: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . $habit['name'] . ' - Performans Raporu</h1>
        <p>Rapor Dönemi: ' . $periodNames[$period] . ' (' . $dateRange['start'] . ' - ' . $dateRange['end'] . ')</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['success_rate'] . '%</div>
            <div class="stat-label">Başarı Oranı</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['active_days'] . '</div>
            <div class="stat-label">Aktif Gün</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['longest_streak'] . '</div>
            <div class="stat-label">En Uzun Seri</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['current_streak'] . '</div>
            <div class="stat-label">Mevcut Seri</div>
        </div>
    </div>
    
    <h3>Günlük Kayıtlar</h3>
    <table>
        <tr>
            <th>Tarih</th>
            <th>Kayıt Sayısı</th>
            <th>Notlar</th>
        </tr>';
        
        foreach ($reportData['logs'] as $log) {
            $html .= '<tr>
                <td>' . $log['date'] . '</td>
                <td>' . $log['count'] . '</td>
                <td>' . ($log['notes'] ?? '') . '</td>
            </tr>';
        }
        
        $html .= '</table>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * NOTE: Generate CSV data for habit report with Progress Trend
     * 🧠 Omar BYA - Enhanced CSV export with Progress Trend data
     */
    private function generateHabitCsvData($habit, $reportData, $dateRange, $period)
    {
        $csvLines = [];
        
        // Progress Trend chart data oluştur
        $chartData = $this->generateChartData($habit['id'], $dateRange['start'], $dateRange['end']);
        
        // CSV Headers
        $csvLines[] = 'Alışkanlık Adı,Tarih,Kayıt Sayısı,Notlar,Başarı Oranı,Toplam Gün,Aktif Gün,En Uzun Seri,Mevcut Seri,Progress Trend';
        
        // İstatistik satırı
        $csvLines[] = sprintf(
            '"%s","İSTATİSTİK",%d,"%s",%.2f,%d,%d,%d,%d,"Progress Trend Grafiği"',
            $habit['name'],
            $reportData['stats']['total_count'],
            'Rapor Özeti',
            $reportData['stats']['success_rate'],
            $reportData['stats']['total_days'],
            $reportData['stats']['active_days'],
            $reportData['stats']['longest_streak'],
            $reportData['stats']['current_streak']
        );
        
        // Progress Trend verilerini ekle
        $csvLines[] = '"Progress Trend Verileri","","","","","","","","",""';
        for ($i = 0; $i < count($chartData['labels']); $i++) {
            $csvLines[] = sprintf(
                '"%s","%s","","","","","","","",%.1f',
                $habit['name'],
                $chartData['labels'][$i],
                $chartData['datasets'][0]['data'][$i]
            );
        }
        
        // Boş satır
        $csvLines[] = '"","","","","","","","","",""';
        
        // Günlük kayıtlar
        $csvLines[] = '"Günlük Kayıtlar","","","","","","","","",""';
        foreach ($reportData['logs'] as $log) {
            $csvLines[] = sprintf(
                '"%s","%s",%d,"%s",%.2f,%d,%d,%d,%d,""',
                $habit['name'],
                $log['date'],
                $log['count'],
                $log['notes'] ?? '',
                $reportData['stats']['success_rate'],
                $reportData['stats']['total_days'],
                $reportData['stats']['active_days'],
                $reportData['stats']['longest_streak'],
                $reportData['stats']['current_streak']
            );
        }
        
        return implode("\n", $csvLines);
    }
    
    /**
     * NOTE: Generate PDF HTML with SVG Chart
     * 🧠 Omar BYA - PDF için SVG tabanlı line chart
     */
    private function generatePdfHtmlWithChart($habit, $reportData, $dateRange, $period, $chartData)
    {
        $periodNames = [
            '1month' => '1 Aylık',
            '3months' => '3 Aylık',
            '6months' => '6 Aylık',
            '1year' => '1 Yıllık',
            'all' => 'Tümü'
        ];
        
        // SVG Line Chart oluştur
        $svgChart = $this->generateSvgLineChart($chartData);
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . $habit['name'] . ' - Performans Raporu</title>
    <style>
        @font-face {
            font-family: "DejaVu Sans";
            font-style: normal;
            font-weight: normal;
        }
        body { 
            font-family: "DejaVu Sans", Arial, sans-serif; 
            margin: 10px; 
            color: #333;
            font-size: 11px;
            width: 100%;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 3px;
        }
        .header p {
            color: #7f8c8d;
            font-size: 12px;
        }
        .stats { 
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .stat-box { 
            display: table-cell;
            text-align: center; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            margin: 3px;
            background: #f8f9fa;
            width: 25%;
        }
        .stat-value { 
            font-size: 16px; 
            font-weight: bold; 
            color: #2c3e50; 
            margin-bottom: 3px;
        }
        .stat-label { 
            font-size: 10px; 
            color: #7f8c8d; 
        }
        .chart-section {
            margin: 20px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .chart-title {
            text-align: center;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .chart-container {
            text-align: center;
            margin: 15px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: white;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left; 
            font-size: 9px;
        }
        th { 
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #7f8c8d;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 ' . $habit['name'] . ' - Performans Raporu</h1>
        <p>Rapor Dönemi: ' . $periodNames[$period] . ' (' . $dateRange['start'] . ' - ' . $dateRange['end'] . ')</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['success_rate'] . '%</div>
            <div class="stat-label">Başarı Oranı</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['active_days'] . '</div>
            <div class="stat-label">Aktif Gün</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['longest_streak'] . '</div>
            <div class="stat-label">En Uzun Seri</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">' . $reportData['stats']['current_streak'] . '</div>
            <div class="stat-label">Mevcut Seri</div>
        </div>
    </div>
    
    <div class="chart-section">
        ' . $svgChart . '
        <p style="text-align: center; color: #7f8c8d; font-style: italic; font-size: 10px; margin-top: 10px;">
            Kayıt/Uygulama olmayan günlerde grafik yükselir (+), uygulanan günlerde düşer (-). Düşüş trendi düzenliliği gösterir.
        </p>
    </div>
    
    <h3 style="color: #2c3e50; margin-top: 30px; font-size: 16px;">📋 Günlük Kayıtlar</h3>
    <table>
        <tr>
            <th>Tarih</th>
            <th>Kayıt Sayısı</th>
            <th>Notlar</th>
        </tr>';
        
        foreach ($reportData['logs'] as $log) {
            $html .= '<tr>
                <td>' . $log['date'] . '</td>
                <td>' . $log['count'] . '</td>
                <td>' . ($log['notes'] ?? '') . '</td>
            </tr>';
        }
        
        $html .= '</table>
        
        <div class="footer">
            <p>Rapor Oluşturulma Tarihi: ' . date('d.m.Y H:i') . '</p>
            <p>Habit Tracker 3.0 - Omar BYA Development</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * NOTE: Generate Line Chart for PDF using HTML/CSS
     * 🧠 Omar BYA - PDF için HTML/CSS tabanlı line chart
     */
    private function generateSvgLineChart($chartData)
    {
        $labels = $chartData['labels'];
        $data = $chartData['datasets'][0]['data'];
        
        if (empty($data)) {
            return '<div style="text-align: center; padding: 50px; color: #999;">Veri bulunamadı</div>';
        }
        
        $minValue = min($data);
        $maxValue = max($data);
        $valueRange = $maxValue - $minValue;
        if ($valueRange == 0) $valueRange = 1;
        
        $chartWidth = 700; // Yatay için daha geniş
        $chartHeight = 300; // Daha yüksek
        $padding = 40;
        
        // Line chart container
        $html = '<div style="border: 1px solid #ddd; padding: 20px; background: white; margin: 10px 0; position: relative;">';
        $html .= '<h4 style="text-align: center; margin: 0 0 20px 0; color: #2c3e50;">📈 Trend Analizi</h4>';
        
        // Chart area
        $html .= '<div style="position: relative; width: ' . $chartWidth . 'px; height: ' . $chartHeight . 'px; margin: 0 auto; border: 1px solid #e0e0e0; background: #fafafa;">';
        
        // Y-axis labels
        for ($i = 0; $i <= 5; $i++) {
            $y = $padding + (($chartHeight - $padding * 2) / 5) * $i;
            $value = $maxValue - ($valueRange / 5) * $i;
            $html .= '<div style="position: absolute; left: 5px; top: ' . ($y - 8) . 'px; font-size: 10px; color: #666;">' . round($value) . '</div>';
        }
        
        // X-axis labels
        $labelCount = count($labels);
        for ($i = 0; $i < $labelCount; $i++) {
            if ($i % max(1, floor($labelCount / 6)) == 0) { // Maksimum 6 etiket
                $x = $padding + (($chartWidth - $padding * 2) / ($labelCount - 1)) * $i;
                $html .= '<div style="position: absolute; left: ' . ($x - 15) . 'px; bottom: 5px; font-size: 9px; color: #666;">' . $labels[$i] . '</div>';
            }
        }
        
        // Grid lines
        for ($i = 0; $i <= 5; $i++) {
            $y = $padding + (($chartHeight - $padding * 2) / 5) * $i;
            $html .= '<div style="position: absolute; left: ' . $padding . 'px; top: ' . $y . 'px; width: ' . ($chartWidth - $padding * 2) . 'px; height: 1px; background: #e0e0e0;"></div>';
        }
        
        // Line chart using positioned divs
        $points = [];
        for ($i = 0; $i < count($data); $i++) {
            $x = $padding + (($chartWidth - $padding * 2) / (count($data) - 1)) * $i;
            $y = $padding + ($chartHeight - $padding * 2) - (($data[$i] - $minValue) / $valueRange) * ($chartHeight - $padding * 2);
            $points[] = ['x' => $x, 'y' => $y, 'value' => $data[$i]];
        }
        
        // Draw lines between points
        for ($i = 0; $i < count($points) - 1; $i++) {
            $x1 = $points[$i]['x'];
            $y1 = $points[$i]['y'];
            $x2 = $points[$i + 1]['x'];
            $y2 = $points[$i + 1]['y'];
            
            // Calculate line length and angle
            $length = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
            $angle = atan2($y2 - $y1, $x2 - $x1) * 180 / pi();
            
            $html .= '<div style="position: absolute; left: ' . $x1 . 'px; top: ' . $y1 . 'px; width: ' . $length . 'px; height: 2px; background: #818cf8; transform-origin: 0 0; transform: rotate(' . $angle . 'deg);"></div>';
        }
        
        $html .= '</div>'; // End chart area
        
        // Legend
        $html .= '<div style="margin-top: 15px; text-align: center; font-size: 10px; color: #666;">';
        $html .= '<div style="display: inline-block; margin: 0 20px;"><span style="display: inline-block; width: 12px; height: 2px; background: #ef4444; margin-right: 5px;"></span>Trend Skoru</div>';
        $html .= '<div style="display: inline-block; margin: 0 20px;"><strong>Min:</strong> ' . round($minValue, 1) . '</div>';
        $html .= '<div style="display: inline-block; margin: 0 20px;"><strong>Max:</strong> ' . round($maxValue, 1) . '</div>';
        $html .= '<div style="display: inline-block; margin: 0 20px;"><strong>Ortalama:</strong> ' . round(array_sum($data) / count($data), 1) . '</div>';
        $html .= '</div>';
        
        $html .= '</div>'; // End container
        
        return $html;
    }
} 