<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'title', 'description', 'start_time', 'end_time', 'is_completed', 'recipients'];


    public static function allEvents()
    {
        try {
            return self::get()->toArray();
        } catch (\Exception $e) {

            return json_decode(self::getLocalStorageEvents(), true) ?? [];
        }
    }

    public static function upcomingEvents()
    {
        $events = self::allEvents();
        return array_filter($events, function ($event) {
            return ($event['is_completed'] == 0);
        });
    }

    public static function completedEvents()
    {
        $events = self::allEvents();
        return array_filter($events, function ($event) {
            return ($event['is_completed'] == 1);
        });
    }

    public static function saveEvent(array $data)
    {
        try {
            return self::create($data);
        } catch (\Exception $e) {
            $data['event_id'] = self::generateUniqueEventId();
            self::saveToLocalStorage($data);
        }
    }

    public static function getLocalStorageEvents()
    {

        return file_get_contents(base_path('tmp/local_events.json'));
    }

    public static function saveToLocalStorage(array $data)
    {
        $events = json_decode(self::getLocalStorageEvents(), true) ?? [];

        $events[] = $data;

        file_put_contents(base_path('tmp/local_events.json'), json_encode($events));
    }

    public static function generateUniqueEventId()
    {
        $filePath = base_path('tmp/last_event_id.txt');

        if (file_exists($filePath)) {
            $lastUniqueId = file_get_contents($filePath);
        } else {
            $lastUniqueId = 'EVT-000000';
        }
        $numericPart = (int) substr($lastUniqueId, 3) + 1;

        $newEventId = 'EVT-' . str_pad($numericPart, 6, '0', STR_PAD_LEFT);

        file_put_contents($filePath, $newEventId);

        return $newEventId;
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $filePath = base_path('tmp/last_event_id.txt');

            if (file_exists($filePath)) {
                $lastUniqueId = file_get_contents($filePath);
            } else {
                $lastUniqueId = 'EVT-000000';
            }
            $numericPart = (int)substr($lastUniqueId, 3) + 1;
            $potentialUniqueId = 'EVT-' . str_pad($numericPart, 6, '0', STR_PAD_LEFT);

            while (static::where('event_id', $potentialUniqueId)->exists()) {
                $numericPart++;
                $potentialUniqueId = 'EVT-' . str_pad($numericPart, 6, '0', STR_PAD_LEFT);
            }

            $model->event_id = $potentialUniqueId;
        });
    }
}
