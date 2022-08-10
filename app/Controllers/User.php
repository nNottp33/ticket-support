<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class User extends BaseController
{
    protected $time;
    protected $session;
    protected $userModel;
    protected $LogUsageModel;
    protected $departmentModel;
    protected $positionModel;
    protected $email;
    protected $logEmailModel;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        $this->departmentModel = new \App\Models\DepartmentModel();
        $this->positionModel = new \App\Models\PositionModel();
        $this->logEmailModel = new \App\Models\LogEmailModel();
        helper('url');
    }

    public function index()
    {
        return view('main/admin/users');
    }

    public function userList()
    {
        if ($this->request->isAJAX()) {
            $query = $this->userModel->select('users.class, users.departmentId, users.email, users.empId, users.fullname, users.id, users.lastLogin, users.nickname, users.positionId, users.prefix, users.status, users.tel, department.nameDepart as department, position.namePosition as position')->join('position', 'position.id = users.positionId')->join('department', 'department.id = users.departmentId')->where('status != 4')->where('users.id !=', $this->session->get('id'))->orderBy('id', 'DESC')->limit(100)->get()->getResult();

            if ($query) {
                $response = [
                    'status' => 200,
                    'title' => 'Success!',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $query,
                ];
                return $this->response->setJson($response);
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถดึงข้อมูลได้',
                ];
                return $this->response->setJson($response);
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

    public function getUserByStatus()
    {
        if ($this->request->isAJAX()) {
            $status =  $this->request->getGet('status');


            $query = $this->userModel->select('users.class, users.departmentId, users.email, users.empId, users.fullname, users.id, users.lastLogin, users.nickname, users.positionId, users.prefix, users.status, users.tel, department.nameDepart as department, position.namePosition as position')->join('position', 'position.id = users.positionId')->join('department', 'department.id = users.departmentId')->where('status != 4')->where('status', $status)->where('users.id !=', $this->session->get('id'))->orderBy('id', 'DESC')->limit(100)->get()->getResult();


            if ($query) {
                $response = [
                    'status' => 200,
                    'title' => 'Success!',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $query,
                ];
                return $this->response->setJson($response);
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถดึงข้อมูลได้',
                ];
                return $this->response->setJson($response);
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


    public function countUsers()
    {
        if ($this->request->isAJAX()) {
            $query['total'] = $this->userModel->select('count(users.id) as total')->where('id !=', $this->session->get('id'))->where('status != 4')->get()->getResult();

            $query['on'] = $this->userModel->select('count(users.id) as online')->where('id !=', $this->session->get('id'))->where('status', 1)->get()->getResult();

            $query['off'] = $this->userModel->select('count(users.id) as suspended')->where('id !=', $this->session->get('id'))->where('status = 0')->get()->getResult();

            $query['lock'] = $this->userModel->select('count(users.id) as locked')->where('id !=', $this->session->get('id'))->where('status = 3')->get()->getResult();

            $query['terminate'] = $this->userModel->select('count(users.id) as terminate')->where('id !=', $this->session->get('id'))->where('status = 4')->get()->getResult();

            if ($query) {
                $response = [
                    'status' => 200,
                    'title' => 'Success!',
                    'message' => 'ดึงข้อมูลสำเร็จ',
                    'data' => $query,
                ];
                return $this->response->setJson($response);
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถดึงข้อมูลได้',
                ];
                return $this->response->setJson($response);
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

    public function getUserById()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $query = $this->userModel->select('users.id, users.status, users.class, users.empId, users.prefix, users.fullname, users.nickname, users.email, users.tel, department.id as depId, department.nameDepart as department, position.id as posId, position.namePosition as position')->join('position', 'position.id = users.positionId')->join('department', 'department.id = users.departmentId')->where('status != 4')->where('users.id', $id)->get()->getResult();


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

    public function getDepartmentList()
    {
        if ($this->request->isAJAX()) {
            $query = $this->departmentModel->findAll();

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

    public function getPositionList()
    {
        if ($this->request->isAJAX()) {
            $depId = $this->request->getPost('depId');
            $query = $this->positionModel->where('departmentId', $depId)->findAll();

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

    public function insertUser()
    {
        if ($this->request->isAJAX()) {
            $email = $this->request->getPost('email');
            $empId = $this->request->getPost('empId');
            $fname = $this->request->getPost('fullname');

            $checkUsername = $this->userModel->where('status !=4')->where('email', $email)->orWHere('empId', $empId)->orWhere('fullname', $fname)->first();

            $saveData = [
                'empId' =>$empId,
                'prefix' => $this->request->getPost('prefix'),
                'fullname' => $fname,
                'nickname' => $this->request->getPost('nickname'),
                'email' => $email,
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'tel' => $this->request->getPost('tel'),
                'class' => $this->request->getPost('class'),
                'createdAt' => $this->time->getTimestamp(),
                'positionId' => $this->request->getPost('positionId'),
                'departmentId' => $this->request->getPost('departmentId'),
            ];

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'inserted new user',
                'detail' => json_encode($saveData),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($checkUsername) {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'มีข้อมูลนี้อยู่ในระบบแล้ว',
                ];
                return $this->response->setJson(($response));
            }


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->userModel->insert($saveData)) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'บันทึกข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถบันทึกข้อมูลได้',
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
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            $this->response->setJson($response);
        }
    }

    public function updateStatusUser()
    {
        if ($this->request->isAJAX()) {
            $status = $this->request->getPost('status');
            $id = $this->request->getPost('id');
            $email = $this->request->getPost('email');

            $textStatus = $status == 1 ? 'เปิด' : 'ปิด';

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'update status user',
                'detail' => "Admin " . $this->session->get('email') . $textStatus . "ใช้งานผู้ใช้ " . $email,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->userModel->where('status != 4')->update($id, ['status' => $status])) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => $textStatus . 'ใช้งานสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถ'. $textStatus .'ใช้งานได้',
                    ];
                    return $this->response->setJson($response);
                }
            } else {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถบันทึก log ได้',
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

    public function deleteUser()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $query = $this->userModel->where('id', $id)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'deleted user',
                'detail' => "Admin " . $this->session->get('email') . " ลบข้อมูลผู้ใช้งาน " . $query['email'],
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->userModel->update($id, ['status' => 4])) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'ลบข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถลบข้อมูลได้',
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
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            $this->response->setJson($response);
        }
    }


    public function updateUser()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $empId = $this->request->getPost('empId');
            $prefix = $this->request->getPost('prefix');
            $fullname = $this->request->getPost('fullname');
            $nickname = $this->request->getPost('nickname');
            $email = $this->request->getPost('email');
            $tel = $this->request->getPost('tel');
            $class = $this->request->getPost('class');
            $status = $this->request->getPost('status');
            $departmentId = $this->request->getPost('departmentId');
            $positionId = $this->request->getPost('positionId');


            $updateData = [
                'id' => $id,
                'empId' => $empId,
                'prefix' => $prefix,
                'fullname'=>  $fullname,
                'nickname' => $nickname,
                'email' =>  $email,
                'tel' => $tel,
                'class' => $class,
                'status' =>  $status ,
                'departmentId' => $departmentId,
                'positionId' => $positionId,
              ];

            $beforeData = $this->userModel->where('id', $id)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'updated user data',
                'detail' => 'BEFORE => ' . json_encode($beforeData) .'AFTER =>' . json_encode($updateData),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->userModel->update($id, $updateData)) {
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
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            $this->response->setJson($response);
        }
    }


    public function resetPassword()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $password = $this->generateRandomString();

            $userData = $this->userModel->where('id', $id)->first();

            $updateData = [
                'id' => $id,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'reset user password',
                'detail' => 'Admin ' . $this->session->get('email') . ' รีเซ็ตรหัสผ่านของผู้ใช้ ' . json_encode($userData['email']),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if (!isset($userData['email'])) {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่พบ Email',
                    ];
                    return $this->response->setJson($response);
                }

                $messageEmail = "<div style='color: #ec6826;font-size: 41px;'>รหัสผ่านใหม่</div> <br /> <div style='font-size: 14px; padding-top: 25px;'>รหัสผ่านใหม่ของคุณ : <span style='font-size: 14px;font-weight: bold;color: #2a2a2a;'>" . $password . " </span></div> <br /> <div style='
                                padding-top: 25px;;
                                font-size: 14px;
                                font-weight: bold;
                                color: red;
                            '> **กรุณาเปลี่ยนรหัสผ่านอีกครั้งเพื่อความปลอดภัย </div>";

                $subjectMail = 'รีเซ็ตรหัสผ่านใหม่';

                $logEmail = [
                    'receiverId' => $userData['id'],
                    'title' => 'send email reset password',
                    'subject' => $subjectMail,
                    'detail' => strip_tags($messageEmail),
                    'createdAt' => $this->time->getTimestamp(),
                ];

                if ($this->sendEmailAPI($subjectMail, $messageEmail, $userData['email'], '')) {
                    if ($this->logEmailModel->insert($logEmail)) {
                        if ($this->userModel->update($id, $updateData)) {
                            $response = [
                                'status' => 200,
                                'title' => 'Success!',
                                'message' => 'รีเซ็ตรหัสผ่านสำเร็จ',
                            ];
                            return $this->response->setJson($response);
                        } else {
                            $response = [
                                'status' => 404,
                                'title' => 'Error!',
                                'message' => 'ไม่สามารถรีเซ็ตรหัสผ่านได้',
                            ];
                            return $this->response->setJson($response);
                        }
                    } else {
                        $response = [
                                'status' => 404,
                                'title' => 'Error!',
                                'message' => 'ไม่สามารถบันทึก log email ได้',
                            ];
                        return $this->response->setJson($response);
                    }
                } else {
                    $response = [
                    'status' => 404,
                    'title' => 'ไม่สำเร็จ',
                    'message' => 'ไม่สามารถส่งอีเมล์ได้'
                ];

                    return $this->response->setJSON($response);
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
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            $this->response->setJson($response);
        }
    }
}
