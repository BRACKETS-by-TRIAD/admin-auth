<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResetPasswordStandardTest extends TestStandardCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function can_see_reset_password_form()
    {

    }

    /** @test */
    public function reset_password_after_form_filled()
    {

    }

    /** @test */
    public function do_not_reset_password_if_email_not_found()
    {

    }

    /** @test */
    public function do_not_reset_password_if_token_failed()
    {

    }

    /** @test */
    public function do_not_reset_password_if_email_and_token_does_not_match()
    {

    }

    /** @test */
    public function do_not_reset_password_if_password_validation_failed()
    {

    }
}
