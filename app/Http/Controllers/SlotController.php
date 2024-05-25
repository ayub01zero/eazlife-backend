<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Slot;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $slots = Slot::whereCompanyId($company->id)->get();

        return view('slots.index', ['company' => $company, 'slots' => $slots]);
    }

    public function store(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $request->validate([
            'date_time' => 'required|date',
            'duration' => 'required|numeric|min:1',
            'capacity' => $company->typeIsGroup() ? 'required|numeric|min:1' : 'nullable|numeric|min:1',
        ]);

        $dateTime = Carbon::parse($request->date_time);
        $duration = $request->duration;
        $capacity = $request->capacity;

        $slot = new Slot();
        $slot->company_id = $company->id;
        $slot->date_time = $dateTime;
        $slot->duration = $duration;
        $slot->capacity = $capacity;
        $slot->save();

        return redirect()->back();
    }

    public function createForWeek(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $request->validate([
            'week' => 'required|regex:/^[0-9]{4}-W[0-9]{2}$/',
            'duration' => 'required|numeric|min:1',
            'capacity' => $company->typeIsGroup() ? 'required|numeric|min:1' : 'nullable|numeric|min:1',
        ]);

        $startDate = Carbon::parse($request->week)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dayOfWeek = $date->format('N'); // Monday = 1, Sunday = 7

            $openingTime = $company->openingTimes()->where('day', $dayOfWeek)->value('opening_time');
            $closingTime = $company->openingTimes()->where('day', $dayOfWeek)->value('closing_time');

            // Check if the given time falls within the opening hours
            if ($openingTime && $closingTime) {
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $openingTime);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $closingTime);

                // Iterate over the hours and minutes within the opening and closing times
                for ($time = $startTime; $time->lt($endTime); $time->addMinutes($request->duration)) {
                    $dateTime = $date->format('Y-m-d') . ' ' . $time->format('H:i:s');
                    $slot = new Slot([
                        'date_time' => new DateTime($dateTime),
                        'duration' => $request->duration,
                        'capacity' => $request->capacity,
                    ]);
                    $company->slots()->save($slot);
                }
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request, Company $company, Slot $slot)
    {
        $this->authorize('manageCompany', $company);

        return view('slots.edit', ['company' => $company, 'slot' => $slot]);
    }

    public function update(Request $request, Company $company, Slot $slot)
    {
        $this->authorize('manageCompany', $company);

        $request->validate([
            'date_time' => 'required|date',
            'duration' => 'required|numeric|min:1',
            'capacity' => 'required|numeric|min:1',
        ]);

        // $request->validate([
        //     'date_time' => 'required|date',
        //     'duration' => 'required|numeric|min:1',
        //     'capacity' => $company->typeIsGroup() ? 'required|numeric|min:1' : 'nullable|numeric|min:1',
        // ]);


        if ($slot->isReserved()) {
            return redirect()->back()->withErrors(['Slot is reserved, cannot edit reserved slots']);
        }

        $dateTime = Carbon::parse($request->date_time);
        $duration = $request->duration;
        $capacity = $request->capacity;

        $slot->date_time = $dateTime;
        $slot->duration = $duration;
        $slot->capacity = $capacity;
        $slot->save();

        return redirect()->route('companies.slots.index', ['company' => $company]);
    }

    public function destroy(Request $request, Company $company, Slot $slot)
    {
        $this->authorize('manageCompany', $company);

        $slot->delete();

        return redirect()->back();
    }
}
