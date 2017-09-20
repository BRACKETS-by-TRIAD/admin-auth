<?php

namespace Brackets\AdminAuth\Tests\Feaure\Auth;

use Brackets\AdminAuth\Tests\TestBracketsCase;
use Brackets\AdminAuth\Tests\TestBracketsUserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;

class LoginBracketsTest extends TestBracketsCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
//        $this->disableExceptionHandling();
    }

    protected function createTestUser($activated = true, $forbidden = false)
    {
        $user = TestBracketsUserModel::create([
            'email' => 'john@example.com',
            'password' => bcrypt('testpass123'),
            'activated' => $activated,
            'forbidden' => $forbidden,
        ]);

        $this->assertDatabaseHas('test_brackets_user_models', [
            'email' => 'john@example.com',
            'activated' => $activated,
            'forbidden' => $forbidden,
        ]);

        return $user;
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
        $user = $this->createTestUser();

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function user_with_wrong_credentials_cannot_log_in()
    {
        $user = $this->createTestUser();

        $response = $this->json('post', '/admin/login', ['email' => 'john@example.com', 'password' => 'testpass1231']);
        $response->assertStatus(422);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function not_activated_user_cannot_log_in()
    {
        $user = $this->createTestUser(false);

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    //Fixme Maybe not???
    public function not_activated_user_can_log_in_if_activation_disabled()
    {
        $user = $this->createTestUser(false);

        $this->app['config']->set('admin-auth.activations.enabled', false);

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function forbidden_user_cannot_log_in()
    {
        $user = $this->createTestUser(true, true);

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function deleted_user_cannot_log_in()
    {
        $time = Carbon::now();
        //Delted at is not fillable, therefore we need to unguard to force fill
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

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);

        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function already_auth_user_is_redirected_from_login()
    {
        $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Brackets\AdminAuth\Http\Middleware\RedirectIfAuthenticated');

        $user = $this->createTestUser();

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->assertNotEmpty(Auth::user());

        $response = $this->post('/admin/login', ['email' => 'john@example.com', 'password' => 'testpass123']);
        $response->assertStatus(302);
        $response->assertRedirect($this->app['config']->get('admin-auth.login_redirect'));
    }
}
