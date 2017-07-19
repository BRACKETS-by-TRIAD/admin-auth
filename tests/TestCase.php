<?php

namespace Brackets\AdminAuth\Tests;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Traits\CreatesApplication;

abstract class TestCase extends Orchestra
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();
        $this->getEnvironmentSetUp($this->app);
        $this->setUpDatabase($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Brackets\AdminAuth\Providers\AdminAuthProvider::class,
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
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
//        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('name');
//            $table->integer('width')->nullable();
//        });
        $this->artisan('migrate');
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
