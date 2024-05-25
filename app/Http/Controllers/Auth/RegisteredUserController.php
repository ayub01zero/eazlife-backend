<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserApproved;
use App\Mail\UserRegistered;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\CompanyType;
use App\Models\User;
use App\Notifications\Registered;
use App\Notifications\SetPassword;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $types = CompanyType::with('categories')->with('fulfillment_types')->get();

        foreach ($types as $type) {
            foreach ($type->fulfillment_types as $fulfillmentType) {
                $fulfillmentType->name = __($fulfillmentType->name);
            }
        }

        return view('auth.register', ['types' => $types]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            // 'address' => ['required', 'string', 'max:255', Rule::unique(Company::class)],
            // 'country' => ['required', 'string', 'max:255'],
            // 'city' => ['required', 'string', 'max:255'],
            // 'state' => ['required', 'string', 'max:255'],
            // 'zip_code' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:255'],
            'company_type_id' => ['required', 'exists:company_types,id'],
            'company_categories' => ['required', 'exists:company_categories,id', 'array'],
            'company_fulfillment_types' => ['exists:fulfillment_types,id', 'array'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'approved' => false,
            'password' => NULL,
        ]);

        $user->roles()->attach(3); // Owner role
        $user->save();

        $request['user_id'] = $user->id;
        $request['name'] = $request['company_name'];

        $company = Company::create($request->all());

        $company->users()->attach($user->id);
        $company->categories()->attach($request->company_categories);
        $company->fulfillmentTypes()->attach($request->company_fulfillment_types);

        if (Auth::user() && (Auth::user()->isAdmin())) {
            $user->approved = true;
            $user->save();
            $company->is_approved = true;
            $company->save();
        } else {
            Mail::to($user->email)->send(new UserRegistered($user));
        }

        return redirect()->route('register')->with('status', 'Registration succesful. You will be informed when your account is approved.');
    }
}
