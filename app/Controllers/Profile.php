<?php

namespace App\Controllers;

class Profile extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        return view('main/profile');
    }
}
