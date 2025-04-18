<?php

namespace App\Models;

use CodeIgniter\Model;

class GoalHistoryModel extends Model
{
    protected $table            = 'goals_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'habit_id', 'goal_days', 'start_date', 'end_date', 
        'is_completed', 'completion_percentage'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'habit_id' => 'required|numeric',
        'goal_days' => 'required|numeric',
        'start_date' => 'required|valid_date',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Yeni bir hedef başlatır
     */
    public function startNewGoal($habitId, $goalDays)
    {
        // Önce aktif hedefi bul ve tamamla
        $this->completeActiveGoal($habitId);
        
        // Yeni hedef oluştur
        $data = [
            'habit_id' => $habitId,
            'goal_days' => $goalDays,
            'start_date' => date('Y-m-d'),
            'is_completed' => false,
            'completion_percentage' => 0.00
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Aktif hedefi bulur
     */
    public function getActiveGoal($habitId)
    {
        return $this->where('habit_id', $habitId)
                    ->where('is_completed', false)
                    ->first();
    }
    
    /**
     * Aktif hedefi tamamlar
     */
    public function completeActiveGoal($habitId, $isSuccessful = false)
    {
        $activeGoal = $this->getActiveGoal($habitId);
        
        if (!$activeGoal) {
            return false;
        }
        
        $habitLogModel = model(HabitLogModel::class);
        
        $startDate = $activeGoal['start_date'];
        $endDate = date('Y-m-d');
        
        // Başarı yüzdesini hesapla
        $percentage = $habitLogModel->calculateSuccessPercentage(
            $habitId, 
            $startDate, 
            $endDate
        );
        
        $data = [
            'end_date' => $endDate,
            'is_completed' => true,
            'completion_percentage' => $percentage
        ];
        
        $this->update($activeGoal['id'], $data);
        
        // Başarı yüzdesini döndür
        return $percentage;
    }
    
    /**
     * Belirli bir alışkanlık için tüm hedef geçmişini alır
     */
    public function getGoalHistory($habitId)
    {
        return $this->where('habit_id', $habitId)
                    ->orderBy('start_date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Ortalama başarı oranını hesaplar
     */
    public function getAverageCompletion($habitId)
    {
        $goals = $this->where('habit_id', $habitId)
                      ->where('is_completed', true)
                      ->findAll();
        
        if (empty($goals)) {
            return 0;
        }
        
        $totalPercentage = 0;
        foreach ($goals as $goal) {
            $totalPercentage += $goal['completion_percentage'];
        }
        
        return $totalPercentage / count($goals);
    }

    /**
     * Aktif hedefi sıfırlar ve süreyi günceller
     * Kötü alışkanlık yapıldığında kullanılır
     */
    public function resetActiveGoal($habitId, $newGoalDays)
    {
        $activeGoal = $this->getActiveGoal($habitId);
        
        if (!$activeGoal) {
            // Aktif hedef yoksa yeni oluştur
            return $this->startNewGoal($habitId, $newGoalDays);
        }
        
        // Mevcut hedefi tamamla (başarısız olarak)
        $this->completeActiveGoal($habitId, false);
        
        // Bugünün tarihini al
        $today = date('Y-m-d');
        
        // Yeni hedef oluştur (güncellenmiş süre ile)
        $data = [
            'habit_id' => $habitId,
            'goal_days' => $newGoalDays,
            'start_date' => $today, // Bugünü başlangıç olarak ayarla
            'is_completed' => false,
            'completion_percentage' => 0.00
        ];
        
        // Eski hedefi güncelle
        $this->db->table('habits')
                 ->where('id', $habitId)
                 ->update([
                     'current_goal' => $newGoalDays,
                     'current_goal_start_date' => $today
                 ]);
        
        return $this->insert($data);
    }
} 