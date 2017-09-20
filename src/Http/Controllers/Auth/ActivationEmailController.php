<?php

namespace Brackets\AdminAuth\Http\Controllers\Auth;

use Brackets\AdminAuth\Http\Controllers\Controller;
use Brackets\AdminAuth\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class ActivationEmailController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Activation Email Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling activation emails and
    | assists in sending these notifications from
    | your application to your users.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a activation link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        if(config('admin-auth.activations.self_activation_form_enabled')) {
            return view('brackets/admin-auth::admin.auth.activation.email');
        } else {
            abort(404);
        }
    }

    /**
     * Send an activation link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function sendActivationEmail(Request $request)
    {
        if(config('admin-auth.activations.self_activation_form_enabled')) {
            if(!Config::get('admin-auth.activations.enabled')) {
                return $this->sendActivationLinkFailedResponse($request, Activation::ACTIVATION_DISABLED);
            }

            $this->validateEmail($request);

            // We will send the activation link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendActivationLink(
                $this->credentials($request)
            );

            return $this->sendActivationLinkResponse($response);
        } else {
            abort(404);
        }
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful activation link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendActivationLinkResponse($response)
    {
        $message = trans('brackets/admin-auth::admin.activations.sent');
        return back()->with('status', $message);
    }

    /**
     * Get the response for a failed activation link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendActivationLinkFailedResponse(Request $request, $response)
    {
        $message = trans($response);
        if($response == Activation::ACTIVATION_DISABLED) {
            $message = trans('brackets/admin-auth::admin.activations.disabled');
        }
        return back()->withErrors(
            ['email' => $message]
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only('email'), ['activated' => false, 'deleted_at' => null]);
    }

    /**
     * Get the broker to be used during activation.
     *
     * @return \Brackets\AdminAuth\Contracts\Auth\ActivationBroker
     */
    public function broker()
    {
        return Activation::broker();
    }
}
