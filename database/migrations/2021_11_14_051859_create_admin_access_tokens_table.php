<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_access_tokens', function (Blueprint $table) {
            $table->id();

            $table->string('token',40)
                ->unique();

            $table->boolean('active')
                ->default(true);

            $table->bigInteger('admin_id')
                ->unsigned();

            $table->timestamps();


            $table->foreign('admin_id')
                ->on('admins')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_access_tokens');
    }
}
