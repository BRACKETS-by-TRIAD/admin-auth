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
    protected $name = 'admin-auth:generate-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold complete CRUD admin user interface';

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
        $withModelOption = $this->option('withModel');
        $force = $this->option('force');

        if($force) {
            //remove all files
            $this->files->delete();
        }

        if($withModelOption) {
            $this->call('admin:generate:model', [
                'table_name' => $tableNameArgument,
            ]);
        }

        $this->call('admin:generate:controller', [
            'table_name' => $tableNameArgument,
        ]);


        $this->call('admin:generate:routes', [
            'table_name' => $tableNameArgument,
        ]);

        $this->call('admin:generate:index', [
            'table_name' => $tableNameArgument,
        ]);

        $this->call('admin:generate:form', [
            'table_name' => $tableNameArgument,
        ]);

        $this->call('admin:generate:factory', [
            'table_name' => $tableNameArgument,
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
            ['withModel', 'm', InputOption::VALUE_OPTIONAL, 'Specify if generating also model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating admin user'],
        ];
    }

}