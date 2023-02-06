<?php
namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model {
  protected $table = 'notifications';
  protected $primaryKey = 'notification_id';
  protected $allowedFields = [
    'topic', 'type', 'receiver_id', 'details', 'seen'
  ];
}