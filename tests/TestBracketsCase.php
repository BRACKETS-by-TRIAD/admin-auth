<?php

namespace Brackets\AdminAuth\Tests;

use Brackets\AdminAuth\Tests\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Traits\CreatesApplication;
use Illuminate\Support\Facades\File;

abstract class TestBracketsCase extends Orchestra
{
    use CreatesApplication;

    protected $sendNotification;

    public function setUp()
    {
        parent::setUp();
        $this->getEnvironmentSetUp($this->app);
        $this->setUpDatabase($this->app);
        $this->sendNotification = false;

        File::copyDirectory(__DIR__.'/fixtures/resources/views', resource_path('views'));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Brackets\AdminAuth\AdminAuthServiceProvider::class,
            \Brackets\AdminUI\AdminUIServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        //Set test user model as auth provider
        $app['config']->set('auth.providers.users.model', TestBracketsUserModel::class);

        //Sets the forbidden check
        $app['config']->set('admin-auth.check_forbidden', true);

        //Sets the activation check
        $app['config']->set('admin-auth.activations.enabled', true);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_brackets_user_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('activated')->default(false);
            $table->boolean('forbidden')->default(false);
            $table->softDeletes('deleted_at');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
//        $this->artisan('migrate');
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}

            public function report(Exception $e)
            {
                // no-op
            }

            public function render($request, Exception $e) {
                throw $e;
            }
        });
    }
}
