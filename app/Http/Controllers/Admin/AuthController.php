<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AuthenticatesAdminUsers;

use App\Models\User;
use Hash, Auth, Str, Session;

class AuthController extends Controller
{

    use AuthenticatesAdminUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Overwrite default login method to in order to allow user to use old MD5 Hash passwords
     * and migrate it without asking him any change
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // check against old md5 password, if correct, create bcrypted updated pswd
        $user = User::where('username', $request->username)->first();
        
        if( $user && $user->password == md5($request->password))
        {
            $user->password = Hash::make($request->password); //update the password to better hasher accordingly
            $user->save();
        }
    
       

        /*
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }*/

        /** @description - only valid user can login */
        $credentials = $request->only('username', 'password');
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'valid' => 1])) {
            // Authentication passed...
            $user->api_token = Hash('sha256', Str::random(80)); //update api token for old md5 passowrd since older user is having md5 encryption
            $user->save();
            return redirect()->intended('admin/dashboard');         

            //@todo: detect if intended redirect is under admin url prefix;
            
            //return redirect('/admin/dashboard');
        }
        

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}