<?php

namespace App\Controllers;

use Attribute;
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
    protected $taskDetailModel;
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
        $this->taskDetailModel = new \App\Models\TicketDetailModel();
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
                'attachment' => $imageFile->getClientName() ? $imageFile->getClientName() : '-',
                'createdAt' => $this->time->getTimestamp(),
                'status' => 0,
                'userId' => $this->session->get('id'),
                'catId' => $catId,
                'subCatId' => $subCatId,
            ];

            $resultCatName = $this->catModel->where('id', $catId)->findColumn('nameCatTh');
            $resultSubCatName = $this->subCatModel->where('id', $subCatId)->findColumn('nameSubCat');

        
            $resultOwner = $this->ownerGroupModel->where('groupId', $catId)->findAll();

            for ($i = 0; $i < sizeof($resultOwner); $i++) {
                $ownerId[] = $resultOwner[$i]['id'];
            }
      
            $email = $this->userModel->whereIn('id', $ownerId)->findColumn('email');
            $toMail = [$_ENV['EMAIL_IT_GROUP'], ...$email];
     
            if ($this->LogUsageModel->insert($logData)) {
                if ($this->ticketTaskModel->insert($insertData)) {
                    $last_ticketId= $this->ticketTaskModel->getInsertID();
                    $ticket_no = 'IT0722-' . str_pad($last_ticketId, 5, "0", STR_PAD_LEFT);

                    $updateData = [
                        'ticket_no' => $ticket_no,
                    ];

                    $titleMail = 'send Email create ticket';
                    $subjectMail = 'Ticket ใหม่';
                    $messageEmail = '<div id="app">';
                    $messageEmail .= ' <div>';
                    $messageEmail .= '  <h2><b> Ticket no. ' . $ticket_no . ' </b></h2>';
                    $messageEmail .= ' </div>';
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


                    if ($this->sendEmailGroup($titleMail, $subjectMail, $messageEmail, $toMail, $imageFile->getClientName()) && $this->ticketTaskModel->update($last_ticketId, $updateData)) {
                        if (is_file($imageFile)) {
                            $imageFile->move('./store_files_uploaded');
                        }

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
                                'message' => 'ทำรายการไม่สำเร็จ',
                            ];
                        return $this->response->setJson($response);
                    }
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

    public function getTicketByUser()
    {
        if ($this->request->isAJAX()) {
            $query = $this->ticketTaskModel
            ->select('ticket_task.*, catagories.id as cat_id, catagories.nameCatTh, sub_catagories.id as subCat_id, sub_catagories.nameSubCat, sub_catagories.detail as subCat_detail, sub_catagories.period')
            ->where('ticket_task.userId', $this->session->get('id'))
            ->where('ticket_task.status != 4')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->orderBy('ticket_task.id', 'DESC')->findAll();

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

    public function getTicketDetail()
    {
        if ($this->request->isAJAX()) {
            $ticketId = $this->request->getPost('ticketId');

            $query = $this->ticketTaskModel
            ->select(
                'ticket_task.ticket_no,
                ticket_task.id as task_id,
                ticket_task.topic as task_topic,
                ticket_task.remark as task_remark,
                ticket_task.createdAt as task_create,
                ticket_task.updatedAt as task_update,
                ticket_task.status as task_status,
                ticket_task.userId as task_user,
                ticket_task.attachment as task_attach,
                catagories.nameCatTh as catName,
                sub_catagories.nameSubCat as subCatName,
                sub_catagories.period as periodTime,'
            )
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->where('ticket_task.id', $ticketId)
            ->where('ticket_task.userId', $this->session->get('id'))
            ->findAll();
            
        
            $query[0]['timeline'] = $this->taskDetailModel
            ->select(
                'ticket_detail.cause as detail_cause,
                ticket_detail.solution as detail_solution,
                ticket_detail.remark as detail_remark,
                ticket_detail.attachment as detail_attachment,
                ticket_detail.createdAt as detail_created'
            )
            ->where('ticket_detail.taskId', $ticketId)
            ->orderBy('ticket_detail.id', 'desc')
            ->limit(5)
            ->findAll();


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
            $taskId = $this->request->getPost('taskId');
            $status = $this->request->getPost('status');

            $resultTask = $this->ticketTaskModel->select('ticket_task.ticket_no, ticket_task.topic, ticket_task.createdAt, users.id admin_id, users.email as admin_email')
            ->join('users', 'users.id = ticket_task.ownerAccepted')
            ->where('ticket_task.id', $taskId)
            ->first();


            // mail data
            $titleMail = 'send email close ticket to admin';
            $subjectMail = 'Close Ticket';
            $messageEmail = '<p>';
            $messageEmail .= '<h2> Ticket No.' . $resultTask['ticket_no'] . ' </h2>';
            $messageEmail .= '  คำร้องขอ Ticket ' . $resultTask['topic'] . ' เมื่อวันที่ ' . date('d/m/Y H:i', $resultTask['createdAt']) . ' ได้รับการรับการตรวจสอบและสามารถใช้งานได้ตามปกติแล้ว ';
            $messageEmail .= '</p> ';
           
            $time = $this->time->getTimestamp();

            $updateData = [
                'status' => $status,
                'updatedAt' => $time,
            ];

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'user update ticket status',
                'detail' => 'user ' . $this->session->get('email') . 'update status ticket เป็น ' . $status,
                'createdAt' =>   $time ,
                'userId' => $this->session->get('id'),
            ];

            $admin_email = [$_ENV['EMAIL_IT_GROUP'] , $resultTask['admin_email']];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->sendEmailGroup($titleMail, $subjectMail, $messageEmail, $admin_email, '')) {
                    if ($this->ticketTaskModel->update($taskId, $updateData)) {

                        // remove file when status close (4)
                        // if ($status == 4) {
                        //     $this->ticketTaskModel
                        //     ->select('ticket_task.attachment, ticket_detail.attachment')
                        //     ->join('ticket_detail', 'ticket_task.id = ticket_detail.taskId')
                        //     ->where('ticket_task.id', $taskId)
                        //     ->findAll();
                        // }

                        $response = [
                                'status' => 200,
                                'title' => 'Success',
                                'message' => 'ดำเนินการสำเร็จ แจ้งผู้ใช้เรียบร้อย',
                            ];
                        return $this->response->setJson($response);
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
                            'message' => 'ไม่สามารถส่งเมล์ได้',
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

            return $this->response->setJSON($response);
        }
    }


    public function returnTicket()
    {
        if ($this->request->isAJAX()) {
            $taskId = $this->request->getPost('taskId');
            $remark = $this->request->getPost('ticketDetailReturn');
            $attatchment = $this->request->getFile('fileReturn');

            $time = $this->time->getTimestamp();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'user return ticket',
                'detail' => 'user ' . $this->session->get('email') . 'ตีกลับ ticket',
                'createdAt' =>   $time ,
                'userId' => $this->session->get('id'),
            ];

            $newTaskDetail = [
                'cause' => '-',
                'solution' => '-',
                'remark' =>  $remark,
                'attachment '=> $attatchment->getClientName(),
                'createdAt' => $time,
                'taskId' => $taskId,
            ];


            $resultTask = $this->ticketTaskModel->select('ticket_task.ticket_no, ticket_task.topic, ticket_task.createdAt, users.id admin_id, users.email as admin_email')
            ->join('users', 'users.id = ticket_task.ownerAccepted')
            ->where('ticket_task.id', $taskId)
            ->first();

            $admin_email = [$_ENV['EMAIL_IT_GROUP'] , $resultTask['admin_email']];

            $titleMail = 'send email return ticket to admin';
            $subjectMail = 'Return Ticket';
            $messageEmail = '<p>';
            $messageEmail .= '<h2> Ticket No.' . $resultTask['ticket_no'] . ' </h2>';
            $messageEmail .= 'คำร้องขอ Ticket ' . $resultTask['topic'] . ' เมื่อวันที่ ' . date('d/m/Y H:i', $resultTask['createdAt']) . '  ยังไม่เป็นปกติ' ;
            $messageEmail .= '</p> ';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> หัวข้อ </b></h3>';
            $messageEmail .= '     <p style="padding-left: 20px;"> ' . $resultTask['topic'] . ' </p> ';
            $messageEmail .= ' </div>';
            $messageEmail .= ' <div>';
            $messageEmail .= '  <h3><b> รายละเอียดเพิ่มเติม </b></h3>';
            $messageEmail .= '    <p style="padding-left: 20px;"> ' . $remark . ' </p> ';
            $messageEmail .= ' </div>';

            $updateData = [
                'status' => 6,
                'updatedAt' => $time
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->sendEmailGroup($titleMail, $subjectMail, $messageEmail, $admin_email, $attatchment->getClientName())) {
                    if ($this->ticketTaskModel->update($taskId, $updateData) && $this->taskDetailModel->insert($newTaskDetail)) {
                        if (is_file($attatchment)) {
                            $attatchment->move('./store_files_uploaded');
                        }

                        $response = [
                            'status' => 200,
                            'title' => 'Success!',
                            'message' => 'ดำเนินการสำเร็จ',
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
                            'message' => 'ไม่สามารถส่งเมล์ได้',
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

            return $this->response->setJSON($response);
        }
    }

    public function sendEmailGroup($titleMail, $subjectMail, $messageEmail, $email, $image)
    {
        $this->email->setFrom($_ENV['email.SMTPUser'], $_ENV['EMAIL_NAME']);
        // $this->email->setTo($email);
        $this->email->setTo($_ENV['CI_ENVIRONMENT'] == 'development' ? $_ENV['EMAIL_TEST'] : $email);
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
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
