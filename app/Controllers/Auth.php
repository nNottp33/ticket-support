<?php

namespace App\Controllers;

use CodeIgniter\Cookie\Cookie;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    protected $time;
    protected $session;
    protected $userModel;
    protected $LogUsageModel;
    protected $cookie;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        helper('url');
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

            $data = $this->userModel->where('email', $email)->where('status != 4')->first();

            if ($data['status'] == 0) {
                $response = [
                            'status' => 404,
                            'title' => 'Error',
                            'message' => 'ยูสเซอร์ของคุณถูกปิดใช้งาน',
                        ];

                return $this->response->setJSON($response);
            }


            if ($data['status'] == 3) {
                $response = [
                            'status' => 404,
                            'title' => 'Error',
                            'message' => 'ยูสเซอร์ของคุณถูกล็อค',
                        ];

                return $this->response->setJSON($response);
            }


            if ($data['status'] == 1) {
                if (password_verify($password, $data['password'])) {
                    $logData = [
                        'ip' => $this->request->getIPAddress(),
                        'action' => 'logged in',
                        'detail' => 'เข้าสู่ระบบสำเร็จ',
                        'createdAt' => $this->time->getTimestamp(),
                        'userId' => $data['id'],
                    ];

                    $sessionData = [
                        'logged_in' => true,
                        'id' => $data['id'],
                        'empId' => $data['empId'],
                        'fullname' => $data['fullname'],
                        'nickname' => $data['nickname'],
                        'email' => $data['email'],
                        'class' => $data['class'],
                        'status' => $data['status'],
                    ];

                    if ($this->LogUsageModel->insert($logData)) {
                        $this->session->set($sessionData);
                        $this->userModel->update($data['id'], ['lastLogin' => $this->time->getTimestamp()]);

                        if ($data['class'] == 'admin' && $data['lastLogin'] != 0) {
                            $url = base_url('/admin');
                        }

                        if ($data['class'] == 'user' && $data['lastLogin'] != 0) {
                            $url = base_url('/user/home');
                        }

                  
                        if ($data['lastLogin'] == 0) {
                            $url = base_url('/profile');
                        }

                        $response = [
                            'status' => 200,
                            'title' => 'Success',
                            'message' => 'ยินดีต้อนรับเข้าสู่ระบบ',
                            'redirectUrl' => $url,
                        ];

                        return $this->response->setJSON($response);
                    } else {
                        $response = [
                            'status' => 404,
                            'title' => 'Error',
                            'message' => 'บันทึก log ไม่สำเร็จ',
                        ];

                        return $this->response->setJSON($response);
                    }
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

    public function logout()
    {
        if ($this->request->isAJAX()) {
            $this->session->destroy();

            $data = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'logged out',
                'detail' => 'ออกจากระบบสำเร็จ',
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];
        
            if ($this->LogUsageModel->insert($data)) {
                $response = [
                'status' => 200,
                'title' => 'Success',
                'message' => 'ออกจากระบบสำเร็จ'
            ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                'status' => 201,
                'title' => 'Success',
                'message' => 'บันทึก log ไม่สำเร็จ'
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
