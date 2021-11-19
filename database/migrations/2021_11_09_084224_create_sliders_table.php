<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();

            $table->string('image');

            $table->string('title')
                ->nullable();

            $table->string('describe')
                ->nullable();

            $table->string('uri')
                ->nullable();

            $table->string('uri_title',64)
                ->nullable();

            $table->bigInteger('sort')
                ->unsigned()
                ->nullable();

            $table->boolean('active')
                ->default(true);

            $table->bigInteger('user_id')
                ->unsigned();

            $table->timestamps();

            $table->softDeletes();

            $table->foreign('user_id')
                ->on('users')
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
        Schema::dropIfExists('sliders');
    }
}
