<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivationEmailTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function can_see_activation_form()
    {

    }

    /** @test */
    public function send_activation_email_after_user_created()
    {

    }

    /** @test */
    public function send_activation_email_after_user_not_activated_and_form_filled()
    {

    }

    /** @test */
    public function do_not_send_activation_email_if_email_not_found()
    {

    }

    /** @test */
    public function do_not_send_activation_email_if_user_already_activated()
    {

    }
}
