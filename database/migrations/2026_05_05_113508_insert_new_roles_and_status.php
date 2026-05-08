<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class InsertNewRolesAndStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert new Roles
        DB::table('roles')->insert([
            ['id' => 7, 'role_desc' => 'Pegawai Aplikasi', 'active' => 1],
            ['id' => 8, 'role_desc' => 'Vendor', 'active' => 1],
        ]);

        // Insert new Status
        DB::table('status')->insert([
            ['id' => 11, 'status_desc' => 'Semakan Pegawai Aplikasi', 'active' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->whereIn('id', [7, 8])->delete();
        DB::table('status')->where('id', 11)->delete();
    }
}
