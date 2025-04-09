<?php

namespace App\Models;

use CodeIgniter\Model;

class HabitModel extends Model
{
    protected $table            = 'habits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'name', 'description', 'level', 'current_goal', 
        'current_goal_start_date', 'success_percentage'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|numeric',
        'name' => 'required|min_length[3]|max_length[100]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Belirli bir kullanıcının alışkanlıklarını alır
     */
    public function getUserHabits($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Sonraki hedef süresini hesaplar
     */
    public function calculateNextGoal($currentGoal)
    {
        // Hedef sürelerini kademeli olarak artırma
        $goalProgression = [
            3, 7, 10, 14, 21, 30, 60, 90, 120, 180, 365
        ];
        
        foreach ($goalProgression as $days) {
            if ($days > $currentGoal) {
                return $days;
            }
        }
        
        // Eğer en büyük değere ulaştıysak, %20 artış sağlayalım
        return ceil($currentGoal * 1.2);
    }

    /**
     * Alışkanlığın seviyesini günceller
     */
    public function updateLevel($habitId, $levelChange)
    {
        $habit = $this->find($habitId);
        
        if (!$habit) {
            return false;
        }
        
        $newLevel = max(0, $habit['level'] + $levelChange);
        
        return $this->update($habitId, ['level' => $newLevel]);
    }

    /**
     * Başarı yüzdesini günceller
     */
    public function updateSuccessPercentage($habitId, $percentage)
    {
        $habit = $this->find($habitId);
        
        if (!$habit) {
            return false;
        }
        
        // Yüzde değerini 0-100 arasında sınırla
        $percentage = max(0, min(100, $percentage));
        
        return $this->update($habitId, ['success_percentage' => $percentage]);
    }

    /**
     * Yeni bir hedef oluşturur
     */
    public function startNewGoal($habitId)
    {
        $habit = $this->find($habitId);
        
        if (!$habit) {
            return false;
        }
        
        $nextGoal = $this->calculateNextGoal($habit['current_goal']);
        
        return $this->update($habitId, [
            'current_goal' => $nextGoal,
            'current_goal_start_date' => date('Y-m-d')
        ]);
    }
} 