<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Reservation;
use App\Http\Requests\ReservationRequest;

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

    public function store(ReservationRequest $request)
    {
        if (auth()->id() === 14 && $request->parking_number == 3) {
            abort(404, 'Une erreur est survenue');
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
        // check if the user can view the selected reservation
        $this->authorize('view', $reservation);

        return $reservation;
    }

    public function destroy(Reservation $reservation)
    {
        // check if the user can delete the selected reservation
        $this->authorize('delete', $reservation);
        
        // the user can't delete a reservation in the past
        if (Carbon::parse($reservation->date) < now()->startOfDay() || Carbon::parse($reservation->date)->addHours(9) < now()) {
            abort(404, 'Vous ne pouvez pas supprimer une réservation passée');
        };

        // delete the reservation
        $reservation->delete();
    }
}
