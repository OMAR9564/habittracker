<?php

namespace App\Controllers;

use App\Models\HabitModel;
use App\Models\HabitLogModel;
use App\Models\GoalHistoryModel;

class Dashboard extends BaseController
{
    protected $habitModel;
    protected $habitLogModel;
    protected $goalHistoryModel;
    
    public function __construct()
    {
        $this->habitModel = new HabitModel();
        $this->habitLogModel = new HabitLogModel();
        $this->goalHistoryModel = new GoalHistoryModel();
    }
    
    public function index()
    {
        // Oturum kontrolü
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        
        // Kullanıcının alışkanlıklarını getir
        $habits = $this->habitModel->getUserHabits($userId);
        
        // Her alışkanlık için son 30 günlük trend verisini ekle
        foreach ($habits as &$habit) {
            $habit['trend_data'] = $this->habitLogModel->getTrendData($habit['id'], 30);
            $habit['active_goal'] = $this->goalHistoryModel->getActiveGoal($habit['id']);
        }
        
        $data = [
            'title' => 'Kontrol Paneli',
            'habits' => $habits
        ];
        
        return view('dashboard/index', $data);
    }
    
    public function addHabit()
    {
        // Oturum kontrolü
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]'
            ];
            
            if ($this->validate($rules)) {
                $userId = session()->get('user_id');
                
                $data = [
                    'user_id' => $userId,
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'level' => 0,
                    'current_goal' => 3, // Başlangıç hedefi 3 gün
                    'current_goal_start_date' => date('Y-m-d'),
                    'success_percentage' => 100.00
                ];
                
                // Alışkanlığı oluştur
                $habitId = $this->habitModel->insert($data);
                
                // İlk hedefi oluştur
                $this->goalHistoryModel->startNewGoal($habitId, 3);
                
                return redirect()->to('/dashboard')->with('message', 'Alışkanlık başarıyla eklendi.');
            } else {
                return view('dashboard/add_habit', ['validation' => $this->validator]);
            }
        }
        
        return view('dashboard/add_habit');
    }
    
    public function viewHabit($id = null)
    {
        // Oturum kontrolü
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        
        // Alışkanlığı getir ve kullanıcı sahipliğini kontrol et
        $habit = $this->habitModel->find($id);
        
        if (!$habit || $habit['user_id'] != $userId) {
            return redirect()->to('/dashboard')->with('error', 'Alışkanlık bulunamadı.');
        }
        
        // Alışkanlık günlüklerini getir
        $logs = $this->habitLogModel->getHabitLogs($id);
        
        // Hedef geçmişini getir
        $goalHistory = $this->goalHistoryModel->getGoalHistory($id);
        
        // Aktif hedefi getir
        $activeGoal = $this->goalHistoryModel->getActiveGoal($id);
        
        // Trend verilerini getir (son 90 gün)
        $trendData = $this->habitLogModel->getTrendData($id, 90);
        
        $data = [
            'title' => $habit['name'],
            'habit' => $habit,
            'logs' => $logs,
            'goalHistory' => $goalHistory,
            'activeGoal' => $activeGoal,
            'trendData' => $trendData
        ];
        
        return view('dashboard/view_habit', $data);
    }
    
    public function logHabit($id = null)
    {
        // Oturum kontrolü
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        
        // Alışkanlığı getir ve kullanıcı sahipliğini kontrol et
        $habit = $this->habitModel->find($id);
        
        if (!$habit || $habit['user_id'] != $userId) {
            return redirect()->to('/dashboard')->with('error', 'Alışkanlık bulunamadı.');
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'date' => 'required|valid_date',
                'count' => 'required|numeric|greater_than[0]',
                'notes' => 'permit_empty|max_length[500]'
            ];
            
            if ($this->validate($rules)) {
                $date = $this->request->getPost('date');
                $count = $this->request->getPost('count');
                $notes = $this->request->getPost('notes');
                
                // Var olan giriş için güncelleme kontrol et
                $existingLog = $this->habitLogModel
                    ->where('habit_id', $id)
                    ->where('date', $date)
                    ->first();
                
                $data = [
                    'habit_id' => $id,
                    'date' => $date,
                    'count' => $count,
                    'notes' => $notes
                ];
                
                if ($existingLog) {
                    $this->habitLogModel->update($existingLog['id'], $data);
                    $message = 'Alışkanlık günlüğü güncellendi.';
                } else {
                    $this->habitLogModel->insert($data);
                    $message = 'Alışkanlık günlüğü eklendi.';
                    
                    // Seviyeyi düşür ve başarı yüzdesini güncelle
                    // Alışkanlığı yapıp kaydetmek seviyeyi ve başarı oranını etkiler
                    $this->habitModel->updateLevel($id, -1); // Seviyeyi 1 azalt
                    
                    // Başarı yüzdesini güncelle (aktif hedefin başlangıcından itibaren)
                    $activeGoal = $this->goalHistoryModel->getActiveGoal($id);
                    if ($activeGoal) {
                        $startDate = $activeGoal['start_date'];
                        $endDate = date('Y-m-d');
                        
                        $percentage = $this->habitLogModel->calculateSuccessPercentage(
                            $id, 
                            $startDate, 
                            $endDate
                        );
                        
                        $this->habitModel->updateSuccessPercentage($id, $percentage);
                        
                        // Hedef tamamlandı mı kontrol et
                        $goalDays = $activeGoal['goal_days'];
                        $start = new \DateTime($startDate);
                        $end = new \DateTime($endDate);
                        $daysPassed = $start->diff($end)->days + 1;
                        
                        if ($daysPassed >= $goalDays) {
                            // Hedefi tamamla ve yeni hedef oluştur
                            $this->goalHistoryModel->completeActiveGoal($id, true);
                            
                            // Yeni hedefi belirle
                            $nextGoalDays = $this->habitModel->calculateNextGoal($goalDays);
                            $this->habitModel->update($id, ['current_goal' => $nextGoalDays]);
                            
                            // Seviye artışı (hedef başarıyla tamamlandığında)
                            $this->habitModel->updateLevel($id, 5); // 5 seviye ekle
                            
                            // Yeni hedef kaydını oluştur
                            $this->goalHistoryModel->startNewGoal($id, $nextGoalDays);
                            
                            $message .= ' Tebrikler! Hedefinizi tamamladınız ve yeni bir hedefe geçtiniz.';
                        }
                    }
                }
                
                return redirect()->to("/dashboard/habit/$id")->with('message', $message);
            } else {
                return view('dashboard/log_habit', [
                    'validation' => $this->validator,
                    'habit' => $habit
                ]);
            }
        }
        
        return view('dashboard/log_habit', ['habit' => $habit]);
    }
    
    public function deleteHabit($id = null)
    {
        // Oturum kontrolü
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        
        // Alışkanlığı getir ve kullanıcı sahipliğini kontrol et
        $habit = $this->habitModel->find($id);
        
        if (!$habit || $habit['user_id'] != $userId) {
            return redirect()->to('/dashboard')->with('error', 'Alışkanlık bulunamadı.');
        }
        
        // Alışkanlığı sil (soft delete)
        $this->habitModel->delete($id);
        
        return redirect()->to('/dashboard')->with('message', 'Alışkanlık başarıyla silindi.');
    }
} 