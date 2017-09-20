<?php

namespace Brackets\AdminAuth\Tests\Auth;

use Brackets\AdminAuth\Tests\TestBracketsCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisabledActivationFormTest extends TestBracketsCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('admin-auth.activations.self_activation_form_enabled', false);
        $this->app['config']->set('admin-auth.activations.enabled', true);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function can_not_see_activation_form_if_disabled()
    {
        $response = $this->get(url('/admin/activation'));
        $response->assertStatus(404);
    }
}
