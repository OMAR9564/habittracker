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
        omarTrace('Reports accessed', ['user_id' => $userId]);
        
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
        
        omarTrace('CSV Export requested', [
            'user_id' => $userId,
            'date_range' => [$startDate, $endDate],
            'habit_id' => $habitId
        ]);
        
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
            $currentStreak = calculateHabitStreak($allLogs);
            
            $habitData = [
                'id' => $habit['id'],
                'name' => $habit['name'],
                'level' => $habit['level'],
                'total_days' => $totalDays,
                'active_days' => $activeDays,
                'success_rate' => $successRate,
                'current_streak' => $currentStreak,
                'trend_data' => generateChartData($logs, 30)
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
} 