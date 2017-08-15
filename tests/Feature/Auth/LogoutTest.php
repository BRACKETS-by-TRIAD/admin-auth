<?php

namespace Brackets\AdminAuth\Tests\Unit;

use Brackets\AdminAuth\Tests\TestStandardUserModel;
use Illuminate\Support\Facades\Auth;
use Brackets\AdminAuth\Tests\TestStandardCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutTest extends TestStandardCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    protected function createTestUser()
    {
        $user = TestStandardUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123')
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john@example.com',
        ]);

        return $user;
    }

    /** @test */
    public function auth_user_can_logout()
    {
        $user = $this->createTestUser();

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());

        $response = $this->get('/admin/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function not_auth_user_cannot_logout()
    {
        $this->assertEmpty(Auth::user());

        $response = $this->get('/admin/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');

        $this->assertEmpty(Auth::user());
    }
}
