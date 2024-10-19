<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class CheckDatabaseConnectionAndSync
{
    public function handle($request, Closure $next)
    {
        try {
            DB::connection()->getPdo();

            $this->syncLocalEvents();

            return $next($request);
        } catch (\Exception $e) {
            Log::warning('Database connection failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'offline',
                'message' => 'Database connection failed, using local storage',
                'events' => $this->getLocalEvents()
            ]);
        }
    }

    protected function getLocalEvents()
    {
        $events = file_get_contents(storage_path('app/local_events.json'));
        return json_decode($events, true) ?? [];
    }

    protected function syncLocalEvents()
    {
        $events = $this->getLocalEvents();
        if(count($events) > 0){
            Event::upsert($events, 'event_id');
            file_put_contents(storage_path('app/local_events.json'), json_encode(array()));
        }
    }

}
