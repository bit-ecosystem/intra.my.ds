<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/login', function () {
//     // point unauthenticated users to Filament's login page
//     return redirect()->route('filament.staff.auth.login');
// })->name('login');

Route::middleware(['web', 'auth']) // align with your Filament guard
    ->get('/calendar/events', function (Request $request) {
        // FullCalendar sends ?start=YYYY-MM-DD & ?end=YYYY-MM-DD
        // Use them to filter DB events if you have a model.
        // Below is a static example:
        return response()->json([
            [
                'id' => 101,
                'title' => 'Team Standup',
                'start' => now()->toDateString().'T09:00:00',
                'end' => now()->toDateString().'T09:30:00',
            ],
            [
                'id' => 102,
                'title' => 'Planning',
                'start' => now()->addDay()->toDateString().'T14:00:00',
                'end' => now()->addDay()->toDateString().'T15:00:00',
            ],
        ]);
    })
    ->name('calendar.events');
