<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Reservation;
use App\Models\UserNotification;
use App\Notifications\ReservationCanceled;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function show($id)
    {
        $reservation = Reservation::with('slot.company')->findOrFail($id);

        $this->authorize('manageCompany', $reservation->slot->company);

        return view('reservations.show', [
            'reservation' => $reservation
        ]);
    }

    public function cancel(Request $request)
    {
        $validatedData = $request->validate([
            'reservation_id' => 'required|integer'
        ]);

        $reservation = Reservation::with('slot.company')->find($validatedData['reservation_id']);

        $this->authorize('manageCompany', $reservation->slot->company);

        if ($reservation) {
            $reservation->user->notify(new ReservationCanceled());
            $reservation->canceled_at = Carbon::now();
            $reservation->save();
        }

        $company = Company::find($reservation->slot->company_id);

        UserNotification::create([
            'user_id' => $reservation->user_id,
            'title' => 'Your reservation at ' . $company->name . ' has been canceled.',
            'body' => 'Update',
            'notifiable_id' => $reservation->id,
            'notifiable_type' => Reservation::class,
        ]);

        return redirect()->back();
    }
}
