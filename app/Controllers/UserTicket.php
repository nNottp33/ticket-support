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
    protected $ticketTaskModel;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->catModel = new \App\Models\CatagoryModel();
        $this->subCatModel = new \App\Models\SubCatagoryModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        $this->ticketTaskModel = new \App\Models\TicketTaskModel();
        helper(['form', 'url']);
    }

    
    public function index()
    {
        return view('main/user/user_ticket');
    }


    public function saveTicket()
    {
        if ($this->request->isAJAX()) {
            $topic = $this->request->getPost('ticketTopic');
            $catId = $this->request->getPost('userSelectCategory');
            $subCatId = $this->request->getPost('userSelectSubCategory');
            $detail = $this->request->getPost('ticketDetail');
            $imageFile = $this->request->getFile('file');

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'insert ticket',
                'detail' => "Admin " . $this->session->get('email') . " สร้าง Ticket " . $topic,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            $insertData = [
                'topic' => $topic,
                'remark' => $detail,
                'attachment' => $imageFile->getClientName(),
                'createdAt' => $this->time->getTimestamp(),
                'status' => 0,
                'userId' => $this->session->get('id'),
                'catId' => $catId,
                'subCatId' => $subCatId,
            ];

            $titleMail = 'send Email create ticket';
        
            $messageEmail = '';
            $email= '';
            $id = '';


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->ticketTaskModel->insert($insertData) && $imageFile->move('./store_files_uploaded') && $this->sendEmailGroup($titleMail, $topic, $messageEmail, $email, $id)) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'เพิ่มข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถเพิ่มข้อมูลได้',
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


    public function sendEmailGroup($titleMail, $subjectMail, $messageEmail, $email, $id)
    {
        $this->email->setFrom($_ENV['email.SMTPUser'], $_ENV['EMAIL_NAME']);
        $this->email->setTo($email);
        $this->email->setSubject($subjectMail);
        $this->email->setMessage($messageEmail);
        $logEmail = [
                'receiverId' => $id,
                'title' => $titleMail,
                'subject' => $subjectMail,
                'detail' => $messageEmail,
                'createdAt' => $this->time->getTimestamp(),
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
