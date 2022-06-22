<?php
namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table = 'otp';
    protected $allowedFields = ['id', 'ref', 'code', 'userId', 'createdAt', 'status'];
}
