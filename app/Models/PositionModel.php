<?php
namespace App\Models;

use CodeIgniter\Model;

class PositionModel extends Model
{
    protected $table = 'position';
    protected $allowedFields = ['id', 'namePosition', 'createdAt', 'departmentId'];
}
