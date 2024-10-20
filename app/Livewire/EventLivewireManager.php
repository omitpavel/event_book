<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Imports\EventImport;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminderMail;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class EventLivewireManager extends Component
{
    use WithFileUploads;
    public $title, $description, $start_time, $end_time, $recipients, $eventId, $csv_file;
    public $isEdit = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'recipients' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.event-manager', [
            'upcomingEvents' => Event::upcomingEvents(),
            'completedEvents' => Event::completedEvents(),
        ]);
    }

    public function createEvent()
    {
        $this->validate();
        Event::saveEvent([
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'recipients' => $this->recipients,
            'is_completed' => 0,
        ]);

        $this->resetFields();
        session()->flash('success', 'Event created successfully.');
        $this->dispatch('close-modal', 'eventModal');
    }


    public function editEvent($eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->first();
            $this->eventId = $event['event_id'];
            $this->title = $event['title'];
            $this->description = $event['description'];
            $this->start_time = $event['start_time'];
            $this->end_time = $event['end_time'];
            $this->recipients = $event['recipients'];
            $this->isEdit = true;

        } catch (\Exception $e) {

            $events = json_decode(Event::getLocalStorageEvents(), true);

            $event = collect($events)->firstWhere('event_id', $eventId);
            $this->eventId = $event['event_id'];
            $this->title = $event['title'];
            $this->description = $event['description'];
            $this->start_time = $event['start_time'];
            $this->end_time = $event['end_time'];
            $this->recipients = $event['recipients'];
            $this->isEdit = true;
        }
        $this->dispatch('open-modal', 'eventModal');
    }


    public function updateEvent()
    {
        $this->validate();


        try {
            $event = Event::where('event_id', $this->eventId)->first();
            $event->update([
                'title' => $this->title,
                'description' => $this->description,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'recipients' => $this->recipients,
            ]);
        } catch (\Exception $e) {
            $events = json_decode(Event::getLocalStorageEvents(), true);
            $eventIndex = array_search($this->eventId, array_column($events, 'event_id'));

            if ($eventIndex !== false) {
                $events[$eventIndex] = [
                    'event_id' => $this->eventId,
                    'title' => $this->title,
                    'description' => $this->description,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'recipients' => $this->recipients,
                    'is_completed' => $events[$eventIndex]['is_completed'],
                ];
                file_put_contents(base_path('tmp/local_events.json'), json_encode($events));
            }
        }

        $this->resetFields();
        session()->flash('success', 'Event updated successfully.');
        $this->dispatch('close-modal', 'eventModal');
    }


    public function deleteEvent($id)
    {
        try {
            $event = Event::where('event_id', $id)->first();
            $event->delete();
        } catch (\Exception $e) {
            $events = json_decode(Event::getLocalStorageEvents(), true);

            $updatedEvents = collect($events)->reject(function ($event) use ($id) {
                return $event['event_id'] == $id;
            })->toArray();

            file_put_contents(base_path('tmp/local_events.json'), json_encode($updatedEvents));

        }
        session()->flash('success', 'Event deleted successfully.');
    }

    public function sendReminder($id)
    {
        try {
            $send_event = Event::where('event_id', $id)->first();
            $recipients = explode(',', $send_event->recipients);
        } catch (\Exception $e) {
            $events = json_decode(Event::getLocalStorageEvents(), true);

            $event = collect($events)->firstWhere('event_id', $id);
            $send_event = new Event($event);
            $recipients = explode(',', $event['recipients']);
        }
        foreach ($recipients as $recipient) {
            Mail::to(trim($recipient))->send(new EventReminderMail($send_event));
        }


        session()->flash('success', 'Reminders sent successfully.');
    }
    public function completeEvent($eventId)
    {
        try {
            $event = Event::where('event_id', $eventId)->first();

            $event->update(['is_completed' => true]);
            session()->flash('success', 'Event marked as completed successfully.');
        } catch (\Exception $e) {
            $events = json_decode(Event::getLocalStorageEvents(), true);
            $event = collect($events)->firstWhere('event_id', $eventId);

            if ($event) {
                $event['is_completed'] = true;
                $events = collect($events)->reject(fn($e) => $e['event_id'] === $eventId)->values()->all();
                $events[] = $event;
                file_put_contents(base_path('tmp/local_events.json'), json_encode($events));
                session()->flash('success', 'Event marked as completed successfully.');
            } else {
                session()->flash('error', 'Event not found.');
            }
        }
    }


    private function resetFields()
    {
        $this->title = '';
        $this->description = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->recipients = '';
        $this->eventId = null;
        $this->isEdit = false;
    }

    public function resetField()
    {
        $this->title = '';
        $this->description = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->recipients = '';
        $this->eventId = null;
        $this->isEdit = false;
        $this->dispatch('open-modal', 'eventModal');
    }




}
