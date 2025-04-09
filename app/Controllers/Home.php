<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Ana Sayfa'
        ];
        
        return view('home', $data);
    }
}
