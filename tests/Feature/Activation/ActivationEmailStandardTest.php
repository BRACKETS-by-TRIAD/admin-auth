<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivationEmailStandardTest extends TestStandardCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    /** @test */
    public function can_see_activation_form()
    {
        //TODO finish
    }

    /** @test */
    public function send_activation_email_after_user_created()
    {
        //TODO finish
    }

    /** @test */
    public function send_activation_email_after_user_not_activated_and_form_filled()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_send_activation_email_if_email_not_found()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_send_activation_email_if_user_already_activated()
    {
        //TODO finish
    }
}
