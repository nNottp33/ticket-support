<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    protected $session;
    protected $userModel;
    protected $ticketModel;
    protected $time;
    protected $current_date;

    public function __construct()
    {
        $this->session = session();
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->current_date = Time::today('Asia/Bangkok', 'th');
        $this->userModel = new \App\Models\UserModel();
        $this->ticketModel = new \App\Models\TicketTaskModel();
    }

    public function index()
    {
        $start = strtotime("first day of this month");
        $end = strtotime("last day of this month");

        // user all
        $data['count_user_all'] = $this->userModel->countAll();

        // new user in month
        $data['count_user_new'] = $this->userModel->select('count(id) as user_new')
        ->where('createdAt >=', $start)
        ->where('createdAt <=', $end)
        ->get()
        ->getRow()->user_new;

        // ticket of month
        $data['count_ticket_month'] = $this->ticketModel
        ->select('count(id) as ticket_month')
        ->where('createdAt >=', $start)
        ->where('createdAt <=', $end)
        ->get()
        ->getRow()->ticket_month;

        // ticket complete (month)
        $data['count_ticket_complete_month'] = $this->ticketModel
        ->select('count(id) as ticket_close')
        ->where('createdAt >=', $start)
        ->where('createdAt <=', $end)
        ->where('status', 4)
        ->get()
        ->getRow()->ticket_close;

        // reject tickets (month)
        $data['count_ticket_reject_month'] = $this->ticketModel
        ->select('count(id) as ticket_reject')
        ->where('createdAt >=', $start)
        ->where('createdAt <=', $end)
        ->where('status', 3)
        ->get()
        ->getRow()->ticket_reject;

        return view('main/admin/dashboard', ['data' => $data]);
    }

    public function getTicketOften()
    {
        if ($this->request->isAJAX()) {

            // set error group by
            $db      = \Config\Database::connect();
            $db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

            $query = $this->ticketModel
                ->select('catagories.nameCatTh as catName, 
                sub_catagories.nameSubCat as subCatName, 
                users.fullname as ownerTicket, 
                count(ticket_task.subCatId) as countTicket')
                ->join('catagories', 'catagories.id = ticket_task.catId')
                ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
                ->join('users', 'users.id = ticket_task.ownerAccepted')
                ->groupBy('ticket_task.subCatId')
                ->orderBy('countTicket', 'DESC')
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
                'title' => 'Error!',
                'message' => 'Server Internal Error',
            ];
            return $this->response->setJSON($response);
        }
    }
}
