<?php

namespace App\Models;

use CodeIgniter\Model;

class CatagoryModel extends Model
{
    protected $table = 'catagories';
    protected $allowedFields = ['id', 'nameCatTh', 'nameCatEn', 'createdAt', 'status'];
}
