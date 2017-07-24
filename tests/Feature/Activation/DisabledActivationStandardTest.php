<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisabledActivationStandardTest extends TestStandardCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    /** @test */
    public function do_not_send_activation_mail_after_user_created()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_send_activation_mail_form_filled()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_activate_user_if_activation_disabled()
    {
        //TODO finish
    }
}
