<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class AdminReport extends BaseController
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
            $ownerId = $this->request->getPost('ownerId');

            $start_date = strtotime($this->request->getPost('startDate') . "00:00:00");
            $end_date = strtotime($this->request->getPost('endDate') . "23:59:59");

            if ($this->request->getPost('type') == 'dashboard') {
                $query = $this->ticketModel
                ->select('
                    ticket_task.catId as catId,
                    users.fullname as ownerName,
                    catagories.nameCatTh as catName,
                    count(ticket_task.catId) as total_ticket,
                    SUM( CASE WHEN ticket_task.status = "1" THEN 1 ELSE 0 END ) as pending_in_sla,
                    SUM( CASE WHEN ticket_task.status = "2" THEN 1 ELSE 0 END ) as complete_out_sla,
                    SUM( CASE WHEN ticket_task.status = "3" THEN 1 ELSE 0 END ) as reject_out_sla,
                    SUM( CASE WHEN ticket_task.status = "4" THEN 1 ELSE 0 END ) as close_out_sla,
                    SUM( CASE WHEN ticket_task.status = "5" THEN 1 ELSE 0 END ) as renew_in_sla,
                ')
                ->join('users', 'users.id = ticket_task.ownerAccepted')
                ->join('catagories', 'catagories.id = ticket_task.catId')
                ->whereIn('ticket_task.ownerAccepted', $ownerId)
                ->where('ticket_task.createdAt >=', $start_date)
                ->where('ticket_task.createdAt <=', $end_date)
                ->groupBy('ticket_task.catId')
                ->orderBy('ticket_task.id', 'DESC')
                ->findAll();
            }


            // echo"<pre>";
            // print_r($query);
            // die();

            if ($this->request->getPost('type') == 'performance') {
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


    public function reportPerformance_page()
    {
        return view('main/admin/report_performance_page');
    }


    public function reportDashboard_page()
    {
        return view('main/admin/report_dashboard_page');
    }
}
