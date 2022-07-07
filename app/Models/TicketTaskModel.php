<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketTaskModel extends Model
{
    protected $table = 'ticket_task';
    protected $allowedFields = ['id', 'topic', 'remark', 'attachment', 'createdAt', 'updatedAt', 'status', 'userId', 'catId', 'subCatId', 'ownerAccepted'];
}
