<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\Reservation as ReservationResource;

class ReservationsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return Reservation::all();
    }

    public function store(Request $request)
    {
        // if the requested date is in more than a week time, return
        if ($request->date > now()->startOfDay()->addDays('7')) {
            return;
        }
        // if the requested date is in the past, return
        if ($request->date < now()->subDays('1')->startOfDay()) {
            return;
        }
        $reservations_at_requested_date = Reservation::where('date', $request->date)->get();
        
        // check if day is full
        if (count($reservations_at_requested_date) === 2) {
            return;
        }

        // check if the parking requested is not the same as the one already booked
        if (count($reservations_at_requested_date) > 0) {
            if ($reservations_at_requested_date[0]->parking_number === $request->parking_number) {
                return;
            };
        }
        
        // return if there is already a reservation in the next days
        $user_reservations = Reservation::where('user_id', auth()->id())->whereDate('date', '>=', now()->startOfDay())->exists();
        if ($user_reservations) {
            return;
        }
        
        // add record to the database
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'parking_number' => $request->parking_number,
            'date' => $request->date
        ]);
        return $reservation->id;
    }

    public function show(Reservation $reservation)
    {
        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
    }

    public function isAUthorized(User $user)
    {
        $reservations = $user->reservations;
        foreach ($reservations as $reservation) {
            $date = Carbon::create($reservation->date);
            if ($date->greaterThanOrEqualTo(now()->startOfDay())) {
                return 0;
            };
        }
        return 1;
    }
}
