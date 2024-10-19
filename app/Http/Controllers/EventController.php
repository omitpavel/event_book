<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EventImport;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index');
    }



    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new EventImport, $request->file('csv_file'));

        session()->flash('success', 'CSV file imported successfully!');

        return redirect()->back();
    }

}
