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
     * NOTE: Calculate habit streak days
     * 
     * @param array $logs
     * @return int
     */
    function calculateHabitStreak($logs)
    {
        if (empty($logs)) {
            return 0;
        }

        $streak = 0;
        $today = new DateTime();
        $currentDate = clone $today;

        // Sort logs by date desc
        usort($logs, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        foreach ($logs as $log) {
            $logDate = new DateTime($log['date']);
            $daysDiff = $currentDate->diff($logDate)->days;

            if ($daysDiff <= 1) {
                $streak++;
                $currentDate = $logDate;
            } else {
                break;
            }
        }

        return $streak;
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