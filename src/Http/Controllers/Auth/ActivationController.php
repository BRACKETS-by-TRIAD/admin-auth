<?php

namespace Brackets\AdminAuth\Http\Controllers\Auth;

use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;

class ActivationController extends Controller
{

    use RedirectsUsers;

    /*
    |--------------------------------------------------------------------------
    | Activation Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling activation requests.
    |
    */

    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * Where to redirect users after activating their accounts.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Activation broker used for admin user
     *
     * @var string
     */
    protected $activationBroker = 'admin_users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = config('admin-auth.defaults.guard');
        $this->activationBroker = config('admin-auth.defaults.activations');
        $this->redirectTo = config('admin-auth.activation_redirect');
        $this->middleware('guest.admin:' . $this->guard);
    }

    /**
     * Activate user from token
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Request $request, $token)
    {
        if (!config('admin-auth.activation_enabled')) {
            return $this->sendActivationFailedResponse($request, Activation::ACTIVATION_DISABLED);
        }

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to activate the user's account. If it is successful we
        // will update the activation flag on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->activate(
            $this->credentials($request, $token), function ($user) {
            $this->activateUser($user);
        }
        );

        // If the activation was successful, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Activation::ACTIVATED
            ? $this->sendActivationResponse($request, $response)
            : $this->sendActivationFailedResponse($request, $response);
    }

    /**
     * Get the activation validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [];
    }

    /**
     * Get the activation validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Get the activation credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $token
     * @return array
     */
    protected function credentials(Request $request, $token)
    {
        return ['token' => $token];
    }

    /**
     * Activate the given user account.
     *
     * @param  \Brackets\AdminAuth\Contracts\Auth\CanActivate $user
     * @return void
     */
    protected function activateUser($user)
    {
        $user->forceFill([
            'activated' => true,
        ])->save();
    }

    /**
     * Get the response for a successful activation.
     *
     * @param Request $request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendActivationResponse(Request $request, $response)
    {
        $message = trans($response);
        if ($response == Activation::ACTIVATED) {
            $message = trans('brackets/admin-auth::admin.activations.activated');
        }
        return redirect($this->redirectPath())
            ->with('status', $message);
    }

    /**
     * Get the response for a failed activation.
     *
     * @param  \Illuminate\Http\Request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendActivationFailedResponse(Request $request, $response)
    {
        $message = trans($response);
        if ($response == Activation::INVALID_USER || $response == Activation::INVALID_TOKEN) {
            $message = trans('brackets/admin-auth::admin.activations.invalid_request');
        } else {
            if (Activation::ACTIVATION_DISABLED) {
                $message = trans('brackets/admin-auth::admin.activations.disabled');
            }
        }
        if (config('admin-auth.self_activation_form_enabled')) {
            return redirect(route('brackets/admin-auth::admin/activation'))
                ->withInput($request->only('email'))
                ->withErrors(['token' => $message]);
        } else {
            return view('brackets/admin-auth::admin.auth.activation.error')->withErrors(
                ['token' => $message]
            );
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Activation::broker($this->activationBroker);
    }
}
