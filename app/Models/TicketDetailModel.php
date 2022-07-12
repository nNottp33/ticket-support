<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketDetailModel extends Model
{
    protected $table = 'ticket_detail';
    protected $allowedFields = ['id', 'cause', 'solution', 'remark', 'attachment', 'updatedBy', 'createdAt', 'updatedAt', 'taskId'];
}
