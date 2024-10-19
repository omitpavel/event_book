<div>
    <h1 class="mb-4">Event Manager</h1>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Modal for Add/Edit Event -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form wire:submit.prevent="{{ $isEdit ? 'updateEvent' : 'createEvent' }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">{{ $isEdit ? 'Edit Event' : 'Add Event' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" id="title" class="form-control" wire:model="title" required>
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control" wire:model="description"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" id="start_time" class="form-control" wire:model="start_time" required>
                            @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="datetime-local" id="end_time" class="form-control" wire:model="end_time" required>
                            @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recipients" class="form-label">Recipients (comma separated)</label>
                            <input type="text" id="recipients" class="form-control" wire:model="recipients">
                            @error('recipients') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Event' : 'Create Event' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Import CSV -->
    <div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('events.importCsv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCsvModalLabel">Import Events (CSV)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                            @error('csv_file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <a href="#" class="text-primary">Download a sample CSV</a><br>
                        <span class="text-danger">Note: Column recipients supports comma-separated values; write 1 for the is_completed column.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import Events</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Upcoming and Completed Events -->
    <div class="row mb-4">
        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Upcoming Events</h5>
                    <div>
                        <button class="btn btn-success btn-sm" wire:click="resetField()">Add Event</button>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importCsvModal">Import CSV</button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($upcomingEvents) < 1)
                        <p class="text-muted">No upcoming events found.</p>
                    @else
                        <ul class="list-group">
                            @foreach($upcomingEvents as $event)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $event['title'] }}</strong><br>
                                        <strong>ID : {{ $event['event_id'] }}</strong><br>
                                        <small class="text-muted">Starts at: {{ $event['start_time'] }}</small>
                                    </div>
                                    <div>
                                        <button wire:click="editEvent('{{$event['event_id']}}')" class="btn btn-warning btn-sm">Edit</button>
                                        <button wire:click="deleteEvent('{{$event['event_id']}}')" class="btn btn-danger btn-sm">Delete</button>
                                        <button wire:click="sendReminder('{{$event['event_id']}}')" class="btn btn-info btn-sm">Send Reminder</button>
                                        <button wire:click="completeEvent('{{$event['event_id']}}')" class="btn btn-success btn-sm">Complete</button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5>Completed Events</h5>
                </div>
                <div class="card-body">
                    @if(count($completedEvents) < 1)
                        <p class="text-muted">No completed events found.</p>
                    @else
                        <ul class="list-group">
                            @foreach($completedEvents as $event)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $event['title'] }}</strong><br>
                                        <strong>ID : {{ $event['event_id'] }}</strong><br>
                                        <small class="text-muted">Completed at: {{ $event['end_time'] }}</small>
                                    </div>
                                    <button wire:click="deleteEvent('{{$event['event_id']}}')" class="btn btn-danger btn-sm">Delete</button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
