<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Reservation;
use App\Models\Slot;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SlotController extends Controller
{
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id|numeric',
            'date_time' => 'required|date',
            'party_size' => 'sometimes|required|numeric|min:1',
        ]);

        $company = Company::findOrFail($request->input('company_id'));
        // $partySize = $request->input('party_size');
        $currentDateTime = $request->input('date_time');

        // $slots = $company->slots()
        //     ->where('date_time', '<', Carbon::parse($currentDateTime)->endOfDay())
        //     ->where('date_time', '>', $currentDateTime)
        //     ->where('capacity', '>=', $partySize)
        //     ->whereDoesntHave('reservation')
        //     ->orderBy('date_time', 'asc');

        $slotsQuery = $company->slots()
            ->where('date_time', '<', Carbon::parse($currentDateTime)->endOfDay())
            ->where('date_time', '>', $currentDateTime)
            ->whereDoesntHave('reservation')
            ->orderBy('date_time', 'asc');

        if ($company->typeIsGroup()) {
            $partySize = $request->input('party_size');
            $slotsQuery->where('capacity', '>=', $partySize);
        }

        // return response()->json($slots->get());
        return response()->json($slotsQuery->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:2|max:255',
            'user_id' => 'required|exists:users,id',
            'slot_id' => 'required|exists:slots,id',
            'party_size' => 'required|numeric|min:1',
            'comment' => 'nullable|string|max:255'
        ]);

        if ($request->input('user_id') != Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        };

        $reservation = Reservation::create([
            'user_id' => Auth::user()->id,
            'slot_id' => $request->input('slot_id'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'comment' => $request->input('comment'),
            'party_size' => $request->input('party_size'),
        ]);

        return response()->json('success');
    }
}
