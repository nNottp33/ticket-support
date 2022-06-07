<?php

namespace App\Controllers;

class Ticket extends BaseController
{
    public function index()
    {
        return view('main/admin/ticket');
    }


    public function CATList()
    {
        return view('main/admin/cat_list');
    }
}
