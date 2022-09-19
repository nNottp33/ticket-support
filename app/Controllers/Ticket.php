<?php

namespace App\Controllers;

use CodeIgniter\BaseModel;
use CodeIgniter\Entity\Cast\BaseCast;
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
    protected $taskDetailModel;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
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
        return view('main/admin/ticket');
    }

    public function getTicketAdmin()
    {
        if ($this->request->isAJAX()) {
            $query = $this->ticketTaskModel
            ->distinct()
            ->select('ticket_task.id as taskId, ticket_task.ticket_no, ticket_task.topic as task_topic, ticket_task.createdAt as task_created, ticket_task.updatedAt as task_updated, ticket_task.status as task_status, catagories.nameCatTh, sub_catagories.nameSubCat, users.email as user_email')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->join('group_owner', 'group_owner.groupId = ticket_task.catId')
            ->join('users', 'users.id = ticket_task.userId')
            ->where('users.status', 1)
            ->where('ticket_task.status != 3')
            ->whereIn('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->orWhere('ticket_task.ownerAccepted', 0)
            ->orderBy('ticket_task.createdAt', 'DESC')
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

    public function getTicketAdminByStatus()
    {
        if ($this->request->isAJAX()) {
            if ($this->request->getGet('status')[0] == 0) {
                $query = $this->ticketTaskModel
                ->distinct()
                ->select('ticket_task.id as taskId, ticket_task.ticket_no, ticket_task.topic as task_topic, ticket_task.createdAt as task_created, ticket_task.updatedAt as task_updated, ticket_task.status as task_status, catagories.nameCatTh, sub_catagories.nameSubCat, users.email as user_email')
                ->join('catagories', 'catagories.id = ticket_task.catId')
                ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
                ->join('group_owner', 'group_owner.groupId = ticket_task.catId')
                ->join('users', 'users.id = ticket_task.userId')
                ->where('users.status', 1)
                ->whereIn('ticket_task.status', $this->request->getGet('status'))
                ->whereIn('group_owner.ownerId', $this->session->get('id'))
                ->where('ticket_task.ownerAccepted', $this->session->get('id'))
                ->orWhere('ticket_task.ownerAccepted', 0)
                ->orderBy('ticket_task.createdAt', 'DESC')
                ->limit(100)
                ->findAll();
            } else {
                $query = $this->ticketTaskModel
                ->distinct()
                ->select('ticket_task.id as taskId, ticket_task.ticket_no, ticket_task.topic as task_topic, ticket_task.createdAt as task_created, ticket_task.updatedAt as task_updated, ticket_task.status as task_status, catagories.nameCatTh, sub_catagories.nameSubCat, users.email as user_email')
                ->join('catagories', 'catagories.id = ticket_task.catId')
                ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
                ->join('group_owner', 'group_owner.groupId = ticket_task.catId')
                ->join('users', 'users.id = ticket_task.userId')
                ->where('users.status', 1)
                ->whereIn('ticket_task.status', $this->request->getGet('status'))
                ->whereIn('group_owner.ownerId', $this->session->get('id'))
                ->where('ticket_task.ownerAccepted', $this->session->get('id'))
                ->orderBy('ticket_task.createdAt', 'DESC')
                ->limit(100)
                ->findAll();
            }

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
            ->where('ticket_task.status != 3')
            ->whereIn('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->orWhere('ticket_task.ownerAccepted', 0)
            ->get()
            ->getRow()->total;


            $query['wait'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            // ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->whereIn('ticket_task.status', [0,5,6])
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->orWhere('ticket_task.ownerAccepted', 0)
            ->get()
            ->getRow()->total;


            $query['pending'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            // ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status', 1)
            // ->where('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            $query['complete'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            // ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status', 2)
            // ->where('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
            ->get()
            ->getRow()->total;


            $query['close'] = $this->ticketTaskModel
            ->select('count(ticket_task.id) as total')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            // ->join('group_owner', 'group_owner.groupId = catagories.id')
            ->where('ticket_task.status = 4')
            // ->where('group_owner.ownerId', $this->session->get('id'))
            ->where('ticket_task.ownerAccepted', $this->session->get('id'))
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

            $resultMail = $this->ticketTaskModel
            ->select('ticket_task.topic, ticket_task.ticket_no, users.id as user_id, users.email as user_email, sub_catagories.period as subCat_period')
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
                // accepted
                case 1:

                    $current_day = date('D', $this->time->getTimestamp());


                    if ($current_day == 'Fri') {
                        $resultMail['subCat_period'] = 2880;
                    }

                    if ($resultMail['subCat_period'] >= 60 && $resultMail['subCat_period'] <= 1440) {
                        $timeUnit = 'ชั่วโมง';
                        $dayTime = $resultMail['subCat_period'] / 60;
                    } elseif ($resultMail['subCat_period'] < 60) {
                        $timeUnit = 'นาที';
                        $dayTime = $resultMail['subCat_period'];
                    } else {
                        $timeUnit = 'วัน';
                        $dayTime = ($resultMail['subCat_period'] / 60) / 24 ;
                    }


                    if ($this->request->getPost('action') == 'replyReturn') {
                        $titleMail = 'send email admin approved return ticket';
                        $subjectMail = 'แอดมินตอบรับการตีกลับ Ticket' . $resultMail['topic'];
                        $messageEmail = '<p>';
                        $messageEmail .= '<h3> Ticket no. ' . $resultMail['ticket_no'] . ' ของคุณได้รับการตอบรับเรียบร้อยแล้ว </h3>' ;
                        $messageEmail .= 'Ticket ' . $resultMail['topic']. ' ขณะนี้แอดมินกำลังตรวจสอบปัญหาอีกครั้ง ใช้เวลาดำเนินการประมาณ ' . $dayTime . ' ' .$timeUnit;
                        $messageEmail .= '</p> ';
                        $messageEmail .= '<div> ';
                        $messageEmail .= '<label> Ticket support system :</label>';
                        $messageEmail .= '<a href="'. base_url('user/home')  .'"> ' . base_url('user/home') . '  </a>';
                        $messageEmail .= '</div> ';
                    } else {
                        $titleMail = 'send email admin approved ticket';
                        $subjectMail = 'แอดมินตอบรับ Ticket' . $resultMail['topic'];
                        $messageEmail = '<p>';
                        $messageEmail .= '<h3> Ticket no. ' .  $resultMail['ticket_no'] . ' ของคุณได้รับการตอบรับเรียบร้อยแล้ว </h3>' ;
                        $messageEmail .= 'Ticket ' . $resultMail['topic']. ' ขณะนี้กำลังดำเนินการใช้เวลาประมาณ ' . $dayTime . ' ' .$timeUnit;
                        $messageEmail .= '</p> ';
                        $messageEmail .= '<div> ';
                        $messageEmail .= '<label> Ticket support system :</label>';
                        $messageEmail .= ' <a href="'. base_url('user/home')  .'"> ' . base_url('user/home') . ' </a>';
                        $messageEmail .= '</div>';
                    }

                    $updateData = [
                        'status' => $status,
                        'ownerAccepted' => $this->session->get('id'),
                        'updatedAt' => $this->time->getTimestamp(),
                    ];

                    if ($this->LogUsageModel->insert($logData)) {
                        if ($this->sendEmailUser($titleMail, $subjectMail, $messageEmail, $resultMail['user_email'], '')) {
                            if ($this->ticketTaskModel->update($task_id, $updateData)) {
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

                    break;

                    // success
                case 2:

                    $cause = $this->request->getPost('cause');
                    $solution = $this->request->getPost('solution');
                    $remark = $this->request->getPost('remark') ? $this->request->getPost('remark') : '-';
                    $attachment = $this->request->getFile('previewImgTask');

                    // get present task
                    $getTask = $this->ticketTaskModel->where('id', $task_id)->first();

                    // mail data
                    $titleMail = 'send email success ticket to user';
                    $subjectMail = 'Ticket ' . $getTask['topic'] . ' เสร็จสิ้น';
                    $messageEmail = '<p>';
                    $messageEmail .= '<h2> Ticket No.' . $getTask['ticket_no'] . ' </h2>';
                    $messageEmail .= ' คำร้องขอ Ticket ' . $getTask['topic'] . ' เมื่อวันที่ ' . date('d/m/Y H:i', $getTask['createdAt']) . ' ได้รับการแก้ไขแล้ว กรุณาตรวจสอบการใช้งาน และยืนยันความถูกต้อง' ;
                    $messageEmail .= '</p> ';
                    $messageEmail .= ' <div>';
                    $messageEmail .= '  <h3><b> สาเหตุ </b></h3>';
                    $messageEmail .= '     <p style="padding-left: 20px;"> ' .  $cause . ' </p> ';
                    $messageEmail .= ' </div>';
                    $messageEmail .= ' <div>';
                    $messageEmail .= '  <h3><b> วิธีแก้ปัญหา </b></h3>';
                    $messageEmail .= '    <p style="padding-left: 20px;"> ' . $solution . ' </p> ';
                    $messageEmail .= ' </div>';
                    $messageEmail .= ' <div>';
                    $messageEmail .= '  <h3><b> รายละเอียดเพิ่มเติม </b></h3>';
                    $messageEmail .= '    <p style="padding-left: 20px; word-wrap: break-word; "> ' . $remark . ' </p> ';
                    $messageEmail .= ' </div>';
                    $messageEmail .= '<div> ';
                    $messageEmail .= '<label> Ticket support system :</label>';
                    $messageEmail .= ' <a href="'. base_url('user/home')  .'"> ' . base_url('user/home') . '  </a>';
                    $messageEmail .= '</div> ';



                    $time = $this->time->getTimestamp();

                    $updateData = [
                        'status' => $status,
                        'updatedAt' => $time,
                    ];

                    $saveData = [
                        'cause' => $cause,
                        'solution' => $solution,
                        'remark' => $remark,
                        'attachment' => $attachment->getClientName() ? $attachment->getClientName() : '-',
                        'updatedBy' => $this->session->get('id'),
                        'createdAt' => $time,
                        'updatedAt' => $time,
                        'taskId' => $task_id
                    ];

                    if ($this->LogUsageModel->insert($logData)) {
                        if (is_file($attachment)) {
                            $attachment->move('./public/store_files_uploaded');
                        }
                        if ($this->sendEmailUser($titleMail, $subjectMail, $messageEmail, $resultMail['user_email'], $attachment->getClientName())) {
                            if ($this->ticketTaskModel->update($task_id, $updateData) && $this->taskDetailModel->insert($saveData)) {
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

                    break;

                    // reject
                case 3:

                    $reject = $this->request->getPost('action');
                    $titleMail = 'send email ' .  $reject . ' ticket';
                    $subjectMail = 'Ticket ' . $resultMail['topic'] . ' ไม่ถูกต้อง';

                    if ($reject  == 'duplicate') {
                        $messageEmail = '<p>';
                        $messageEmail .= '<h2> Ticket No.' . $resultMail['ticket_no'] . ' </h2>';
                        $messageEmail .= '  Ticket มีการดำเนินการเรียบร้อยแล้ว กรุณาตรวจสอบความถูกต้องใหม่อีกครั้ง' ;
                        $messageEmail .= '</p> ';
                        $messageEmail .= '<div> ';
                        $messageEmail .= '<label> Ticket support system :</label>';
                        $messageEmail .= ' <a href="'. base_url('user/home')  .'"> ' . base_url('user/home') . '  </a>';
                        $messageEmail .= '</div> ';

                        $updateData = [
                            'status' => $status,
                            'ownerAccepted' => $this->session->get('id'),
                            'updatedAt' => $this->time->getTimestamp(),
                        ];

                        if ($this->LogUsageModel->insert($logData)) {
                            if ($this->sendEmailUser($titleMail, $subjectMail, $messageEmail, $resultMail['user_email'], '')) {
                                if ($this->ticketTaskModel->update($task_id, $updateData)) {
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
                    }

                    break;

                    // case 4:
                    // break;

                    // case 5:
                    // break;

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

    public function getTicketOwner()
    {
        if ($this->request->isAJAX()) {
            $query = $this->ownerGroupModel
            ->distinct()
            ->select('users.*')
            ->join('users', 'users.id = group_owner.ownerId')
            ->join('catagories', 'catagories.id = group_owner.groupId')
            ->where('users.class', 'admin')
            ->where('users.status', 1)
            ->where('users.id !=', $this->session->get('id'))
            ->where('catagories.id', $this->request->getGet('groupId'))
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


    public function changeTicket()
    {
        if ($this->request->isAJAX()) {
            $task_id = $this->request->getPost('taskId');
            $catId = $this->request->getPost('catId');
            $subCatId = $this->request->getPost('subCatId');
            $ownerAccepted = $this->request->getPost('ownerId');


            $result_ticket = $this->userModel
            ->select('catagories.nameCatTh, sub_catagories.nameSubCat, ticket_task.ticket_no, ticket_task.topic, ticket_task.remark, ticket_task.attachment, users.email')
            ->join('ticket_task', 'ticket_task.userId = users.id')
            ->join('catagories', 'ticket_task.catId = catagories.id')
            ->join('sub_catagories', 'ticket_task.subCatId = sub_catagories.id')
            ->where('ticket_task.id', $task_id)
            ->first();

            $result_owner_email = $this->userModel->select('users.email')
            ->where('users.id', $ownerAccepted)
            ->first();

            $owner_email = [$_ENV['EMAIL_IT_GROUP'], ...$result_owner_email];

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'admin reject ticket',
                'detail' => 'แอดมิน ' . $this->session->get('email') . ' reject ticket',
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            $titleMail = 'send email wrong ticket';

            // email to user
            $subjectMail_user = 'Ticket ' . $result_ticket['topic'] .  ' ไม่ถูกต้อง';
            $messageEmail_user = '<p>';
            $messageEmail_user .= '   <h2> Ticket no. ' . $result_ticket['ticket_no'] . ' </h2>' ;
            $messageEmail_user .= '  Ticket ของคุณผิดหมวดหมู่ แอดมิน ' . $this->session->get('email') . ' ได้ทำการแก้ไขและส่งคำขอใหม่ไปยังแอดมินคนใหม่เรียบร้อยแล้ว';
            $messageEmail_user .= '</p> ';
            $messageEmail_user .= '<div> ';
            $messageEmail_user .= '<label> Ticket support system :</label>';
            $messageEmail_user .= ' <a href="'. base_url('user/home')  .'"> ' . base_url('user/home') . '  </a>';
            $messageEmail_user .= '</div> ';


            // email to owner
            $messageEmail_owner = ' <div>';
            $messageEmail_owner .= '  <h2> Ticket no. ' . $result_ticket['ticket_no'] . ' </h2>' ;
            $messageEmail_owner .= ' </div>';
            $messageEmail_owner = ' <div>';
            $messageEmail_owner .= '  <h3><b> หัวข้อ </b></h3>';
            $messageEmail_owner .= '     <p style="padding-left: 20px;"> ' . $result_ticket['topic'] . ' </p> ';
            $messageEmail_owner .= ' </div>';
            $messageEmail_owner .= ' <div>';
            $messageEmail_owner .= '  <h3><b> หมวดเรื่อง </b></h3>';
            $messageEmail_owner .= '    <p style="padding-left: 20px;"> ' . $result_ticket['nameCatTh'] . ' </p> ';
            $messageEmail_owner .= ' </div>';
            $messageEmail_owner .= ' <div>';
            $messageEmail_owner .= '  <h3><b> หมวดย่อย </b></h3>';
            $messageEmail_owner .= '    <p style="padding-left: 20px;"> ' . $result_ticket['nameSubCat'] . ' </p> ';
            $messageEmail_owner .= ' </div>';
            $messageEmail_owner .= ' <div>';
            $messageEmail_owner .= '  <h3><b> รายละเอียดเพิ่มเติม </b></h3>';
            $messageEmail_owner .= '    <p style="padding-left: 20px; word-wrap: break-word; "> ' . $result_ticket['remark'] . ' </p> ';
            $messageEmail_owner .= ' </div>';
            $messageEmail_owner .= '<div> ';
            $messageEmail_owner .= '<label> Ticket support system :</label>';
            $messageEmail_owner .= ' <a href="'. base_url('admin/ticket/list')  .'"> ' . base_url('user/home') . '  </a>';
            $messageEmail_owner .= '</div> ';


            $updateData = [
                'status' => 5,
                'catId' => $catId,
                'subCatId' => $subCatId,
                'ownerAccepted' => $ownerAccepted ,
                'updatedAt' => $this->time->getTimestamp(),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->sendEmailUser($titleMail, $subjectMail_user, $messageEmail_user, $result_ticket['email'], '')) {
                    if ($this->sendEmailUser($titleMail, $result_ticket['topic'], $messageEmail_owner, $owner_email['email'], $result_ticket['attachment'])) {
                        if ($this->ticketTaskModel->update($task_id, $updateData)) {
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
                                'message' => 'ไม่สามารถส่งเมล์ไปยัง admin ได้',
                            ];
                            return $this->response->setJson($response);
                        }
                    } else {
                        $response = [
                                'status' => 404,
                                'title' => 'Error!',
                                'message' => 'ไม่สามารถส่งเมล์ไปยัง user ได้',
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
        } else {
            $response = [
                'status' => 500,
                'title' => 'Error',
                'message' => 'Server internal error'
            ];

            return $this->response->setJSON($response);
        }
    }

    public function getMoreTicketDetail()
    {
        if ($this->request->isAJAX()) {
            $task_id = $this->request->getPost('taskId');

            $query['task'] = $this->ticketTaskModel
            ->select(
                'ticket_task.id as task_id,
                ticket_task.ticket_no,
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
            ->where('ticket_task.id', $task_id)
            ->findAll();

            $query['detail'] = $this->taskDetailModel
            ->select(
                'ticket_detail.cause as detail_cause,
                ticket_detail.solution as detail_solution,
                ticket_detail.remark as detail_remark,
                ticket_detail.attachment as detail_attachment,
                ticket_detail.createdAt as detail_created'
            )
            ->where('ticket_detail.taskId', $task_id)
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

    public function sendEmailUser($titleMail, $subjectMail, $messageEmail, $receiver, $image)
    {
        $logEmail = [
            'receiver' => $receiver,
            'title' => $titleMail,
            'subject' => $subjectMail,
            'detail' => strip_tags($messageEmail),
            'createdAt' => $this->time->getTimestamp(),
            'status' => 1
        ];

        $image_link = base_url() . '/public/store_files_uploaded' . $image;

        if ($this->logEmailModel->insert($logEmail)) {
            if ($this->sendEmailAPI($subjectMail, $messageEmail, $receiver, $image_link)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
