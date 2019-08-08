<?php

namespace App\Http\Requests;

use App\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $reservations_at_requested_date = Reservation::where('date', $this->date)->get();

        // check if day is full
        if (count($reservations_at_requested_date) === 2) {
            return false;
        }

        // check if the parking requested is not the same as the one already booked
        if (count($reservations_at_requested_date) > 0) {
            if ($reservations_at_requested_date[0]->parking_number === $this->parking_number) {
                return false;
            };
        }

        // return if there is already a reservation in the next days
        $user_reservations = Reservation::where('user_id', auth()->id())->whereDate('date', '>=', now()->startOfDay())->exists();
        if ($user_reservations) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parking_number' => 'required|integer|max:10',
            'date' => 'required|date|after_or_equal:today|before_or_equal:+ 7 days'
        ];
    }
}
