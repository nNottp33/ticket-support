<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketTaskModel extends Model
{
    protected $table = 'ticket_task';
    protected $allowedFields = ['id', 'ticket_no', 'topic', 'remark', 'attachment', 'createdAt', 'updatedAt', 'status', 'userId', 'catId', 'subCatId', 'ownerAccepted'];
}
