<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class UserReport extends BaseController
{
    protected $session;
    protected $time;
    protected $ticketModel;
    protected $userModel;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->ticketModel = new \App\Models\TicketTaskModel();
        $this->userModel = new \App\Models\UserModel();
        helper(['form', 'url']);
    }

    
    public function index()
    {
        if ($this->request->isAJAX()) {
            $type = $this->request->getGet('type');

            $start_date = strtotime($this->request->getGet('startDate') . "00:00:00");
            $end_date = strtotime($this->request->getGet('endDate') . "23:59:59");

            if ($type == 'all') {
                $ticket_no = $this->request->getGet('ticket_no');

                $query = $this->ticketModel
                ->select('ticket_task.id as task_id, 
                    ticket_task.ticket_no, 
                    ticket_task.status as task_status,
                    ticket_task.createdAt as task_created,
                    ticket_task.topic as task_topic,
                    users.fullname as ownerName,
                    catagories.nameCatTh as catName
                    ')
                ->join('users', 'users.id = ticket_task.ownerAccepted')
                ->join('catagories', 'catagories.id = ticket_task.catId')
                ->like('ticket_task.ticket_no', $ticket_no, 'both')
                ->where('ticket_task.createdAt >=', $start_date)
                ->where('ticket_task.createdAt <=', $end_date)
                ->where('ticket_task.userId', $this->session->get('id'))
                ->orderBy('ticket_task.id', 'DESC')
                ->findAll();
            }

            if ($type == 'status') {
                $status = $this->request->getGet('status');

                $query = $this->ticketModel
                    ->select('ticket_task.id as task_id, 
                        ticket_task.ticket_no, 
                        ticket_task.status as task_status,
                        ticket_task.createdAt as task_created,
                        ticket_task.topic as task_topic,
                        users.fullname as ownerName,
                        catagories.nameCatTh as catName
                        ')
                    ->join('users', 'users.id = ticket_task.ownerAccepted')
                    ->join('catagories', 'catagories.id = ticket_task.catId')
                    ->whereIn('ticket_task.status', $status)
                    ->where('ticket_task.createdAt >=', $start_date)
                    ->where('ticket_task.createdAt <=', $end_date)
                    ->where('ticket_task.userId', $this->session->get('id'))
                    ->orderBy('ticket_task.id', 'DESC')
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


    public function ticketAll_page()
    {
        return view('main/user/report_ticket_all');
    }


    public function ticketStatus_page()
    {
        return view('main/user/report_ticket_status');
    }
}
