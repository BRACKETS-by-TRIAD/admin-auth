<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
            });

            $users = DB::table('users')->orderBy('id')->get();
            foreach ($users as $user) {
                $name = explode(' ', trim($user->name));
                $firstName = $name[0];
                unset($name[0]);
                $lastName = implode(' ', $name);
                $data = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ];

                DB::table('users')->where('id', $user->id)->update($data);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->boolean('activated')->default(false);
                $table->boolean('forbidden')->default(false);
                $table->softDeletes('deleted_at');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['email']);
            });

            Schema::table('users', function (Blueprint $table) {
                $table->unique(['email', 'deleted_at']);
            });

            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            if($driver == 'pgsql') {
                Schema::table('users', function (Blueprint $table) {
                    DB::statement('CREATE UNIQUE INDEX users_email_null_deleted_at ON users (email) WHERE deleted_at IS NULL;');
                });
            }

            Schema::table('users', function (Blueprint $table) {
                $table->string('language', 2)->default('en');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('language');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_email_null_deleted_at');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['email', 'deleted_at']);
            });

            Schema::table('users', function (Blueprint $table) {
                $table->unique(['email']);
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('activated');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('forbidden');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->string('name');
            });

            $users = DB::table('users')->orderBy('id')->get();
            foreach ($users as $user) {
                $data = [
                    'name' => $user->first_name.' '.$user->last_name
                ];

                DB::table('users')->where('id', $user->id)->update($data);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('first_name');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_name');
            });
        });
    }
}
