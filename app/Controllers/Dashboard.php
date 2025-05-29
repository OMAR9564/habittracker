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
        
        // 🤖 Optimized by O.B. - Enhanced security + direct helper loading
        helper(['security', 'form']);
        
        // Direct helper loading as fallback
        if (!function_exists('omarTrace')) {
            require_once APPPATH . 'Helpers/omar_helper.php';
        }
    }
    
    public function index()
    {
        // ✅ Rule 6: Separation of Concerns - Security check first
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        $userId = session()->get('user_id');
        // omarTrace('Dashboard accessed', ['user_id' => $userId]); // Temporarily disabled
        log_message('info', 'Dashboard accessed by user: ' . $userId);
        
        // Kullanıcının alışkanlıklarını getir
        $habits = $this->habitModel->getUserHabits($userId);
        
        // Her alışkanlık için hedef tamamlama kontrolü ve trend verilerini güncelleme
        foreach ($habits as &$habit) {
            // Hedef tamamlanma kontrolü yap
            $goalCompleted = $this->checkGoalCompletion($habit['id']);
            
            // Eğer hedef tamamlandıysa, başarı mesajını session'a ekle
            if ($goalCompleted) {
                session()->setFlashdata('goal_completed_' . $habit['id'], 'Tebrikler! ' . $habit['name'] . ' alışkanlığında hedefinizi tamamladınız!');
            }
            
            // 🤖 Optimized by O.B. - Enhanced streak calculation (simplified)
            $logs = $this->habitLogModel->getHabitLogs($habit['id']);
            $streakData = $this->calculateSimpleStreak($logs); // Use local function instead
            $habit['streak_data'] = $streakData;
            
            // Trend verilerini güncelle
            $habit['trend_data'] = $this->habitLogModel->getTrendData($habit['id'], 30, true);
            $habit['active_goal'] = $this->goalHistoryModel->getActiveGoal($habit['id']);
            
            // NOTE: Fix counters calculation
            $habit['total_logs'] = count($logs);
            $habit['last_activity'] = $streakData['last_activity'];
        }
        
        $data = [
            'title' => 'Kontrol Paneli',
            'habits' => $habits
        ];
        
        return view('dashboard/index', $data);
    }
    
    public function addHabit()
    {
        // ✅ Rule 6: Separation of Concerns - Security check first
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        if ($this->request->getMethod() === 'POST') {
            // NOTE: Enhanced validation with CSRF protection
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|alpha_numeric_space',
                'description' => 'permit_empty|max_length[500]'
            ];
            
            if ($this->validate($rules)) {
                $userId = session()->get('user_id');
                
                // NOTE: Sanitize input using basic PHP functions instead of helper
                $sanitizedInput = [
                    'name' => trim(strip_tags($this->request->getPost('name') ?? '')),
                    'description' => trim(strip_tags($this->request->getPost('description') ?? ''))
                ];
                
                $data = [
                    'user_id' => $userId,
                    'name' => $sanitizedInput['name'],
                    'description' => $sanitizedInput['description'],
                    'level' => 0,
                    'current_goal' => 3, // Başlangıç hedefi 3 gün
                    'current_goal_start_date' => date('Y-m-d'),
                    'success_percentage' => 100.00
                ];
                
                // Alışkanlığı oluştur
                $habitId = $this->habitModel->insert($data);
                
                if ($habitId) {
                    // İlk hedefi oluştur
                    $this->goalHistoryModel->startNewGoal($habitId, 3);
                    // omarTrace('New habit created', ['habit_id' => $habitId, 'user_id' => $userId]); // Temporarily disabled
                    log_message('info', 'New habit created: ' . $habitId . ' for user: ' . $userId);
                    
                    return redirect()->to('/dashboard')->with('message', 'Alışkanlık başarıyla eklendi.');
                } else {
                    return redirect()->back()->with('error', 'Alışkanlık eklenirken bir hata oluştu.');
                }
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
        
        // Hedef tarihlerini kontrol et ve güncelle
        if ($activeGoal) {
            // Son günlüğün tarihini kontrol et - eğer hedef başlangıç tarihinden sonra günlük eklenmiş, 
            // hedef sıfırlanmamış olabilir
            $lastLog = null;
            if (!empty($logs)) {
                $lastLog = $logs[0]; // Günlükler tarihe göre sıralandığından ilk eleman en son kayıt
            }
            
            // Eğer son günlük aktif hedef başlangıç tarihinden sonraysa ve daha önce
            // hedef sıfırlanmadıysa, hedefi sıfırla
            if ($lastLog && strtotime($lastLog['date']) >= strtotime($activeGoal['start_date'])) {
                // Bugünün tarihiyle karşılaştır
                if (date('Y-m-d') != $activeGoal['start_date']) {
                    // Verileri güncelle
                    $this->refreshHabitData($id);
                    
                    // Güncel verileri tekrar al
                    $activeGoal = $this->goalHistoryModel->getActiveGoal($id);
                    $logs = $this->habitLogModel->getHabitLogs($id);
                    $goalHistory = $this->goalHistoryModel->getGoalHistory($id);
                }
            }
        }
        
        // Tüm geçmişten itibaren trend verilerini getir
        $trendData = $this->habitLogModel->getTrendData($id, 90, true);
        
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
        
        if ($this->request->getMethod() === 'POST') {
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
                    
                    // Yeni başarı sistemini uygula - işaretlenmeyen günlerde başarı artacak, işaretlenen günlerde düşecek
                    $this->habitLogModel->updateHabitSuccessScore($id);
                    
                    // Aktif hedefi getir
                    $activeGoal = $this->goalHistoryModel->getActiveGoal($id);
                    if ($activeGoal) {
                        // Hedef süresini azalt (kötü alışkanlık yapıldığında)
                        $currentGoalDays = $activeGoal['goal_days'];
                        $newGoalDays = max(3, $currentGoalDays - 1); // Minimum 3 gün olacak şekilde azalt
                        
                        // Aktif hedefi güncelle - sıfırla ve yeni süreyi ayarla
                        $this->goalHistoryModel->resetActiveGoal($id, $newGoalDays);
                        
                        // Habit tablosundaki hedef süresini de güncelle
                        $this->habitModel->update($id, [
                            'current_goal' => $newGoalDays,
                            'current_goal_start_date' => date('Y-m-d') // Başlangıç tarihini de güncelle
                        ]);
                        
                        $message .= ' Kötü alışkanlık yapıldığı için hedef süresi ' . $currentGoalDays . ' günden ' . $newGoalDays . ' güne düşürüldü ve süre sıfırlandı. Yeni başlangıç tarihi: ' . date('d/m/Y');
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
    
    /**
     * Hedefin tamamlanıp tamamlanmadığını kontrol eder
     */
    private function checkGoalCompletion($habitId)
    {
        // Aktif hedefi getir
        $activeGoal = $this->goalHistoryModel->getActiveGoal($habitId);
        
        if (!$activeGoal) {
            return false;
        }
        
        $goalDays = $activeGoal['goal_days'];
        $startDate = $activeGoal['start_date'];
        $endDate = date('Y-m-d');
        
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $daysPassed = $start->diff($end)->days + 1;
        
        if ($daysPassed >= $goalDays) {
            // Hedefi tamamla ve başarı yüzdesini hesapla
            $completionPercentage = $this->goalHistoryModel->completeActiveGoal($habitId, true);
            
            // Başarı yüzdesine göre yeni hedefi belirle
            $nextGoalDays = $this->habitModel->calculateNextGoal($goalDays, $completionPercentage);
            $this->habitModel->update($habitId, ['current_goal' => $nextGoalDays]);
            
            // Seviye artışı (hedef başarıyla tamamlandığında)
            $this->habitModel->updateLevel($habitId, 5); // 5 seviye ekle
            
            // Yeni hedef kaydını oluştur
            $this->goalHistoryModel->startNewGoal($habitId, $nextGoalDays);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Alışkanlık verilerini güncelleme
     */
    public function refreshHabitData($id = null)
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
        
        // Aktif hedefi getir
        $activeGoal = $this->goalHistoryModel->getActiveGoal($id);
        
        // Sorun tespiti - Son günlüğün tarihini kontrol et
        $lastLog = null;
        $message = '';
        
        if (!empty($logs) && $activeGoal) {
            $lastLog = $logs[0]; // Günlükler tarihe göre sıralandığından ilk eleman en son kayıt
            $startDate = new \DateTime($activeGoal['start_date']);
            $lastLogDate = new \DateTime($lastLog['date']);
            
            // Eğer son günlük, başlangıç tarihinden sonra eklenmişse
            if ($lastLogDate >= $startDate) {
                // Hedef süresi ve başlangıç tarihini güncelle
                $currentGoalDays = $activeGoal['goal_days'];
                $newGoalDays = max(3, $currentGoalDays - 1); // Minimum 3 gün olacak şekilde azalt
                
                // Aktif hedefi güncelle - sıfırla ve yeni süreyi ayarla
                $this->goalHistoryModel->resetActiveGoal($id, $newGoalDays);
                
                // Habit tablosundaki hedef süresini de güncelle
                $this->habitModel->update($id, [
                    'current_goal' => $newGoalDays,
                    'current_goal_start_date' => date('Y-m-d') // Bugünü başlangıç olarak ayarla
                ]);
                
                $message = 'Hedef verileriniz yenilendi. Son kayıt tarihinden dolayı hedef süresi ' . $currentGoalDays . 
                           ' günden ' . $newGoalDays . ' güne düşürüldü ve süre sıfırlandı.';
            } else {
                // Hedef tamamlandı mı kontrol et
                $startDate = new \DateTime($activeGoal['start_date']);
                $today = new \DateTime(date('Y-m-d'));
                $daysPassed = $startDate->diff($today)->days;
                $goalDays = $activeGoal['goal_days'];
                
                // Eğer hedef tamamlandıysa
                if ($daysPassed >= $goalDays) {
                    // Hedefi tamamla ve başarı yüzdesini hesapla
                    $completionPercentage = $this->goalHistoryModel->completeActiveGoal($id, true);
                    
                    // Başarı yüzdesine göre yeni hedefi belirle
                    $nextGoalDays = $this->habitModel->calculateNextGoal($goalDays, $completionPercentage);
                    $this->habitModel->update($id, [
                        'current_goal' => $nextGoalDays,
                        'current_goal_start_date' => date('Y-m-d') // Bugünü başlangıç olarak ayarla
                    ]);
                    
                    // Seviye artışı (hedef başarıyla tamamlandığında)
                    $this->habitModel->updateLevel($id, 5); // 5 seviye ekle
                    
                    // Yeni hedef kaydını oluştur
                    $this->goalHistoryModel->startNewGoal($id, $nextGoalDays);
                    
                    $message = 'Tebrikler! ' . $goalDays . ' günlük hedefinizi tamamladınız. Yeni ' . $nextGoalDays . ' günlük hedefiniz başladı!';
                } else {
                    // 1. Başarı puanını güncelle
                    $newSuccessScore = $this->habitLogModel->updateHabitSuccessScore($id);
                    $message = 'Alışkanlık verileri başarıyla güncellendi. Başarı puanı: ' . number_format($newSuccessScore, 1) . '%';
                }
            }
        } else {
            // Aktif hedef yoksa, yeni bir hedef başlat
            if (!$activeGoal) {
                $this->goalHistoryModel->startNewGoal($id, $habit['current_goal']);
                $message = 'Yeni bir hedef oluşturuldu.';
            } else {
                // 1. Başarı puanını güncelle
                $newSuccessScore = $this->habitLogModel->updateHabitSuccessScore($id);
                $message = 'Alışkanlık verileri başarıyla güncellendi. Başarı puanı: ' . number_format($newSuccessScore, 1) . '%';
            }
        }
        
        // 3. Tüm trend verilerini yeniden hesapla
        $this->habitLogModel->getTrendData($id, 90, true);
        
        return redirect()->to("/dashboard/habit/$id")->with('message', $message);
    }
    
    /**
     * Simple streak calculation without helper dependency
     * 🤖 Optimized by O.B. - Temporary fallback function
     */
    private function calculateSimpleStreak($logs)
    {
        if (empty($logs)) {
            return [
                'current_streak' => 0,
                'max_streak' => 0,
                'streak_type' => 'none',
                'last_activity' => null,
                'days_since_last' => 0
            ];
        }

        // Sort logs by date descending
        usort($logs, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $today = new \DateTime();
        $currentStreak = 0;
        $lastActivityDate = $logs[0]['date'] ?? null;
        
        // Calculate current streak (days without the habit)
        if ($lastActivityDate) {
            $lastLogDate = new \DateTime($lastActivityDate);
            $daysSinceLastLog = $lastLogDate->diff($today)->days;
            
            // If last log was today, streak is 0
            if ($lastLogDate->format('Y-m-d') === $today->format('Y-m-d')) {
                $currentStreak = 0;
            } else {
                $currentStreak = $daysSinceLastLog;
            }
        }
        
        // Determine streak type
        $streakType = 'none';
        if ($currentStreak >= 7) {
            $streakType = 'excellent';
        } elseif ($currentStreak >= 3) {
            $streakType = 'good';
        } elseif ($currentStreak >= 1) {
            $streakType = 'starting';
        }
        
        return [
            'current_streak' => $currentStreak,
            'max_streak' => $currentStreak, // Simplified
            'streak_type' => $streakType,
            'last_activity' => $lastActivityDate,
            'days_since_last' => $currentStreak
        ];
    }
} 