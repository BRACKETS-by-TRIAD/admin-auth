<?php namespace Brackets\AdminAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AdminAuthInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin-auth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a brackets/admin-auth package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Installing package brackets/admin-auth');

        $this->call('admin-ui:install');

        $this->call('vendor:publish', [
            '--provider' => "Brackets\\AdminAuth\\AdminAuthServiceProvider",
        ]);

        $this->frontendAdjustments();

        $this->strReplaceInFile(
            resource_path('views/admin/layout/profile-dropdown.blade.php'),
            '|url\(\'admin\/logout\'\)|',
            '{{-- Do not delete me :) I\'m used for auto-generation menu items --}}',
            '{{-- Do not delete me :) I\'m used for auto-generation menu items --}}
    <a href="{{ url(\'admin/logout\') }}" class="dropdown-item"><i class="fa fa-lock"></i> Logout</a>');

        $this->call('migrate');

        $this->info('Package brackets/admin-auth installed');
    }

    private function strReplaceInFile($fileName, $ifExistsRegex, $find, $replaceWith) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }

    private function appendIfNotExists($fileName, $ifExistsRegex, $append) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return;
        }

        return File::put($fileName, $content.$append);
    }

    private function frontendAdjustments() {
        // webpack
        $this->strReplaceInFile(
            'webpack.mix.js',
            '|vendor/brackets/admin-auth|',
            '// Do not delete this comment, it\'s used for auto-generation :)',
            'path.resolve(__dirname, \'vendor/brackets/admin-auth/resources/assets/js\'),
				// Do not delete this comment, it\'s used for auto-generation :)');

        $this->info('Admin Auth assets registered');
    }
}