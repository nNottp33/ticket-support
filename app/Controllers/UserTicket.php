<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class UserTicket extends BaseController
{
    protected $session;
    protected $time;
    protected $catModel;
    protected $subCatModel;
    protected $LogUsageModel;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->catModel = new \App\Models\CatagoryModel();
        $this->subCatModel = new \App\Models\SubCatagoryModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        // $this->userModel = new \App\Models\UserModel();
        // $this->ownerGroupModel = new \App\Models\GroupOwnerModel();
        helper('url');
    }

    
    public function index()
    {
        return view('main/user/user_ticket');
    }

    public function getCategories()
    {
        if ($this->request->isAJAX()) {
            $query = $this->catModel->where('status', 1)->findAll();

            if ($query) {
                $data = [
                    'status' => 200,
                    'title' => 'Success!',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $query,
                ];
                return $this->response->setJson(($data));
            } else {
                $data = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถดึงข้อมูลได้',
                ];
                return $this->response->setJson(($data));
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            return $this->response->setJSON($response);
        }
    }
}
