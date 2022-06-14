<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['id', 'empId', 'prefix', 'fullname', 'nickname', 'email', 'password', 'tel', 'class', 'createdAt', 'lastLogin', 'status', 'positionId', 'departmentId'];
}
