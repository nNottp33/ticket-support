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
}
