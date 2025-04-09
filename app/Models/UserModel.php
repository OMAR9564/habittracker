<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'pin_hash', 'failed_attempts', 'last_attempt_time'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'pin_hash' => 'required'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Kullanıcı kimlik doğrulama
     */
    public function verifyPin($username, $pin)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return false;
        }
        
        return password_verify($pin, $user['pin_hash']);
    }

    /**
     * Başarısız giriş denemelerini kontrol eder
     */
    public function checkFailedAttempts($username)
    {
        $user = $this->where('username', $username)->first();
        
        if (!$user) {
            return 0;
        }
        
        return $user['failed_attempts'];
    }

    /**
     * Başarısız giriş denemesini kaydeder
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
     * Başarısız giriş denemelerini sıfırlar
     */
    public function resetFailedAttempts($username)
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
} 