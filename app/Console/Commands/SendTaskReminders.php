<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';

    protected $description = 'ارسال یادآوری برای تسک‌های نزدیک به موعد';

    public function handle()
    {
        $now = Carbon::now();
        $this->info("Now: " . $now);

        $timedTasks = Task::where('status', 'pending')
            ->where('reminder', 1)
            ->whereNull('reminder_sent_at')
            ->whereNotNull('time')
            ->whereDate('due_date', $now->toDateString())
            ->get()
            ->filter(function ($task) use ($now) {
                $taskDateTime = Carbon::parse($task->due_date->toDateString() . ' ' . $task->time);
                return $taskDateTime->between($now->copy()->subMinutes(5), $now);
            });

        $this->info("Timed tasks found: " . $timedTasks->count());

        foreach ($timedTasks as $task) {
            $task->user->notify(new TaskReminderNotification($task));
            $task->update(['reminder_sent_at' => $now]);
            $this->info("Notified task #{$task->id}");
        }

        if ($now->format('H:i') >= '08:00' && $now->format('H:i') < '08:05') {
            $untimedTasks = Task::where('status', 'pending')
                ->where('reminder', 1)
                ->whereNull('reminder_sent_at')
                ->whereNull('time')
                ->whereDate('due_date', $now->toDateString())
                ->get();

            $this->info("Untimed tasks found: " . $untimedTasks->count());

            foreach ($untimedTasks as $task) {
                $task->user->notify(new TaskReminderNotification($task));
                $task->update(['reminder_sent_at' => $now]);
            }
        }

        $this->info("Done.");
    }
}
