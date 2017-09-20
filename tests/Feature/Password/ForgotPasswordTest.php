<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Notifications\ResetPassword;
use Brackets\AdminAuth\Tests\TestStandardCase;
use Brackets\AdminAuth\Tests\TestStandardUserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;

class ForgotPasswordTest extends TestStandardCase
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
            'password' => bcrypt('testpass123'),
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john@example.com',
        ]);

        return $user;
    }

    /** @test */
    public function can_see_forgot_password_form()
    {
        $response = $this->get(url('/admin/password-reset'));
        $response->assertStatus(200);
    }

    /** @test */
    public function send_forgot_password_email_after_form_filled()
    {
        Notification::fake();

        $user = $this->createTestUser();

        $response = $this->post(url('/admin/password-reset/send'), ['email' => 'john@example.com']);
        $response->assertStatus(302);

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }

    /** @test */
    public function do_not_send_password_email_if_email_not_found()
    {
        Notification::fake();

        $user = $this->createTestUser();

        $response = $this->post(url('/admin/password-reset/send'), ['email' => 'john1@example.com']);
        $response->assertStatus(302);

        Notification::assertNotSentTo(
            $user,
            ResetPassword::class
        );
    }
}
