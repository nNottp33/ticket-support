<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class HistoryTicket extends BaseController
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
        return view('main/user/history_ticket');
    }


    public function searchHistory()
    {
        if ($this->request->isAJAX()) {
            $start_date = strtotime($this->request->getGet('startDate') . "00:00:00");
            $end_date = strtotime($this->request->getGet('endDate') . "23:59:59");
            // $status = $this->request->getGet('status');

            $query = $this->ticketModel
            ->select('ticket_task.ticket_no,
                      ticket_task.id as ticket_id, 
                      ticket_task.topic as ticket_topic,
                      ticket_task.createdAt as ticket_created,
                      ticket_task.updatedAt as ticket_updated,
                      ticket_task.status as ticket_status,
                      catagories.nameCatTh as catName,
                      sub_catagories.nameSubCat as subCatName')
            ->join('catagories', 'catagories.id = ticket_task.catId')
            ->join('sub_catagories', 'sub_catagories.id = ticket_task.subCatId')
            ->where('ticket_task.createdAt >=', $start_date)
            ->where('ticket_task.createdAt <=', $end_date)
            ->where('ticket_task.userId =', $this->session->get('id'))
            // ->orWhereIn('ticket_task.status', [3,4])
            ->findAll();

            // echo "<pre>" ;
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
