<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    protected $time;
    protected $session;
    protected $userModel;
    protected $logModel;
    protected $googleAuth;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        $this->logModel = new \App\Models\LogUsageModel();
    }


    public function index()
    {
        return view('main/login');
    }

    public function login()
    {
        if ($this->request->isAJAX()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $data = $this->userModel->where('email', $email)->where('status', 1)->first();

            if ($data) {
                if (password_verify($password, $data['password'])) {
                    $sessionData = [
                        'logged_in' => true,
                        'empId' => $data['empId'],
                        'fullname' => $data['fullname'],
                        'email' => $data['email'],
                        'class' => $data['class'],
                        'status' => $data['status'],
                    ];

                    $this->session->set($sessionData);

                    $response = [
                        'status' => 200,
                        'title' => 'Success',
                        'message' => 'เข้าสู่ระบบสำเร็จ',
                    ];

                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error',
                        'message' => 'รหัสผ่านไม่ถูกต้อง',
                    ];

                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error',
                    'message' => 'ไม่พบผู้ใช้งาน'
                ];

                return $this->response->setJSON($response);
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
