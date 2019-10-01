<?php

namespace App\Policies;

use App\User;
use App\Reservation;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the reservation.
     *
     * @param  \App\User  $user
     * @param  \App\Reservation  $reservation
     * @return mixed
     */
    public function view(User $user, Reservation $reservation)
    {
        return $user->id === $reservation->user_id;
    }

    /**
     * Determine whether the user can update the reservation.
     *
     * @param  \App\User  $user
     * @param  \App\Reservation  $reservation
     * @return mixed
     */
    public function create(User $user)
    {
        $requested_date = CarbonImmutable::parse(request()->date);

        $reservations_at_requested_date = Reservation::where('date', request()->date)->get();

        // check if day is full
        if (count($reservations_at_requested_date) === 2) {
            return false;
        }

        // check if the parking requested is not the same as the one already booked
        if (count($reservations_at_requested_date) > 0) {
            if ($reservations_at_requested_date[0]->parking_number === request()->parking_number) {
                return false;
            };
        }

        // check if there is already a reservation this week
        $user_reservations = Reservation::where('user_id', auth()->id())->whereDate('date', '>=', $requested_date->startOfWeek())->whereDate('date', '<=', $requested_date->endOfWeek())->exists();
        if ($user_reservations) {
            $this->deny('Vous avez déjà une réservation cette semaine');
        }

        // check if the reservation is within 7 days
        if ((now()->addDays(7))->lessThanOrEqualTo($requested_date)) {
            $this->deny("Vous ne pouvez pas réserver plus de 7 jours à l'avance");
        }

        return true;
    }

    /**
     * Determine whether the user can delete the reservation.
     *
     * @param  \App\User  $user
     * @param  \App\Reservation  $reservation
     * @return mixed
     */
    public function delete(User $user, Reservation $reservation)
    {
        return $user->id === $reservation->user_id;
    }
}
