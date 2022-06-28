<?php
namespace App\Models;

use CodeIgniter\Model;

class LogEmailModel extends Model
{
    protected $table = 'log_mail';
    protected $allowedFields = ['id', 'receiverId', 'title', 'subject', 'detail', 'createdAt', 'status'];
}
