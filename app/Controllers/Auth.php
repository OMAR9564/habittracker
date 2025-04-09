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
        if ($this->request->getMethod() === 'POST') {
            
            $rules = [
                'username' => [
                    'rules' => 'required|min_length[3]|max_length[30]|alpha_numeric_space|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Kullanıcı adı alanı zorunludur.',
                        'min_length' => 'Kullanıcı adı en az {param} karakter olmalıdır.',
                        'max_length' => 'Kullanıcı adı en fazla {param} karakter olabilir.',
                        'alpha_numeric_space' => 'Kullanıcı adı sadece harf, rakam ve boşluk içerebilir.',
                        'is_unique' => 'Bu kullanıcı adı zaten kullanımda. Lütfen başka bir kullanıcı adı seçin.'
                    ]
                ],
                'pin' => [
                    'rules' => 'required|exact_length[6]|numeric|max_length[6]',
                    'errors' => [
                        'required' => 'PIN kodu alanı zorunludur.',
                        'exact_length' => 'PIN kodu tam olarak {param} haneli olmalıdır.',
                        'numeric' => 'PIN kodu sadece rakamlardan oluşmalıdır.',
                        'max_length' => 'PIN kodu en fazla {param} karakter olabilir.'
                    ]
                ],
                'confirm_pin' => [
                    'rules' => 'required|matches[pin]',
                    'errors' => [
                        'required' => 'PIN kodu onayı zorunludur.',
                        'matches' => 'PIN kodu onayı, PIN kodu ile eşleşmelidir.'
                    ]
                ]
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
                
                try {
                    // Hata ayıklama için
                    log_message('debug', 'Kullanıcı kaydı deneniyor: ' . json_encode($data));
                    
                    $userId = $this->userModel->insert($data);
                    
                    if (!$userId) {
                        log_message('error', 'Kullanıcı kaydı başarısız: ' . print_r($this->userModel->errors(), true));
                        return redirect()->back()->with('error', 'Kayıt işlemi sırasında bir hata oluştu.');
                    }
                    
                    log_message('debug', 'Kullanıcı başarıyla kaydedildi. ID: ' . $userId);
                    
                    // Başarı mesajı ve giriş sayfasına yönlendirme
                    return redirect()->to('/auth')->with('message', 'Kayıt başarılı. Lütfen giriş yapın.');
                } catch (\Exception $e) {
                    log_message('error', 'Kayıt işlemi istisnası: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Sistemde bir hata oluştu: ' . $e->getMessage());
                }
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