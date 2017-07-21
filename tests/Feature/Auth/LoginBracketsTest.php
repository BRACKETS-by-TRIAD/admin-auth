<?php

namespace Brackets\AdminAuth\Tests\Feaure\Auth;

use Brackets\AdminAuth\Tests\TestBracketsCase;
use Brackets\AdminAuth\Tests\TestBracketsUserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginBracketsTest extends TestBracketsCase
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
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => true,
            'forbidden' => false,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => true,
            'forbidden' => false,
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function user_with_wrong_credentials_cannot_log_in()
    {
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => true,
            'forbidden' => false,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => true,
            'forbidden' => false,
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass1231']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function not_activated_user_cannot_log_in()
    {
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => false,
            'forbidden' => false,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => false,
            'forbidden' => false,
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    //Fixme Maybe not???
    public function not_activated_user_can_log_in_if_activation_disabled()
    {
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => false,
            'forbidden' => false,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => false,
            'forbidden' => false,
        ]);

        $this->app['config']->set('admin-auth.activation-required', false);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function forbidden_user_cannot_log_in()
    {
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => true,
            'forbidden' => true,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => true,
            'forbidden' => true,
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function deleted_user_cannot_log_in()
    {
        $time = Carbon::now();
        TestBracketsUserModel::unguard();
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => true,
            'forbidden' => false,
            'deleted_at' => $time,
        ]);
        TestBracketsUserModel::reguard();

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => true,
            'forbidden' => false,
            'deleted_at' => $time,
        ]);

        $response = $this->json('POST', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function already_auth_user_is_redirected_from_login()
    {
        $user = TestBracketsUserModel::create([
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
