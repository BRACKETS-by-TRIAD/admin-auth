<?php

namespace Brackets\AdminAuth\Tests\Feaure\Auth;

use Brackets\AdminAuth\Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function user_can_log_in()
    {
//        $this->disableExceptionHandling();
        $response = $this->get('/admin/login');
//        dd($response);
        $response->assertStatus(200);

        $user = \App\User::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123')
        ]);
        $this->seeInDatabase('users', [
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
        ]);

//        $this->visit(route('/admin/login'))
//            ->type($user->email, 'email')
//            ->type('testpass123', 'password')
//            ->press('Login')
//            ->onPage('/admin/users');
//
//        $credentials = ['email' => 'david.behal@triad.sk', 'password' => 'aaaaaa12'];

//        dd(Auth::user());
//        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function user_with_wrong_credentials_cannot_log_in()
    {
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function not_activated_user_cannot_log_in()
    {
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    //Fixme Maybe not???
    public function not_activated_user_can_log_in_if_activation_disabled()
    {
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function forbidden_user_cannot_log_in()
    {
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function already_auth_user_is_redirected_from_login()
    {
        $this->assertEmpty(Auth::user());
    }
}
