<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.modules.settings.index', compact('user'));
    }

    public function updatePassword(request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'currentPassword' => ['required', 'string', 'min:8'],
            'newPassword' => ['required', 'string', 'min:8'],
            'confirmPassword' => 'required|min:8|same:newPassword',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.settings.index')->withErrors($validator)->withInput();
        } else {
            $currentPassword = Hash::make($request->currentPassword);
            if (Auth::attempt(['username' => $user->username, 'password' => $request->currentPassword, 'valid' => 1])) {
                // The user is active, not suspended, and exists.
                $userData = ['password' => Hash::make($request['newPassword'])];
                $user = $user->update($userData);
                return redirect()->route('admin.settings.index')->with('message', 'Password has been updated successfully');
            } else {
                return redirect()->route('admin.settings.index')->with('error_message', 'Sorry, your credentials was invalid, please try to supply your current password correctly');
            }
        }
    }

    public function update(request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],            
            'japanese_firstname' => ['required', 'string'],
            'japanese_lastname' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.settings.index')->withErrors($validator)->withInput();
        } else {

            $currentPassword = Hash::make($request->currentPassword);

            $userData = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,                
                'japanese_firstname' => $request->japanese_firstname,
                'japanese_lastname' => $request->japanese_lastname,
                'email' => $request->email,
                'items_per_page' => $request->itemsPerPage
            ];

            $user = $user->update($userData);
            return redirect()->route('admin.settings.index')->with('message', 'User Information has been updated successfully');
        }
    }
}
