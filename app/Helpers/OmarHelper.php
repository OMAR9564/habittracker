<?php

/**
 * 🧠 Omar BYA - Personal Helper Functions
 * Code aligned with Omar BYA's Personal Style Sheet
 */

if (!function_exists('omarTrace')) {
    /**
     * 🤖 Optimized by O.B. - Custom logging function
     * 
     * @param string $message
     * @param mixed $data
     * @return void
     */
    function omarTrace($message, $data = null)
    {
        log_message('info', '[OMAR-TRACE] ' . $message . ' ' . json_encode($data));
    }
}

if (!function_exists('calculateHabitStreak')) {
    /**
     * NOTE: Calculate habit streak days - Enhanced version
     * 🤖 Optimized by O.B. - Advanced streak calculation
     * 
     * @param array $logs
     * @return array ['current_streak', 'max_streak', 'streak_type']
     */
    function calculateHabitStreak($logs)
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

        $today = new DateTime();
        $currentStreak = 0;
        $maxStreak = 0;
        $lastActivityDate = null;
        $daysSinceLastLog = 0;
        
        // Find last activity
        if (!empty($logs)) {
            $lastActivityDate = $logs[0]['date'];
        }
        
        // Calculate current streak (days without the habit)
        if ($lastActivityDate) {
            $lastLogDate = new DateTime($lastActivityDate);
            $daysSinceLastLog = $lastLogDate->diff($today)->days;
            
            // If last log was today, streak is 0
            if ($lastLogDate->format('Y-m-d') === $today->format('Y-m-d')) {
                $currentStreak = 0;
            } else {
                $currentStreak = $daysSinceLastLog;
            }
        } else {
            $currentStreak = 30; // Default if no logs
        }
        
        // Calculate maximum streak in history
        $allDates = array_column($logs, 'date');
        
        // Create date range from first log to today
        if (!empty($allDates)) {
            $firstDate = new DateTime(min($allDates));
            $endDate = clone $today;
            
            $interval = new DateInterval('P1D');
            $dateRange = new DatePeriod($firstDate, $interval, $endDate);
            
            $tempMaxStreak = 0;
            
            foreach ($dateRange as $date) {
                $dateStr = $date->format('Y-m-d');
                
                if (!in_array($dateStr, $allDates)) {
                    $tempMaxStreak++;
                    $maxStreak = max($maxStreak, $tempMaxStreak);
                } else {
                    $tempMaxStreak = 0;
                }
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
            'max_streak' => $maxStreak,
            'streak_type' => $streakType,
            'last_activity' => $lastActivityDate,
            'days_since_last' => $daysSinceLastLog
        ];
    }
}

if (!function_exists('formatSuccessRate')) {
    /**
     * NOTE: Format success rate with color class
     * 
     * @param float $percentage
     * @return array
     */
    function formatSuccessRate($percentage)
    {
        $color = 'success-low';
        $icon = '❌';

        if ($percentage >= 70) {
            $color = 'success-high';
            $icon = '✅';
        } elseif ($percentage >= 40) {
            $color = 'success-medium'; 
            $icon = '⚠️';
        }

        return [
            'percentage' => number_format($percentage, 1),
            'color' => $color,
            'icon' => $icon
        ];
    }
}

if (!function_exists('generateHabitId')) {
    /**
     * NOTE: Generate unique habit ID
     * 
     * @return string
     */
    function generateHabitId()
    {
        return 'habit_' . uniqid() . '_' . time();
    }
}

if (!function_exists('sanitizeHabitInput')) {
    /**
     * NOTE: Sanitize habit form input
     * 
     * @param array $input
     * @return array
     */
    function sanitizeHabitInput($input)
    {
        return [
            'name' => trim(strip_tags($input['name'] ?? '')),
            'description' => trim(strip_tags($input['description'] ?? '')),
            'count' => (int) ($input['count'] ?? 1),
            'date' => $input['date'] ?? date('Y-m-d'),
            'notes' => trim(strip_tags($input['notes'] ?? ''))
        ];
    }
}

if (!function_exists('calculateNextGoalDays')) {
    /**
     * NOTE: Calculate next goal days based on success rate
     * ✅ Rule 3: Single Responsibility - One clear purpose
     * 
     * @param int $currentGoal
     * @param float $successRate
     * @return int
     */
    function calculateNextGoalDays($currentGoal, $successRate)
    {
        // Goal progression map - object map preferred over switch/case
        $goalProgression = [
            3 => 7,
            7 => 10, 
            10 => 14,
            14 => 21,
            21 => 30,
            30 => 60,
            60 => 90,
            90 => 120,
            120 => 180,
            180 => 365
        ];

        if ($successRate < 50) {
            return max(3, (int)($currentGoal * 0.8));
        } elseif ($successRate < 70) {
            return $currentGoal;
        }

        return $goalProgression[$currentGoal] ?? (int)($currentGoal * 1.2);
    }
}

if (!function_exists('generateChartData')) {
    /**
     * NOTE: Generate chart data for habit visualization
     * 
     * @param array $logs
     * @param int $days
     * @return array
     */
    function generateChartData($logs, $days = 30)
    {
        $chartData = [];
        $today = new DateTime();
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = clone $today;
            $date->sub(new DateInterval('P' . $i . 'D'));
            $dateStr = $date->format('Y-m-d');
            
            $count = 0;
            foreach ($logs as $log) {
                if ($log['date'] === $dateStr) {
                    $count = $log['count'];
                    break;
                }
            }
            
            $chartData[$date->format('M d')] = $count;
        }
        
        return $chartData;
    }
}

// 🤖 Optimized by O.B. - Helper loaded
omarTrace('Omar Helper functions loaded successfully'); 