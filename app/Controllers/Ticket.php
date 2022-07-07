<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Ticket extends BaseController
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
        return view('main/admin/ticket');
    }

    public function getTicketAdmin()
    {
        if ($this->request->isAJAX()) {
            $query = $this->ticketTaskModel
            ->select('ticket_task.id as taskId, ticket_task.topic as task_topic, ticket_task.createdAt as task_created, ticket_task.updatedAt as task_updated, ticket_task.status as task_status, catagories.nameCatTh, sub_catagories.nameSubCat, users.email as user_email')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->join('group_owner', 'group_owner.groupId = ticket_task.catId')
            ->join('users', 'users.id = ticket_task.userId')
            ->where('users.status', 1)
            ->where('ticket_task.status !=', 3)
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->orWhere('ticket_task.ownerAccepted', 0)
            ->orderBy('ticket_task.id', 'desc')
            ->limit(100)
            ->findAll();

            // echo "<pre>";
            // print_r($query);
            // die();

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

    public function getUserByEmail()
    {
        if ($this->request->isAJAX()) {
            $query = $this->userModel
            ->join('position', 'position.id = users.positionId')
            ->join('department', 'department.id = users.departmentId')
            ->where('email', $this->request->getPost('email'))
            ->where('status', 1)->first();

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

    public function countTicket()
    {
        if ($this->request->isAJAX()) {
            $query['total'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status != 4')
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->get()
            ->getRow()->total;

            $query['wait'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status = 0')
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            $query['pending'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status = 1')
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            $query['complete'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status = 2')
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            $query['close'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status = 4')
            ->where('group_owner.ownerId', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            // echo "<pre>";
            // print_r($query);
            // die();

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

    public function updateTicket()
    {
        if ($this->request->isAJAX()) {
            $task_id =  $this->request->getPost('id');
            $status =  $this->request->getPost('status');
            
            $resultMail = $this->ticketTaskModel->select('users.id as user_id, users.email as user_email, sub_catagories.period as subCat_period')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->join('users', 'users.id = ticket_task.userId')
            ->where('ticket_task.id', $task_id)
            ->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'admin update ticket status',
                'detail' => 'แอดมิน ' . $this->session->get('email') . 'update status ticket เป็น ' . $status,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            switch ($status) {
                case 1 || '1':
                    $titleMail = 'send email admin approved ticket';
                    $subjectMail = 'แอดมินตอบรับ Ticket';
                    $messageEmail = '<p>';
                    $messageEmail .= '   <h3> Ticket ของคุณได้รับการตอบรับเรียบร้อยแล้ว </h3>' ;
                    $messageEmail .= '     ขณะนี้กำลังดำเนินการใช้เวลาประมาณ ' . $resultMail['subCat_period'] . ' ชั่วโมง';
                    $messageEmail .= '</p> ';

                    $updateData = [
                        'status' => $status,
                        'ownerAccepted' => $this->session->get('id'),
                        'updatedAt' => $this->time->getTimestamp(),
                    ];

                    if ($this->LogUsageModel->insert($logData)) {
                        if ($this->ticketTaskModel->update($task_id, $updateData)) {
                            if ($this->sendEmailUser($titleMail, $subjectMail, $messageEmail, $resultMail['user_email'], $resultMail['user_id'])) {
                                $response = [
                                'status' => 200,
                                'title' => 'Success',
                                'message' => 'ดำเนินสำเร็จ ทำการแจ้งผู้ใช้เรียบร้อย',
                            ];
                                return $this->response->setJson($response);
                            } else {
                                $response = [
                                'status' => 404,
                                'title' => 'Error!',
                                'message' => 'ไม่สามารถส่งเมล์ได้',
                            ];
                                return $this->response->setJson($response);
                            }
                        } else {
                            $response = [
                            'status' => 404,
                            'title' => 'Error!',
                            'message' => 'ไม่สามารถอัพเดทข้อมูลได้',
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
                    break;

                case 2 || '2':

                break;

                case 3 || '3':
                    
                break;

                case 4 || '4':
                    
                break;

                default:

                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ไม่สามารถอัพเดทข้อมูลได้',
                ];
                return $this->response->setJson($response);

                break;
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

    public function sendEmailUser($titleMail, $subjectMail, $messageEmail, $email, $id)
    {
        $this->email->setFrom($_ENV['email.SMTPUser'], $_ENV['EMAIL_NAME']);
        $this->email->setTo($email);
        $this->email->setSubject($subjectMail);
        $this->email->setMessage($messageEmail);

        $logEmail = [
            'receiverId' => $id,
            'title' => $titleMail,
            'subject' => $subjectMail,
            'detail' => strip_tags($messageEmail),
            'createdAt' => $this->time->getTimestamp(),
            'status' => 1
        ];

        if ($this->email->send()) {
            if ($this->logEmailModel->insert($logEmail)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
