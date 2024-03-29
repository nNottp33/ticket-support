<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Catagory extends BaseController
{
    protected $time;
    protected $session;
    protected $userModel;
    protected $catModel;
    protected $ownerGroupModel;
    protected $subCatModel;
    protected $LogUsageModel;
    protected $cookie;

    public function __construct()
    {
        $this->time = Time::now('Asia/Bangkok', 'th');
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
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

            $query = $this->ownerGroupModel->select('users.email as ownerEmail, users.fullname as ownerName, users.empId as ownerEmpId, users.status, users.id, position.namePosition as ownerPosition')->join('users', 'users.id = group_owner.ownerId')->join('position', 'users.positionId = position.id')->where('users.status !=4')->where('group_owner.groupId', $owner_groupId)->findAll();

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

    public function updateStatusCat()
    {
        if ($this->request->isAJAX()) {
            $status = $this->request->getPost('status');
            $id = $this->request->getPost('id');
            $nameSubCat = $this->request->getPost('nameCat');

            $textStatus = $status == 1 ? 'เปิด' : 'ปิด';

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'update status catagory',
                'detail' => "Admin " . $this->session->get('email') . $textStatus . "ใช้งาน cat " .  $nameSubCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

        
            if ($this->LogUsageModel->insert($logData)) {
                if ($this->catModel->where('status != 4')->update($id, ['status' => $status]) && $this->subCatModel->where('status != 4')->where('catId', $id)->set(['status' => $status])->update()) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => $textStatus . 'ใช้งานสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถ'. $textStatus .'ใช้งานได้',
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

    public function deleteCatagory()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nameCat = $this->request->getPost('nameCat');
            $status = $this->request->getPost('status');
            
            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'deleted catagory',
                'detail' => "Admin " . $this->session->get('email') . " ลบ catagory " . $nameCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->catModel->update($id, ['status' => $status]) && $this->subCatModel->where('catId', $id)->set(['status' => $status])->update()) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'ลบข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถลบข้อมูลได้',
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

    public function deleteSubCatagory()
    {
        if ($this->request->isAJAX()) {
            $subCatId = $this->request->getPost('id');
            $nameSubCat = $this->request->getPost('nameSubCat');
           
            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'deleted sub catagory',
                'detail' => "Admin " . $this->session->get('email') . " ลบ sub catagory " . $nameSubCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->subCatModel->update($subCatId, ['status' => 4])) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'ลบข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถลบข้อมูลได้',
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

    public function updateStatusSubCat()
    {
        if ($this->request->isAJAX()) {
            $status = $this->request->getPost('status');
            $id = $this->request->getPost('id');
            $nameSubCat = $this->request->getPost('nameSubCat');

            $textStatus = $status == 1 ? 'เปิด' : 'ปิด';

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'update status sub catagory',
                'detail' => "Admin " . $this->session->get('email') . $textStatus . "ใช้งาน sub cat " .  $nameSubCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->subCatModel->where('status != 4')->update($id, ['status' => $status])) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => $textStatus . 'ใช้งานสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถ'. $textStatus .'ใช้งานได้',
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

    public function insertSubCat()
    {
        if ($this->request->isAJAX()) {
            $nameSubCat = $this->request->getPost('nameSubCat');
            $detail = $this->request->getPost('detail');
            $period = $this->request->getPost('period');
            $catId = $this->request->getPost('catId');

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'insert sub catagory',
                'detail' => "Admin " . $this->session->get('email') . " เพิ่ม sub cat " .  $nameSubCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            $insertData = [
                'nameSubCat' => $nameSubCat,
                'detail' => $detail,
                'createdAt' => $this->time->getTimestamp(),
                'period' => $period * 60,
                'status' => 1,
                'catId' => $catId
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->subCatModel->insert($insertData)) {
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

    public function getUpdateSubCat()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $query = $this->subCatModel->where('status != 4')->where('id', $id)->first();

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

    public function updateSubCat()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nameSubCat = $this->request->getPost('nameSubCat');
            $detail = $this->request->getPost('detail');
            $period = $this->request->getPost('period');

            $updateData = [
                'nameSubCat' => $nameSubCat,
                'detail' => $detail,
                'period' => $period * 60,
            ];

            $beforeData = $this->subCatModel->where('id', $id)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'updated sub catagory data',
                'detail' => 'BEFORE => ' . json_encode($beforeData) .'AFTER =>' . json_encode($updateData),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->subCatModel->update($id, $updateData)) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'แก้ไขข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถแก้ไขข้อมูลได้',
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

    public function getAdminList()
    {
        if ($this->request->isAJAX()) {
            $query = $this->userModel->select('id, empId, prefix, fullname, nickname, email, tel, class')->where('status', 1)->where('class', 'admin')->orderBy('id', 'asc')->findAll();

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

    public function saveCatagoryOwner()
    {
        if ($this->request->isAJAX()) {
            $groupId = $this->request->getPost('groupId');
            $ownerId = $this->request->getPost('ownerId');

            $adminUser = $this->userModel->select('fullname')->where('id', $ownerId)->first();
            
            $check = $this->ownerGroupModel->where('ownerId', $ownerId)->where('groupId', $groupId)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'insert catagory owner',
                'detail' => "Admin " . $this->session->get('email') . " เพิ่ม cat owner " . $adminUser['fullname'],
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            $insertData = [
                'ownerId' => $ownerId,
                'groupId' => $groupId,
                'status' => 1
            ];

            if (!isset($check)) {
                if ($this->LogUsageModel->insert($logData)) {
                    if ($this->ownerGroupModel->insert($insertData)) {
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
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ข้อมูลซ้ำ',
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

    public function deleteCatagoryOwner()
    {
        if ($this->request->isAJAX()) {
            $nameOwner = $this->request->getPost('nameOwner');
            $ownerId = $this->request->getPost('ownerId');
            $catId = $this->request->getPost('catId');

            $cat = $this->catModel->select('nameCatTh')->where('id', $catId)->first();
    
            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'delete catagory owner',
                'detail' => "Admin " . $this->session->get('email') . " ลบ owner " .  $nameOwner . ' ออกจาก cat ' . $cat['nameCatTh'] ,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];


            if ($this->LogUsageModel->insert($logData)) {
                if ($this->ownerGroupModel->where('groupId', $catId)->where('ownerId', $ownerId)->delete()) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'ลบข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถลบข้อมูลได้',
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

    public function getCatEdit()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $query = $this->catModel->where('id', $id)->findAll();

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

    public function updateCatagory()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $nameCatTh = $this->request->getPost('nameCatTh');
           

            $updateData = [
                'nameCatTh' => $nameCatTh,
            ];

            $beforeData = $this->catModel->where('id', $id)->first();

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'updated catagory data',
                'detail' => 'BEFORE => ' . json_encode($beforeData) .'AFTER =>' . json_encode($updateData),
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            if ($this->LogUsageModel->insert($logData)) {
                if ($this->catModel->update($id, $updateData)) {
                    $response = [
                        'status' => 200,
                        'title' => 'Success!',
                        'message' => 'แก้ไขข้อมูลสำเร็จ',
                    ];
                    return $this->response->setJson($response);
                } else {
                    $response = [
                        'status' => 404,
                        'title' => 'Error!',
                        'message' => 'ไม่สามารถแก้ไขข้อมูลได้',
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

    public function insertCatagory()
    {
        if ($this->request->isAJAX()) {
            $nameCat = $this->request->getPost('nameCat');
            $ownerId = $this->request->getPost('ownerId');

            $logData = [
                'ip' => $this->request->getIPAddress(),
                'action' => 'insert catagory',
                'detail' => "Admin " . $this->session->get('email') . " เพิ่ม cat " .  $nameCat,
                'createdAt' => $this->time->getTimestamp(),
                'userId' => $this->session->get('id'),
            ];

            $insertCat = [
                'nameCatTh' => $nameCat,
                'nameCatEn' => $nameCat,
                'createdAt' => $this->time->getTimestamp(),
                'status' => 1
            ];


            if ($this->catModel->where('nameCatTh', $nameCat)->first()) {
                $response = [
                    'status' => 404,
                    'title' => 'Error!',
                    'message' => 'ข้อมูลซ้ำ!',
                ];
                return $this->response->setJson($response);
            } else {
                if ($this->LogUsageModel->insert($logData)) {
                    if ($this->catModel->insert($insertCat)) {
                        $resultGroupId = $this->catModel->where('nameCatTh', $nameCat)->first();

                        for ($i = 0; $i < sizeOf($ownerId); $i++) {
                            $ownerData[$i] = [
                            'groupId' => $resultGroupId['id'],
                            'ownerId' => $ownerId[$i],
                            'status' => 1,
                        ];
                        }

                        if ($this->ownerGroupModel->insertBatch($ownerData)) {
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
