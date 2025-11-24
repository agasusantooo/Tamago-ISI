<?php

namespace App\Http\Controllers\KoordinatorTA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalAcara; // Assuming a model named JadwalAcara exists

class JadwalController extends Controller
{
    public function index()
    {
        // Fetch events and return as JSON
        $events = JadwalAcara::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'start' => $item->start,
                'end' => $item->end,
                'color' => $item->color,
            ];
        });
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);

        $event = JadwalAcara::create($request->all());

        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = JadwalAcara::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string',
        ]);
        
        $event->update($request->all());

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = JadwalAcara::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['status' => 'success']);
    }
}
