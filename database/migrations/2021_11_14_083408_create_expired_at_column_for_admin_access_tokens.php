<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpiredAtColumnForAdminAccessTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_access_tokens', function (Blueprint $table) {
            $table->dateTime('expired_at')
                ->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_access_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('admin_access_tokens','expired_at'))
                Schema::dropColumns('admin_access_tokens','expired_at');
        });
    }
}
