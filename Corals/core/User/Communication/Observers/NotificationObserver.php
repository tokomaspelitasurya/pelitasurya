<?php

namespace Corals\User\Communication\Observers;

use Corals\User\Communication\Events\CoralsBroadcastEvent;
use Corals\User\Communication\Models\Notification;

class NotificationObserver
{

    /**
     * @param Notification $notification
     */
    public function created(Notification $notification)
    {
        $notifiable = $notification->notifiable;

        $channelName = sprintf("%s.%s", strtolower(class_basename($notifiable)), $notifiable->hashed_id, $notifiable);

        event(new CoralsBroadcastEvent($channelName, $notification, $notifiable));
    }
}