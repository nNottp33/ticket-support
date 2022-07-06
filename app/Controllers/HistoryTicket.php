<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class HistoryTicket extends BaseController
{
    protected $session;
    protected $time;
    protected $catModel;
    protected $subCatModel;
    protected $LogUsageModel;
    protected $ticketTaskModel;
    protected $userModel;
    protected $ownerGroupModel;
    protected $logEmailModel;
    protected $email;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->email = \Config\Services::email();
        $this->catModel = new \App\Models\CatagoryModel();
        $this->subCatModel = new \App\Models\SubCatagoryModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        $this->ticketTaskModel = new \App\Models\TicketTaskModel();
        $this->userModel = new \App\Models\UserModel();
        $this->ownerGroupModel = new \App\Models\GroupOwnerModel();
        $this->logEmailModel = new \App\Models\LogEmailModel();
        helper(['form', 'url']);
    }

    
    public function index()
    {
        return view('main/user/history_ticket');
    }
}
