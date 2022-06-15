<?php
namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';
    protected $allowedFields = ['id', 'nameDepart', 'createdAt'];
}
