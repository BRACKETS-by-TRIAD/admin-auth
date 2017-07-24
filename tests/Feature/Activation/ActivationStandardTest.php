<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivationStandardTest extends TestStandardCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    /** @test */
    public function activate_user_if_token_is_ok()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_activate_user_if_token_does_not_exists()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_activate_user_if_token_used()
    {
        //TODO finish
    }

    /** @test */
    public function do_not_activate_user_if_token_expired()
    {
        //TODO finish
    }
}
