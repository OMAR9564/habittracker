<?php

/**
 * 🧠 Omar BYA - Authentication Controller
 * 🤖 Optimized by O.B. - Ultra Flexible PIN System (3-8 digits)
 * Code aligned with Omar BYA's Personal Style Sheet
 */

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
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
        
        return view('auth/login', ['title' => 'Sign In - Habit Tracker']);
    }
    
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $pin = $this->request->getPost('pin');
            
            // Throttle check
            if ($this->userModel->isThrottled($username)) {
                return redirect()->back()->with('error', 'Too many failed attempts. Please try again later.');
            }
            
            $user = $this->userModel->getUserByCredentials($username, $pin);
            
            if ($user) {
                // Clear failed attempts
                $this->userModel->clearFailedAttempts($username);
                
                // Set session
                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'isLoggedIn' => true
                ]);
                
                return redirect()->to('/dashboard')->with('success', 'Successfully signed in! Welcome back.');
            } else {
                // Record failed attempt
                $this->userModel->recordFailedAttempt($username);
                
                return redirect()->back()->with('error', 'Invalid username or PIN.');
            }
        }
        
        return $this->index();
    }
    
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            
            $rules = [
                'username' => [
                    'rules' => 'required|min_length[3]|max_length[30]|alpha_numeric_space|is_unique[users.username]',
                    'errors' => [
                        'required' => 'Username field is required.',
                        'min_length' => 'Username must be at least {param} characters long.',
                        'max_length' => 'Username cannot exceed {param} characters.',
                        'alpha_numeric_space' => 'Username can only contain letters, numbers and spaces.',
                        'is_unique' => 'This username is already taken. Please choose a different username.'
                    ]
                ],
                'pin' => [
                    'rules' => 'required|min_length[3]|max_length[8]|numeric',
                    'errors' => [
                        'required' => 'PIN code field is required.',
                        'min_length' => 'PIN code must be at least {param} digits long.',
                        'max_length' => 'PIN code cannot exceed {param} digits.',
                        'numeric' => 'PIN code must contain only numbers.'
                    ]
                ],
                'confirm_pin' => [
                    'rules' => 'required|matches[pin]',
                    'errors' => [
                        'required' => 'PIN code confirmation is required.',
                        'matches' => 'PIN code confirmation must match the PIN code.'
                    ]
                ]
            ];
            
            if (!$this->validate($rules)) {
                return view('auth/register', [
                    'title' => 'Sign Up - Habit Tracker',
                    'validation' => $this->validator
                ]);
            }

            // Create user
            $userData = [
                'username' => $this->request->getPost('username'),
                'pin_hash' => password_hash($this->request->getPost('pin'), PASSWORD_DEFAULT),
                'failed_attempts' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->save($userData)) {
                return redirect()->to('/auth')->with('success', 'Account created successfully! You can now sign in.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while creating the account. Please try again.');
            }
        }
        
        return view('auth/register', ['title' => 'Sign Up - Habit Tracker']);
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Successfully signed out. See you soon!');
    }
} 