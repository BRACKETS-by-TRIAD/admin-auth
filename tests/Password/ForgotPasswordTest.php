<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ForgotPasswordTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function can_see_forgot_password_form()
    {

    }

    /** @test */
    public function send_forgot_password_email_after_user_created()
    {

    }

    /** @test */
    public function send_forgot_password_email_after_form_filled()
    {

    }

    /** @test */
    public function do_not_send_activation_email_if_email_not_found()
    {

    }
}
