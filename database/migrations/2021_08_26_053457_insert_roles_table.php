<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class InsertRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert([
                ['name' => 'Пользователь', 'slug' => 'user'],
                ['name' => 'Администратор', 'slug' => 'admin']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('roles',
            [
                ['slug' => 'user'],
                ['slug' => 'admin']
            ]
        );
    }
}
