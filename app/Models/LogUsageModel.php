<?php
namespace App\Models;

use CodeIgniter\Model;

class LogUsageModel extends Model
{
    protected $table = 'log_usage';
    protected $allowedFields = ['id', 'ip', 'action', 'detail', 'createdAt', 'status', 'userId'];
}
