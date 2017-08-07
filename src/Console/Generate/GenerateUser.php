<?php namespace Brackets\AdminAuth\Console\Generate;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class GenerateUser extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:generate:auth:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold complete admin CRUD for specified user model. This differs from admin:generate command in many additional features (password handling, roles, ...).';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $tableNameArgument = 'users';
        $modelOption = $this->option('model');
        $controllerOption = $this->option('controller');
        $withModelOption = $this->option('withModel');
        $force = $this->option('force');

        if($force) {
            //remove all files
            if($withModelOption) {
                $this->files->delete(app_path('Models/User.php'));
            }
            $this->files->delete(app_path('Http/Controllers/Admin/UsersController.php'));
            $this->files->deleteDirectory(resource_path('assets/js/admin/user'));
            $this->files->deleteDirectory(resource_path('views/admin/user'));

            $this->info('Deleting previous files finished.');
        }

        if($withModelOption) {
            $this->call('admin:generate:model', [
                'table_name' => $tableNameArgument,
                '--model-name' => $modelOption,
                '--template' => 'user',
                '--belongs-to-many' => 'roles',
            ]);

            //TODO change config/auth.php to use our user model for auth
        }

        $this->call('admin:generate:controller', [
            'table_name' => $tableNameArgument,
            'class_name' => $controllerOption,
            '--model-name' => $modelOption,
            '--template' => 'user',
            '--belongs-to-many' => 'roles',
        ]);


        $this->call('admin:generate:routes', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--template' => 'user',
            '--controller-name' => $controllerOption,
        ]);

        $this->call('admin:generate:index', [
            'table_name' => $tableNameArgument,
            '--template' => 'user',
            '--model-name' => $modelOption,
        ]);

        $this->call('admin:generate:form', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--belongs-to-many' => 'roles',
        ]);

        $this->call('admin:generate:factory', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
        ]);

        if ($this->option('seed')) {
            $this->info('Seeding testing data');
            factory($this->modelFullName, 20)->create();
        }

        $this->info('Generating whole admin user finished');

    }

    protected function getArguments() {
        return [
        ];
    }

    protected function getOptions() {
        return [
            // TODO model-name, controller-name, with-model + copy description from other commands
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            // TODO maybe change to generate-model
            ['withModel', 'w', InputOption::VALUE_NONE, 'Generates model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating admin user'],
            ['seed', 's', InputOption::VALUE_NONE, 'Seeds table with fake data'],
        ];
    }

}