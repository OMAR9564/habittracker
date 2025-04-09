<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        // Zaten giriş yapılmışsa ana sayfaya yönlendir
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }
    
    public function login()
    {
        $username = $this->request->getPost('username');
        $pin = $this->request->getPost('pin');
        
        // Giriş denemelerini kontrol et
        $failedAttempts = $this->userModel->checkFailedAttempts($username);
        
        if ($failedAttempts >= 3) {
            return redirect()->back()->with('error', 'Çok fazla başarısız giriş denemesi. Lütfen daha sonra tekrar deneyin.');
        }
        
        // Kullanıcı adı ve PIN kontrolü
        if ($this->userModel->verifyPin($username, $pin)) {
            // Başarılı giriş, kullanıcı bilgilerini al
            $user = $this->userModel->where('username', $username)->first();
            
            // Oturum bilgilerini ayarla
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'isLoggedIn' => true
            ];
            
            session()->set($sessionData);
            
            // Başarısız deneme sayacını sıfırla
            $this->userModel->resetFailedAttempts($username);
            
            return redirect()->to('/dashboard');
        } else {
            // Başarısız denemeyi kaydet
            $this->userModel->recordFailedAttempt($username);
            
            return redirect()->back()->with('error', 'Geçersiz kullanıcı adı veya PIN.');
        }
    }
    
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
                'pin' => 'required|exact_length[6]|numeric',
                'confirm_pin' => 'required|matches[pin]'
            ];
            
            if ($this->validate($rules)) {
                $username = $this->request->getPost('username');
                $pin = $this->request->getPost('pin');
                
                // PIN'i hash'le
                $pinHash = password_hash($pin, PASSWORD_DEFAULT);
                
                // Yeni kullanıcı oluştur
                $data = [
                    'username' => $username,
                    'pin_hash' => $pinHash,
                    'failed_attempts' => 0
                ];
                
                $this->userModel->insert($data);
                
                // Başarı mesajı ve giriş sayfasına yönlendirme
                return redirect()->to('/auth')->with('message', 'Kayıt başarılı. Lütfen giriş yapın.');
            } else {
                // Hataları gösterip formu yeniden yükleme
                return view('auth/register', ['validation' => $this->validator]);
            }
        }
        
        return view('auth/register');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth');
    }
} 