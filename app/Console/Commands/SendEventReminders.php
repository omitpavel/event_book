<?php

namespace App\Console\Commands;

use App\Mail\EventReminderMail;
use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';

    protected $description = 'Send event reminders';

    public function handle()
    {

        try {
            $events = Event::where('start_time', '<=', now()->addMinutes(30))
                ->where('is_completed', false)
                ->get();

            foreach ($events as $event) {
                $recipients = explode(',', $event->recipients);
                foreach ($recipients as $recipient) {
                    Mail::to(trim($recipient))->send(new EventReminderMail($event));
                }
            }
        } catch (\Exception $e) {
            $events = Event::upcomingEvents();
            foreach ($events as $event) {
                $recipients = explode(',', $event['recipients']);
                foreach ($recipients as $recipient) {
                    $eventModel = new Event($event);
                    Mail::to(trim($recipient))->send(new EventReminderMail($eventModel));
                }
            }

        }

        $this->info('Event reminders sent successfully.');
    }


}
