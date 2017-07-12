<?php

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
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
                $table->boolean('enabled')->default(false);
                $table->softDeletes('deleted_at');
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
                $table->dropColumn('last_name');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('enabled');
                $table->dropColumn('deleted_at');
            });
        });
    }
}
