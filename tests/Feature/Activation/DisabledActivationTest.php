<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Notifications\ActivationNotification;
use Brackets\AdminAuth\Tests\TestBracketsCase;
use Brackets\AdminAuth\Tests\TestBracketsUserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;

class DisabledActivationTest extends TestBracketsCase
{
    use DatabaseMigrations;

    protected $token;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('admin-auth.activations.enabled', false);
        $this->disableExceptionHandling();
        $this->token = '123456aabbcc';
    }

    protected function createTestUser($activated = true, $forbidden = false, $used = false, Carbon $activationCreatedAt = null)
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

        //create also activation
        $this->app['db']->connection()->table('activations')->insert([
            'email' => $user->email,
            'token' => $this->token,
            'used' => $used,
            'created_at' => !is_null($activationCreatedAt) ? $activationCreatedAt : Carbon::now(),
        ]);

        $this->assertDatabaseHas('activations', [
            'email' => 'john@example.com',
            'token' => $this->token,
            'used' => $used,
        ]);

        return $user;
    }

    /** @test */
    public function do_not_send_activation_mail_after_user_created()
    {
        Notification::fake();

        $user = $this->createTestUser(false);

        Notification::assertNotSentTo(
            $user,
            ActivationNotification::class
        );
    }

    /** @test */
    public function do_not_send_activation_mail_form_filled()
    {
        Notification::fake();

        $user = $this->createTestUser(false);

        $response = $this->post(url('/admin/activation/send'), ['email' => 'john@example.com']);
        $response->assertStatus(302);

        Notification::assertNotSentTo(
            $user,
            ActivationNotification::class
        );
    }

    /** @test */
    public function do_not_activate_user_if_activation_disabled()
    {
        $user = $this->createTestUser(false);

        $response = $this->get(route('brackets/admin-auth::admin/activation/activate', ['token' => $this->token]));
        $response->assertStatus(302);

        $userNew = TestBracketsUserModel::where('email', 'john@example.com')->first();
        $this->assertEquals(0, $userNew->activated);

        $this->assertDatabaseHas('activations', [
            'email' => 'john@example.com',
            'token' => $this->token,
            'used' => false,
        ]);
    }
}
