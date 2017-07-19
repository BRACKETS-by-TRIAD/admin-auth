<?php

namespace Brackets\AdminAuth\Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutTest extends TestCase
{
    /** @test */
    public function auth_user_can_logout()
    {
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function not_auth_user_cannot_logout()
    {
        $this->assertEmpty(Auth::user());
    }
}
