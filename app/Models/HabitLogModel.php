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
    public function getHabitLogs($habitId, $startDate = null, $endDate = null)
    {
        $builder = $this->where('habit_id', $habitId);
        
        if ($startDate) {
            $builder->where('date >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('date <=', $endDate);
        }
        
        return $builder->orderBy('date', 'DESC')->findAll();
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
    public function getTrendData($habitId, $days = 30)
    {
        $logs = $this->getRecentLogs($habitId, $days);
        $trendData = [];
        
        // Son X günü doldur
        $endDate = new \DateTime();
        $startDate = clone $endDate;
        $startDate->modify("-$days days");
        
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);
        
        // Tüm günler için boş veri oluştur
        foreach ($dateRange as $date) {
            $dateStr = $date->format('Y-m-d');
            $trendData[$dateStr] = 0;
        }
        
        // Log kayıtlarını ekle
        foreach ($logs as $log) {
            if (isset($trendData[$log['date']])) {
                $trendData[$log['date']] = $log['count'];
            }
        }
        
        return $trendData;
    }
} 