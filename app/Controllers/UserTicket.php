<?php

namespace App\Controllers;

class UserTicket extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    
    public function index()
    {
        return view('main/user/user_ticket');
    }
}
