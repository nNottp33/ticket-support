<?php

namespace App\Controllers;

class UserTicket extends BaseController
{
    public function index()
    {
        return view('main/user/user_ticket');
    }
}
