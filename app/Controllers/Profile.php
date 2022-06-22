<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Profile extends BaseController
{
    protected $time;
    protected $session;
    protected $userModel;
    protected $LogUsageModel;
    protected $otpModel;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->email = \Config\Services::email();
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        $this->otpModel = new \App\Models\OtpModel();
        helper('url');
    }

    public function index()
    {
        return view('main/profile');
    }

    public function sendEmailCode()
    {
        if ($this->request->isAJAX()) {
            $code = rand(0, 999999);
            $ref = $this->generateRandomString();
          
            $messageEmail = "<h2> รหัสยืนยันการเปลี่ยนรหัสผ่านของคุณคือ </h2> <br/>
                        [ REF Code ]: " . $ref . "<br/>
                        OTP: " . $code;
            $otp = [
                'userId' => $this->session->get('id'),
                'ref' => $ref,
                'code' => $code,
                'createdAt' => $this->time->getTimestamp(),
            ];
    
           
            $this->email->setFrom($_ENV['email.SMTPUser'], $_ENV['EMAIL_NAME']);
            $this->email->setTo($this->session->get('email'));
            $this->email->setSubject('รหัสยืนยันการเปลี่ยนรหัสผ่าน');
            $this->email->setMessage($messageEmail);
            if ($this->email->send()) {
                if ($this->otpModel->insert($otp)) {
                    $response = [
                                    'status' => 200,
                                    'title' => 'สำเร็จ',
                                    'message' => 'ส่งอีเมล์ยืนยันสำเร็จ',
                                    'ref' => $ref,
                                ];

                    return $this->response->setJSON($response);
                } else {
                    $response = [
                                    'status' => 404,
                                    'title' => 'ไม่สำเร็จ',
                                    'message' => 'ไม่สามารถบันทึกข้อมูล OTP ได้'
                                ];

                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'ไม่สำเร็จ',
                    'message' => 'ไม่สามารถส่งอีเมล์ยืนยันได้'
                ];

                return $this->response->setJSON($response);
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Server Internal Error',
            ];
            return $this->response->setJSON($response);
        }
    }


    public function changePassword()
    {
        if ($this->request->isAJAX()) {
            $ref = $this->request->getPost('ref');
            $otp = $this->request->getPost('otp');
            $newPass = $this->request->getPost('newPass');
    
            $otpData = $this->otpModel->where('ref', $ref)->where('status', 0)->first();

            if ($otpData['code'] == $otp && $this->session->get('id') == $otpData['userId']) {
                $logData = [
                    'ip' => $this->request->getIPAddress(),
                    'action' => 'change password',
                    'detail' => [
                        'message' =>
                            'ยูสเซอร์: ' . $this->session->get('username') . ' ได้มีการเปลี่ยนรหัสผ่านใหม่',
                    ],
                    'userId' => $this->session->get('id'),
                    'createdAt' => $this->time->getTimestamp()
                ];

                if ($this->LogUsageModel->insert($logData)) {
                    $newPassword = password_hash($newPass, PASSWORD_DEFAULT);
                    if ($this->userModel->update($this->session->get('id'), ['password' => $newPassword]) && $this->otpModel->update($otpData['id'], ['status' => 1])) {
                        $this->session->destroy();
                        $response = [
                            'status' => 200,
                            'title' => 'สำเร็จ',
                            'message' => 'เปลี่ยนรหัสผ่านสำเร็จ',
                        ];
                        return $this->response->setJSON($response) ;
                    } else {
                        $response = [
                        'status' => 404,
                        'title' => 'ไม่สำเร็จ',
                        'message' => 'ไม่สามารถเปลี่ยนรหัสผ่านได้',
                    ];
                        return $this->response->setJSON($response);
                    }
                } else {
                    $response = [
                    'status' => 404,
                    'title' => 'ไม่สำเร็จ',
                    'message' => 'บันทึกข้อมูล log ไม่สำเร็จ',
                ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'ไม่สำเร็จ',
                    'message' => 'otp ไม่ถูกต้อง',
                ];
                return $this->response->setJSON($response);
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Server Internal Error',
            ];
            return $this->response->setJSON($response);
        }
    }

    public function getProfile()
    {
        if ($this->request->isAJAX()) {
            $profileData = $this->userModel->where('id', $this->session->get('id'))->where('status', 1)->first();

            if ($profileData) {
                $response = [
                    'status' => 200,
                    'title' => 'Success!',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $profileData,
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถดึงข้อมูลได้',
                    'data' => 0
                ];
                return $this->response->setJSON($response);
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Server Internal Error',
            ];
            return $this->response->setJSON($response);
        }
    }


    public function updateProfile()
    {
        if ($this->request->isAJAX()) {
            $updateData = [
                'fullname' => $this->request->getPost('fullname'),
                'nickname' => $this->request->getPost('nickname'),
                'tel' => $this->request->getPost('tel'),
            ];

            $beforeData = $this->userModel->where('id', $this->session->get('id'))->where('status', 1)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'updated profile',
                'detail' => 'BEFORE => ' . json_encode($beforeData) .'AFTER =>' . json_encode($updateData),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->userModel->update($this->session->get('id'), $updateData)) {
                    $this->session->remove(['fullname', ['nickname']]);
                    $this->session->set($updateData);

                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'แก้ไขข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถแก้ไขข้อมูลได้',
                    ];
                    return $this->response->setJson($response);
                }
            } else {
                $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถบันทึก log ได้',
                    ];
                return $this->response->setJson($response);
            }
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error!',
                'message' => 'Server Internal Error',
            ];
            return $this->response->setJSON($response);
        }
    }
}
