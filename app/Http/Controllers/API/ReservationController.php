<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, ['user_id' => 'exists:users,id|required|numeric']);

        $reservations = Reservation::where('user_id', $request->input('user_id'))
            ->with('slot')
            ->with('slot.company')
            ->get();

        return response()->json($reservations);
    }
}
