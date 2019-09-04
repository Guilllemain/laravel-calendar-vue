<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Reservation;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();

        return view('admin.reservations.index', compact('reservations'));    
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back();
    }

    public function edit(Reservation $reservation)
    {
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        // TODO
        
        // $reservation->update([
        //     'firstname' => $request->firstname,
        //     'lastname' => $request->lastname,
        //     'email' => $request->email,
        //     'email_verified_at' => $request->verified
        // ]);

        return redirect('/admin');
    }
}
