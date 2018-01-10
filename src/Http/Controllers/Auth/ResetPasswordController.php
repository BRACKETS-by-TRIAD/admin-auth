<?php

namespace Brackets\AdminAuth\Http\Controllers\Auth;

use Brackets\AdminAuth\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = Config::get('admin-auth.password_reset_redirect');
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('brackets/admin-auth::admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        if($this->loginCheck($user)) {
            $this->guard()->login($user);
        }
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        $message = trans($response);
        if($response == Password::PASSWORD_RESET) {
            $message = trans('brackets/admin-auth::admin.passwords.reset');
        }
        return redirect($this->redirectPath())
            ->with('status', $message);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $message = trans($response);
        if($response == Password::INVALID_TOKEN) {
            $message = trans('brackets/admin-auth::admin.passwords.invalid_token');
        } else if($response == Password::INVALID_USER) {
            $message = trans('brackets/admin-auth::admin.passwords.invalid_user');
        } else if($response == Password::INVALID_PASSWORD) {
            $message = trans('brackets/admin-auth::admin.passwords.invalid_password');
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|string',
            'password' => 'required|confirmed|min:7|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/|string',
        ];
    }

    /**
     * Check if provided user can be logged in
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return boolean
     */
    protected function loginCheck($user) {
        return (!property_exists($user, 'activated') || $user->activated) && (!property_exists($user, 'forbidden') || !$user->forbidden);
    }
}
