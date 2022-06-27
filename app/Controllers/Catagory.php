<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Catagory extends BaseController
{
    protected $time;
    protected $session;
    protected $catModel;
    protected $ownerGroupModel;
    protected $subCatModel;
    protected $LogUsageModel;
    protected $cookie;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->session = session();
        $this->catModel = new \App\Models\CatagoryModel();
        $this->ownerGroupModel = new \App\Models\GroupOwnerModel();
        $this->subCatModel = new \App\Models\SubCatagoryModel();
        $this->LogUsageModel = new \App\Models\LogUsageModel();
        helper('url');
    }

    public function index()
    {
        return view('main/admin/cat_list');
    }


    public function getCategories()
    {
        if ($this->request->isAJAX()) {
            $query = $this->catModel->where('status != 4')->findAll();

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



    public function getCatagoryOwner()
    {
        if ($this->request->isAJAX()) {
            $owner_groupId =  $this->request->getGet('ownerGroupId');

            $query = $this->ownerGroupModel->select('users.email as ownerEmail, users.fullname as ownerName, users.empId as ownerEmpId, users.status, users.id')->join('users', 'users.id = group_owner.ownerId')->where('users.status !=4')->where('group_owner.groupId', $owner_groupId)->findAll();

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

    public function getSubCatagory()
    {
        if ($this->request->isAJAX()) {
            $catId =  $this->request->getGet('catId');

            $query = $this->subCatModel->where('status !=4')->where('catId', $catId)->orderBy('createdAt', 'desc')->findAll();

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
