<?php

namespace App\Imports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class EventImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
         $data = [
            'title' => $row['title'],
            'description' => $row['description'],
            'start_time' => \Carbon\Carbon::parse($row['start_time']),
            'end_time' => \Carbon\Carbon::parse($row['end_time']),
            'is_completed' => !empty($row['is_completed']) ? $row['is_completed'] : 0,
            'recipients' => $row['recipients'],
        ];
        $data['event_id'] = Event::generateUniqueEventId();
        return Event::saveEvent($data);
    }
}
