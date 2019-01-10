<?php

namespace Brackets\AdminAuth\Tests;

use Brackets\AdminAuth\Tests\Exceptions\Handler;
use Brackets\AdminAuth\Tests\Models\TestBracketsUserModel;
use Brackets\AdminAuth\Tests\Models\TestStandardUserModel;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected $adminAuthGuard;

    public function setUp()
    {
        parent::setUp();
        $this->getEnvironmentSetUp($this->app);
        $this->setUpDatabase($this->app);

        File::copyDirectory(__DIR__ . '/fixtures/resources/views', resource_path('views'));
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
        if (env('DB_CONNECTION') === 'pgsql') {
            $app['config']->set('database.default', 'pgsql');
            $app['config']->set('database.connections.pgsql', [
                'driver' => 'pgsql',
                'host' => 'testing',
                'port' => '5432',
                'database' => 'homestead',
                'username' => 'homestead',
                'password' => 'secret',
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]);
        } else {
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        }

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        //Set admin guard
        $app['config']->set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'admin_users',
        ]);

        //Set admin_users provider
        $app['config']->set('auth.providers.admin_users', [
            'driver' => 'eloquent',
            'model' => TestBracketsUserModel::class,
        ]);

        //Set admin_users passwords
        $app['config']->set('auth.passwords.admin_users', [
            'provider' => 'admin_users',
            'table' => 'admin_password_resets',
            'expire' => 60,
        ]);

        //Set test user model as auth provider
        $app['config']->set('auth.providers.users.model', TestStandardUserModel::class);

        //Sets the forbidden check
        $app['config']->set('admin-auth.check_forbidden', true);

        //Sets the activation check
        $app['config']->set('admin-auth.activation_enabled', true);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_standard_user_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_brackets_user_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->boolean('activated')->default(false);
            $table->boolean('forbidden')->default(false);
            $table->string('language', 2)->default('en');
            $table->softDeletes('deleted_at');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        $app['db']->connection()->getSchemaBuilder()->create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $app['db']->connection()->getSchemaBuilder()->create('admin_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $app['db']->connection()->getSchemaBuilder()->create('admin_activations', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->boolean('used')->default(false);
            $table->timestamp('created_at')->nullable();
        });

//        $this->artisan('migrate');
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
            }

            public function report(Exception $e)
            {
                // no-op
            }

            public function render($request, Exception $e)
            {
                throw $e;
            }
        });
    }
}
