<?php

namespace App\Models;

use CodeIgniter\Model;

class HabitLogModel extends Model
{
    protected $table            = 'habit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'habit_id', 'date', 'count', 'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'habit_id' => 'required|numeric',
        'date' => 'required|valid_date',
        'count' => 'required|numeric|greater_than[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Belirli bir alışkanlık için günlükleri getirir
     */
    public function getHabitLogs($habitId, $startDate = null, $endDate = null, $limit = null)
    {
        $builder = $this->where('habit_id', $habitId);
        
        if ($startDate) {
            $builder->where('date >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('date <=', $endDate);
        }
        
        $builder->orderBy('date', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Belirli bir tarih aralığında günleri sayar
     */
    public function countLogDaysInRange($habitId, $startDate, $endDate)
    {
        return $this->where('habit_id', $habitId)
                   ->where('date >=', $startDate)
                   ->where('date <=', $endDate)
                   ->countAllResults();
    }
    
    /**
     * Belirli bir tarih aralığı içindeki başarı yüzdesini hesaplar
     */
    public function calculateSuccessPercentage($habitId, $startDate, $endDate)
    {
        $loggedDays = $this->countLogDaysInRange($habitId, $startDate, $endDate);
        
        // Tarih aralığındaki toplam gün sayısını hesapla
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $totalDays = $interval->days + 1; // Her iki ucu dahil etmek için +1
        
        // Başarı yüzdesini hesapla
        if ($totalDays > 0) {
            return ($loggedDays / $totalDays) * 100;
        }
        
        return 0;
    }
    
    /**
     * Son X gün içindeki günlükleri getirir
     */
    public function getRecentLogs($habitId, $days = 30)
    {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-$days days"));
        
        return $this->getHabitLogs($habitId, $startDate, $endDate);
    }
    
    /**
     * Alışkanlık için günlük trend verisi oluşturur
     */
    public function getTrendData($habitId, $days = 30, $fromStartDate = false)
    {
        // Eğer kullanıcının başlangıç tarihinden beri hesaplayacaksak
        if ($fromStartDate) {
            // Alışkanlık için ilk log tarihini bul
            $firstLog = $this->where('habit_id', $habitId)
                           ->orderBy('date', 'ASC')
                           ->first();
            
            if ($firstLog) {
                $startDate = new \DateTime($firstLog['date']);
                $endDate = new \DateTime();
                $interval = $startDate->diff($endDate);
                $days = $interval->days + 1; // Başlangıç gününü de ekle
            }
        }
        
        $logs = $this->getRecentLogs($habitId, $days);
        $trendData = [];
        
        // Son X günü doldur
        $endDate = new \DateTime();
        $startDate = clone $endDate;
        $startDate->modify("-$days days");
        
        // Eğer başlangıç tarihinden hesaplayacaksak ve ilk log varsa, startDate'i güncelle
        if ($fromStartDate && isset($firstLog)) {
            $startDate = new \DateTime($firstLog['date']);
        }
        
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);
        
        // Başarı puanı - başlangıçta 0 olarak ayarlıyoruz
        $successScore = 0;
        
        // Tüm günleri itere edelim ve her gün için success score hesaplayalım
        $loggedDates = array_column($logs, 'date');
        
        // Tarih aralığını doldur
        foreach ($dateRange as $date) {
            $dateStr = $date->format('Y-m-d');
            
            // Eğer bu tarihte kayıt varsa (alışkanlık yapıldıysa) başarı düşer
            if (in_array($dateStr, $loggedDates)) {
                $successScore = max(0, $successScore - 10); // Başarı düşüşü (daha fazla)
            } else {
                // Kayıt yoksa (alışkanlık yapılmadıysa) başarı artar
                $successScore = min(100, $successScore + 5); // Başarı artışı (daha fazla)
            }
            
            // Trend datası olarak başarı puanını kaydet
            $trendData[$dateStr] = $successScore;
        }
        
        return $trendData;
    }
    
    /**
     * Alışkanlık için başarı puanını hesapla
     * İşaretlenmeyen günlerde başarı artar, işaretlenen günlerde düşer
     */
    public function calculateSuccessScoreForHabit($habitId, $days = 30)
    {
        $logs = $this->getRecentLogs($habitId, $days);
        
        // Son X günü hesapla
        $endDate = new \DateTime();
        $startDate = clone $endDate;
        $startDate->modify("-$days days");
        
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);
        
        // Başarı puanı - başlangıçta 0 olarak ayarlıyoruz
        $successScore = 0;
        
        // Loglar içindeki tarihleri al
        $loggedDates = array_column($logs, 'date');
        
        // Tarih aralığını doldur
        foreach ($dateRange as $date) {
            $dateStr = $date->format('Y-m-d');
            
            // Eğer bu tarihte kayıt varsa (alışkanlık yapıldıysa) başarı düşer
            if (in_array($dateStr, $loggedDates)) {
                $successScore = max(0, $successScore - 10); // Başarı düşüşü (daha fazla)
            } else {
                // Kayıt yoksa (alışkanlık yapılmadıysa) başarı artar
                $successScore = min(100, $successScore + 5); // Başarı artışı (daha fazla)
            }
        }
        
        return $successScore;
    }
    
    /**
     * Alışkanlık başarı puanını güncelle
     */
    public function updateHabitSuccessScore($habitId)
    {
        $score = $this->calculateSuccessScoreForHabit($habitId);
        
        // HabitModel'i yükle
        $habitModel = model('App\Models\HabitModel');
        $habit = $habitModel->find($habitId);
        
        if (!$habit) {
            return $score;
        }
        
        // Başarı puanını güncelle
        $habitModel->update($habitId, [
            'success_percentage' => $score
        ]);
        
        // Ayrıca verilerin güncelliğini kontrol edelim
        $goalHistoryModel = model('App\Models\GoalHistoryModel');
        $activeGoal = $goalHistoryModel->getActiveGoal($habitId);
        
        if ($activeGoal) {
            // Son logu kontrol et
            $logs = $this->getHabitLogs($habitId, null, null, 1);
            if (!empty($logs)) {
                $lastLog = $logs[0];
                $startDate = new \DateTime($activeGoal['start_date']);
                $lastLogDate = new \DateTime($lastLog['date']);
                
                // Eğer son log başlangıç tarihinden sonraysa, hedef sıfırlanmalı
                if ($lastLogDate >= $startDate && $lastLogDate->format('Y-m-d') != date('Y-m-d')) {
                    // Tarih bugüne ait değilse güncelleme yap
                    $currentGoalDays = $activeGoal['goal_days'];
                    $newGoalDays = max(3, $currentGoalDays - 1);
                    
                    // Hedefi resetle
                    $goalHistoryModel->resetActiveGoal($habitId, $newGoalDays);
                }
            }
        }
        
        return $score;
    }
} 