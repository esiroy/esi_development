<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\UserImage;

use Auth;
use Hash;
use Validator;

class MemberSettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
        
    public function index()
    {
        $member = Auth::user();

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($member); 
        
        
        return view('modules.member.settings.index', compact('member', 'userImage'));
    }


    public function updatePassword(request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'currentPassword' => ['required', 'string', 'min:4|max:32'],
            'newPassword' => ['required', 'string', 'min:4|max:32'],
            'confirmPassword' => 'required|min:4|max:32|same:newPassword',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')->withErrors($validator)->withInput()->with('error_message', 'Sorry, your credentials was invalid, please try to supply your current password correctly');
        } else {
            $currentPassword = Hash::make($request->currentPassword);
            if (Auth::attempt(['username' => $user->username, 'password' => $request->currentPassword, 'valid' => 1])) {
                // The user is active, not suspended, and exists.
                $userData = ['password' => Hash::make($request['newPassword'])];
                $user = $user->update($userData);
                return redirect()->route('settings.index')->with('message', 'Password has been updated successfully');
            } else {
                return redirect()->route('settings.index')->with('error_message', 'Sorry, your credentials was invalid, please try to supply your current password correctly');
            }
        }
    }

    public function update(request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'japanese_firstname' => ['required', 'string'],
            'japanese_lastname' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)->whereNull('deleted_at'),
            ],
        ]);

        if ($validator->fails()) {           
            return redirect()->route('settings.index')->withErrors($validator)->withInput();
        } else {

            $currentPassword = Hash::make($request->currentPassword);
            $userData = [
                'japanese_firstname' => $request->japanese_firstname,
                'japanese_lastname' => $request->japanese_lastname,
                'email' => $request->email,
                'items_per_page' => $request->itemsPerPage
            ];
            $user = $user->update($userData);
            return redirect()->route('settings.index')->with('message', 'User Information has been updated successfully');
        }
    }
    
    
}
