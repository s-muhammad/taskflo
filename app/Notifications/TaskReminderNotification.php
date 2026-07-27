<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TaskReminderNotification extends Notification
{
    public function __construct(protected Task $task) {}

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('یادآوری وظیفه')
            ->body($this->task->title . ' نزدیک به موعد انجامه')
            ->icon('/apple-touch-icon.png')
            ->data(['url' => route('task')]);
    }
}
