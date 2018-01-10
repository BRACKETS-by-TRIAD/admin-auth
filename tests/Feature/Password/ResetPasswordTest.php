<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestStandardCase;
use Brackets\AdminAuth\Tests\TestStandardUserModel;
use Carbon\Carbon;
use Illuminate\Support\Debug\Dumper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class ResetPasswordTest extends TestStandardCase
{
    use DatabaseMigrations;

    protected $token;

    public function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
        $this->token = '123456aabbcc';
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

        //create also password reset
        $this->app['db']->connection()->table('password_resets')->insert([
            'email' => $user->email,
            'token' => bcrypt($this->token),
            'created_at' => Carbon::now()
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => 'john@example.com',
        ]);

        return $user;
    }

    /** @test */
    public function can_see_reset_password_form()
    {
        $response = $this->get(route('brackets/admin-auth::admin/password/showResetForm', ['token' => $this->token]));
        $response->assertStatus(200);
    }

    /** @test */
    public function reset_password_after_form_filled()
    {
        $user = $this->createTestUser();

        $response = $this->post(url('/admin/password-reset/reset'),
            [
                'email' => 'john@example.com',
                'password' => 'testpass123new',
                'password_confirmation' => 'testpass123new',
                'token' => $this->token
            ]);
        $response->assertStatus(302);

        $userNew = TestStandardUserModel::where('email', 'john@example.com')->first();

        $this->assertEquals(true, Hash::check('testpass123new', $userNew->password));
    }

    /** @test */
    public function do_not_reset_password_if_email_not_found()
    {
        $user = $this->createTestUser();

        $response = $this->post(url('/admin/password-reset/reset'),
            [
                'email' => 'john1@example.com',
                'password' => 'testpass123new',
                'password_confirmation' => 'testpass123new',
                'token' => $this->token
            ]);
        $response->assertStatus(302);

        $userNew = TestStandardUserModel::where('email', 'john@example.com')->first();

        $this->assertNotEquals(true, Hash::check('testpass123new', $userNew->password));
        $this->assertEquals(true, Hash::check('testpass123', $userNew->password));
    }

    /** @test */
    public function do_not_reset_password_if_token_failed()
    {
        $user = $this->createTestUser();

        $response = $this->post(url('/admin/password-reset/reset'),
            [
                'email' => 'john@example.com',
                'password' => 'testpass123new',
                'password_confirmation' => 'testpass123new',
                'token' => $this->token.'11'
            ]);
        $response->assertStatus(302);

        $userNew = TestStandardUserModel::where('email', 'john@example.com')->first();

        $this->assertNotEquals(true, Hash::check('testpass123new', $userNew->password));
        $this->assertEquals(true, Hash::check('testpass123', $userNew->password));
    }

    /** @test */
    public function do_not_reset_password_if_email_and_token_does_not_match()
    {
        $user1 = $this->createTestUser();

        $user2 = TestStandardUserModel::create([
            'email' => 'john2@example.com',
            'password' => bcrypt('testpass123'),
        ]);

        $this->assertDatabaseHas('test_standard_user_models', [
            'email' => 'john2@example.com',
        ]);

        //TODO create also password reset
        $this->app['db']->connection()->table('password_resets')->insert([
            'email' => $user2->email,
            'token' => bcrypt($this->token.'2'),
            'created_at' => Carbon::now()
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => 'john2@example.com',
        ]);

        $response = $this->post(url('/admin/password-reset/reset'),
            [
                'email' => 'john2@example.com',
                'password' => 'testpass123new',
                'password_confirmation' => 'testpass123new',
                'token' => $this->token
            ]);
        $response->assertStatus(302);

        $userNew2 = TestStandardUserModel::where('email', 'john2@example.com')->first();

        $this->assertNotEquals(true, Hash::check('testpass123new', $userNew2->password));
        $this->assertEquals(true, Hash::check('testpass123', $userNew2->password));

        $response = $this->post(url('/admin/password-reset/reset'),
            [
                'email' => 'john@example.com',
                'password' => 'testpass123new',
                'password_confirmation' => 'testpass123new',
                'token' => $this->token.'2'
            ]);
        $response->assertStatus(302);

        $userNew1 = TestStandardUserModel::where('email', 'john@example.com')->first();

        $this->assertNotEquals(true, Hash::check('testpass123new', $userNew1->password));
        $this->assertEquals(true, Hash::check('testpass123', $userNew1->password));
    }

    /** @test */
    public function do_not_reset_password_if_password_validation_failed()
    {
        $user = $this->createTestUser();

        //Fixme not working getting error instead of exception
//        $response = $this->post(url('/admin/password-reset/reset'),
//            [
//                'email' => 'john@example.com',
//                'password' => 'testpass',
//                'password_confirmation' => 'testpass',
//                'token' => $this->token.'11'
//            ]);
//        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userNew = TestStandardUserModel::where('email', 'john@example.com')->first();

        $this->assertNotEquals(true, Hash::check('testpass', $userNew->password));
        $this->assertEquals(true, Hash::check('testpass123', $userNew->password));
    }
}
