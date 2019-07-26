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
        $reservation = Reservation::create([
            'user_id' => $request->user_id,
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
