<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivationTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function activate_user_if_token_is_ok()
    {

    }

    /** @test */
    public function do_not_activate_user_if_token_does_not_exists()
    {

    }

    /** @test */
    public function do_not_activate_user_if_token_used()
    {

    }

    /** @test */
    public function do_not_activate_user_if_token_expired()
    {

    }
}
