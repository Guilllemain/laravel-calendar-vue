<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\Reservation as ReservationResource;
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

        // delete the reservation
        $reservation->delete();
    }

    public function canMakeAReservation()
    {
        $this->authorize('create', Reservation::class);

        return response()->json([
            'authorized' => true
        ]);
    }
}
