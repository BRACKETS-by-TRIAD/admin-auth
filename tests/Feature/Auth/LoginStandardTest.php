<?php

namespace Brackets\AdminAuth\Tests\Feaure\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Brackets\AdminAuth\Tests\TestStandardUserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginStandardTest extends TestStandardCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_log_in()
    {
        $user = TestStandardUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123')
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john@example.com',
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function user_with_wrong_credentials_cannot_log_in()
    {
        $user = TestStandardUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123')
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john@example.com',
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass1231']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function already_auth_user_is_redirected_from_login()
    {
        $user = TestStandardUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123')
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john@example.com',
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/user');

        $this->assertNotEmpty(Auth::user());

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);
        //TODO enable middleware
        $response->assertRedirect('/home');
    }
}
