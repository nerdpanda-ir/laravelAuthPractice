<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            $table->string('name',32);

            $table->string('family',32);

            $table->string('nick',60);

            $table->string('username',60)
                ->unique();

            $table->string('email',160)
            ->unique();

            $table->string('phone',11)
                ->unique();

            $table->string('password',60)
            ->index();

            $table->boolean('active')
                ->index()
                ->default(true);

            $table->string('thumbnail',200)
                ->nullable();



            $table->timestamps();

            $table->dateTime('email_verified_at')
                ->nullable()
                ->index();
            $table->dateTime('phone_verified_at')
                ->nullable()
                ->index();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
