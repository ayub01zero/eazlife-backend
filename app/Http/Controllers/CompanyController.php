<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\UploadInventoryRequest;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\CompanyType;
use App\Models\User;
use App\Notifications\OrderSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company = null)
    {
        $currentCompany = $company;
        $user = Auth::user();

        if ($currentCompany === null && $user->companies()->where('is_approved', true)->count() > 0) {
            $currentCompany = $user->companies()->where('is_approved', true)->first();
        }

        $this->authorize('manageCompany', $currentCompany);

        $editDuplicateIds = DB::table('companies')
            ->whereNotNull('edit_duplicate_id')
            ->pluck('edit_duplicate_id');

        $companies = $user->companies()->where('is_approved', true)->with('openingTimes', 'fulfillmentTypes')->get();

        $fulfillmentTypes = [];
        foreach ($companies as $company) {
            foreach ($company->fulfillmentTypes as $fulfillmentType) {
                $fulfillmentTypes[$company->id][] = $fulfillmentType->name;
            }
        }

        foreach ($companies as $company) {
            $company->openingTimes = $company->openingTimes->keyBy('day');
            if (isset($fulfillmentTypes[$company->id])) {
                $company->fulfillmentTypes = $fulfillmentTypes[$company->id];
            } else {
                $company->fulfillmentTypes = [];
            }
        }

        $totalRevenue = 0;

        foreach ($currentCompany->revenue as $revenue) {
            $totalRevenue += $revenue->sum;
        }

        $currentCompany->totalRevenue = $totalRevenue;

        return view('dashboard', ['companies' => $companies, 'company' => $currentCompany]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = CompanyType::with('categories')->with('fulfillment_types')->get();

        foreach ($types as $type) {
            foreach ($type->fulfillment_types as $fulfillmentType) {
                $fulfillmentType->name = __($fulfillmentType->name);
            }
        }

        return view('companies.create', ['types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $request['user_id'] = Auth::user()->id;

        $company = new Company($request->all());
        $company->is_approved = false;
        $company->save();

        $company->users()->attach(Auth::user());
        $company->categories()->attach($request->company_categories);
        $company->fulfillmentTypes()->attach($request->company_fulfillment_types);

        return Redirect::route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $this->authorize('manageCompany', $company);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $this->authorize('manageCompany', $company);

        // Load duplicated company for when the company is edited and waiting for approval
        $duplicatedCompany = Company::find($company->edit_duplicate_id);

        $company->load('categories');
        $company->load('fulfillmentTypes');

        $types = CompanyType::with('categories')->with('fulfillment_types')->get();

        foreach ($types as $type) {
            foreach ($type->fulfillment_types as $fulfillmentType) {
                $fulfillmentType->name = __($fulfillmentType->name);
            }
        }

        return view('companies.edit', ['company' => $company, 'duplicatedCompany' => $duplicatedCompany, 'types' => $types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $duplicateCompany = Company::where('id', $company->edit_duplicate_id)->first();

        if ($duplicateCompany) {
            $duplicateCompany->fill($request->all());
            $duplicateCompany->setLocation();
            $duplicateCompany->save();
        } else {
            $editedCompany = $company->replicate();
            $editedCompany->fill($request->all());
            $editedCompany->edit_duplicate_id = null;
            $editedCompany->setLocation();
            $editedCompany->save();

            $company->edit_duplicate_id = $editedCompany->id;
            $company->save();
        }

        return redirect()->back();
        // return Redirect::route('dashboard')->with('status', 'Your changes have been submitted for approval.');
    }

    public function updateLogo(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);
    
        $logo = $request->file('logo');
    
        if ($logo) {
            $logoPng = Image::make($logo)->fit(200, 200)->encode('png');
            $path = 'logos/' . $company->id . '-' . time() . '.png';
            $store = Storage::put($path, $logoPng, 'public');
            if ($store) {
                // Generate the full URL to the stored file
                $url = Storage::url($path);
                $company->logo_path = $url; // Save the full URL instead of the relative path
                $company->save();
            } else {
                // Handle failure...
            }
        }
    
        return redirect()->back();
    }

    public function updateBanner(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $banner = $request->file('banner');

        if ($banner) {
            $bannerPng = Image::make($banner)->fit(1200, 500)->encode('png');
            $path = 'banners/' . $company->id . '-' . time() . '.png';
            $store = Storage::put($path, $bannerPng, 'public');
            if ($store) {
                $url = Storage::url($path);
                $company->banner_path = $url;
                $company->save();
            } else {
                // Handle failure...
            }
        }

        return redirect()->back();
    }

    public function deleteLogo(Company $company)
    {
        $this->authorize('manageCompany', $company);

        $company->logo_path = null;
        $company->save();

        return redirect()->back();
    }

    public function deleteBanner(Company $company)
    {
        $this->authorize('manageCompany', $company);

        $company->banner_path = null;
        $company->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->authorize('manageCompany', $company);
        //
    }

    public function uploadInventory(Company $company, UploadInventoryRequest $request)
    {
        if ($request->hasFile('inventory_file')) {
            $file = $request->file('inventory_file');

            $path = 'inventories/' . $company->id . '-' . md5(time()) . '.pdf';
            $store = Storage::put($path, file_get_contents($file), 'public');
            if ($store) {
                $url = $path;
                $company->inventory_path = $url;
                $company->save();
            } else {
                // Handle failure...
            }

            return redirect()->back()->with('success', 'Inventory uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Error uploading inventory file!');
    }

    public function downloadInventory(Company $company)
    {
        $filePath = $company->inventory_path;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return 'File not found';
        }
    }

    public function updateOpeningTimes(Request $request, Company $company)
    {
        // dd($request->all());
        $this->authorize('manageCompany', $company);

        $company->openingTimes()->delete();

        $input = $request->all();

        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

        foreach ($days as $k => $day) {
            $company->openingTimes()->create([
                'day' => $k + 1,
                'opening_time' => isset($input[$day . '_open']) ? $input[$day . '_open'] : null,
                'closing_time' => isset($input[$day . '_close']) ? $input[$day . '_close'] : null,
            ]);
        }

        return redirect()->back()->with('success', 'Opening times saved successfully!');
    }

    public function updateStatus(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        if ($company->name == null || $company->address == null || $company->city == null || $company->zip_code == null || $company->country == null || $company->state == null || $company->location == null || $company->logo_path == null || $company->banner_path == null || $company->inventory_path == null || $company->radius == null) {
            return redirect()->back()->with('error', 'Data is missing to set your company online!');
        }

        $company->online = ($request->input('online') === '1');
        $company->save();

        return redirect()->back();
    }
}