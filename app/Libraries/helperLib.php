<?php

namespace App\Libraries;

use App\Models\NotificationModel;

class helperLib
{
    private NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function create_new_notification($type, $topic, $receiver_id, $details)
    {
        $notification_data = array();
        $notification_data['type'] = $type;
        $notification_data['topic'] = $topic;
        $notification_data['receiver_id'] = $receiver_id;
        $notification_data['details'] = $details;
        $this->notificationModel->save($notification_data);
    }
}