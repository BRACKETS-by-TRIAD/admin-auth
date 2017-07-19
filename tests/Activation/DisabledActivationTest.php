<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisabledActivationTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function do_not_send_activation_mail_after_user_created()
    {

    }

    /** @test */
    public function do_not_send_activation_mail_form_filled()
    {

    }

    /** @test */
    public function do_not_activate_user_if_activation_disabled()
    {

    }
}
