<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Company $company)
    {
        $this->authorize('manageCompany', $company);

        $users = $company->users()->where('user_id', '!=', $company->user_id)->get();

        return view('companies.users.index', compact('users', 'company'));
    }

    public function create(Company $company)
    {
        $this->authorize('manageCompany', $company);

        return view('companies.users.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $this->authorize('manageCompany', $company);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = $company->users()->create([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach(4); // Employee role
        $user->save();

        return redirect()->route('companies.users.index', $company);
    }

    public function edit(Company $company, $id)
    {
        $this->authorize('manageCompany', $company);

        $user = $company->users()->findOrFail($id);
        return view('companies.users.edit', compact('user', 'company'));
    }

    public function update(Request $request, Company $company, $id)
    {
        $this->authorize('manageCompany', $company);

        $user = $company->users()->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('companies.users.index', $company);
    }

    public function destroy(Company $company, $id)
    {
        $this->authorize('manageCompany', $company);

        $user = $company->users()->findOrFail($id);
        $user->companies()->detach($company->id);
        $user->roles()->detach(4); // Employee role
        $user->delete();

        return redirect()->route('companies.users.index', $company);
    }
}
