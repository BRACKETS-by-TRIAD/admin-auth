<?php namespace Brackets\AdminAuth\Console\Generate;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class GenerateProfile extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin-auth:generate-profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold admin profile';

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
        $force = $this->option('force');

        if($force) {
            //remove all files
//            $this->files->delete(app_path('Http/Controllers/Admin/ProfileController.php'));
//            $this->files->deleteDirectory(resource_path('assets/js/admin/profile'));
//            $this->files->deleteDirectory(resource_path('views/admin/profile'));
        }

//        $this->call('admin:generate:controller', [
//            'table_name' => $tableNameArgument,
//            '--model' => $modelOption,
//            '--controller' => 'ProfileController',
//            '--template' => 'profile',
//        ]);


        $this->call('admin:generate:routes', [
            'table_name' => $tableNameArgument,
            '--model' => $modelOption,
            '--controller' => 'ProfileController',
            '--template' => 'profile',
        ]);

//        $this->call('admin:generate:form', [
//            'table_name' => $tableNameArgument,
//            '--model' => $modelOption,
//            '--template' => 'profile',
//        ]);
//
//        $this->info('Generating whole admin profile finished');

    }

    protected function getArguments() {
        return [
        ];
    }

    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating admin profile'],
            ['seed', 's', InputOption::VALUE_NONE, 'Seeds table with fake data'],
        ];
    }

}