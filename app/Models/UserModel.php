<?php

/**
 * 🧠 Omar BYA - User Model with Enhanced Security
 * 🤖 Optimized by O.B. - Ultra Flexible Authentication System
 * Code aligned with Omar BYA's Personal Style Sheet
 */

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'pin_hash', 'failed_attempts', 'last_attempt_time', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'pin_hash' => 'required'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // 🤖 Optimized by O.B. - Security constants
    private const MAX_FAILED_ATTEMPTS = 3;
    private const THROTTLE_MINUTES = 15;

    /**
     * NOTE: Enhanced user authentication with credentials
     */
    public function getUserByCredentials($username, $pin)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return false;
        }
        
        // Verify PIN
        if (password_verify($pin, $user['pin_hash'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * NOTE: Check if user is throttled due to failed attempts
     */
    public function isThrottled($username)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return false;
        }
        
        // Check if user has exceeded max attempts
        if ($user['failed_attempts'] >= self::MAX_FAILED_ATTEMPTS) {
            // Check if throttle time has passed
            if ($user['last_attempt_time']) {
                $lastAttempt = strtotime($user['last_attempt_time']);
                $throttleTime = $lastAttempt + (self::THROTTLE_MINUTES * 60);
                
                if (time() < $throttleTime) {
                    return true; // Still throttled
                } else {
                    // Throttle time has passed, reset attempts
                    $this->clearFailedAttempts($username);
                    return false;
                }
            }
        }
        
        return false;
    }

    /**
     * NOTE: Record a failed login attempt
     */
    public function recordFailedAttempt($username)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return false;
        }
        
        $data = [
            'failed_attempts' => $user['failed_attempts'] + 1,
            'last_attempt_time' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($user['id'], $data);
    }

    /**
     * NOTE: Clear failed attempts after successful login
     */
    public function clearFailedAttempts($username)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return false;
        }
        
        $data = [
            'failed_attempts' => 0,
            'last_attempt_time' => null
        ];
        
        return $this->update($user['id'], $data);
    }

    /**
     * NOTE: Legacy methods for backward compatibility
     */
    public function verifyPin($username, $pin)
    {
        return $this->getUserByCredentials($username, $pin) !== false;
    }

    public function checkFailedAttempts($username)
    {
        $user = $this->where('username', $username)->first();
        return $user ? $user['failed_attempts'] : 0;
    }

    public function resetFailedAttempts($username)
    {
        return $this->clearFailedAttempts($username);
    }
} 