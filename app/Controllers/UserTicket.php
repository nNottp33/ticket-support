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
        return view('main/user/user_ticket');
    }

    // echo "<pre>";
    // print_r();
    // die();

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

            $resultCatName = $this->catModel->where('id', $catId)->findColumn('nameCatTh');
            $resultSubCatName = $this->subCatModel->where('id', $subCatId)->findColumn('nameSubCat');

            $titleMail = 'send Email create ticket';
            $messageEmail = '<div id="app">';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> หัวข้อ </b></h3>';
            $messageEmail .= '     <p style="padding-left: 20px;"> ' . $topic . ' </p> ';
            $messageEmail .= ' </div>';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> หมวดเรื่อง </b></h3>';
            $messageEmail .= '    <p style="padding-left: 20px;"> ' . $resultCatName[0] . ' </p> ';
            $messageEmail .= ' </div>';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> หมวดย่อย </b></h3>';
            $messageEmail .= '    <p style="padding-left: 20px;"> ' . $resultSubCatName[0] . ' </p> ';
            $messageEmail .= ' </div>';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> รายละเอียดเพิ่มเติม </b></h3>';
            $messageEmail .= '    <p style="padding-left: 20px;"> ' . $detail . ' </p> ';
            $messageEmail .= ' </div>';
            $messageEmail .= '</div>';

            $resultOwner = $this->ownerGroupModel->where('groupId', $catId)->findAll();

            for ($i = 0; $i < sizeof($resultOwner); $i++) {
                $ownerId[] = $resultOwner[$i]['id'];
            }
      
            $email = $this->userModel->whereIn('id', $ownerId)->findColumn('email');
            $toMail = [$_ENV['EMAIL_IT_GROUP'], ...$email];
     
            if ($this->LogUsageModel->insert($logData)) {
                if ($this->ticketTaskModel->insert($insertData) && $imageFile->move('./store_files_uploaded') && $this->sendEmailGroup($titleMail, $topic, $messageEmail, $toMail, $imageFile->getClientName())) {
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


    public function sendEmailGroup($titleMail, $subjectMail, $messageEmail, $email, $image)
    {
        $this->email->setFrom($_ENV['email.SMTPUser'], $_ENV['EMAIL_NAME']);
        $this->email->setTo($email);
        $this->email->setSubject($subjectMail);
        $this->email->setMessage($messageEmail);
        $this->email->attach(FCPATH . "store_files_uploaded/". $image);

        for ($i = 0; $i < sizeOf($email); $i++) {
            $logEmail[$i] = [
                'receiver' => $email[$i],
                'title' => $titleMail,
                'subject' => $subjectMail,
                'detail' => strip_tags($messageEmail),
                'createdAt' => $this->time->getTimestamp(),
                'status' => 1
            ];
        }
        
        if ($this->logEmailModel->insertBatch($logEmail)) {
            if ($this->email->send()) {
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getTicketByUser()
    {
        if ($this->request->isAJAX()) {
            $query = $this->ticketTaskModel->select('ticket_task.*, catagories.id as cat_id, catagories.nameCatTh, sub_catagories.id as subCat_id, sub_catagories.nameSubCat, sub_catagories.detail as subCat_detail, sub_catagories.period')->where('ticket_task.userId', $this->session->get('id'))->join('catagories', 'catagories.id = ticket_task.catId')->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')->orderBy('ticket_task.id', 'DESC')->findAll();

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

    public function getTicketDetail()
    {
        if ($this->request->isAJAX()) {
            $ticketId = $this->request->getPost('ticketId');
            $query = $this->ticketTaskModel->select('ticket_task.*, catagories.nameCatTh, sub_catagories.nameSubCat, sub_catagories.detail as subCat_detail, sub_catagories.period')->where('ticket_task.id', $ticketId)->where('ticket_task.userId', $this->session->get('id'))->join('catagories', 'catagories.id = ticket_task.catId')->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')->findAll();


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
}
