<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }

    public function panduan(): string
    {
        return view('panduan');
    }

    public function panduanRepositori(): string
    {
        return view('panduan_repositori');
    }

    public function template(): string
    {
        return view('template');
    }

    public function repositoriData(): string
    {
        return view('repositori_data');
    }

    public function about(): string
    {
        return view('about');
    }
}
