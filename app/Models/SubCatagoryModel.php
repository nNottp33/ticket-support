<?php
namespace App\Models;

use CodeIgniter\Model;

class SubCatagoryModel extends Model
{
    protected $table = 'sub_catagories';
    protected $allowedFields = ['id', 'nameSubCat', 'detail', 'createdAt', 'period', 'status', 'catId'];
}
