<?php
namespace App\Models;

use CodeIgniter\Model;

class GroupOwnerModel extends Model
{
    protected $table = 'group_owner';
    protected $allowedFields = ['id', 'ownerId', 'groupId', 'status'];
}
