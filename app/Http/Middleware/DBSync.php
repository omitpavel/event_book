<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class DBSync
{
    public function handle($request, Closure $next)
    {
        try {
            DB::connection()->getPdo();

            $this->syncLocalEvents();

            return $next($request);
        } catch (\Exception $e) {
            return $next($request);
        }
    }

    protected function getLocalEvents()
    {
        $events = file_get_contents(base_path('tmp/local_events.json'));
        return json_decode($events, true) ?? [];
    }

    protected function syncLocalEvents()
    {
        $events = $this->getLocalEvents();
        if(count($events) > 0){
            Event::upsert($events, 'event_id');
            file_put_contents(base_path('tmp/local_events.json'), json_encode(array()));
        }
    }

}
