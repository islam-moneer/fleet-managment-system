<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->decimal('cost');
            $table->integer('from_order');
            $table->integer('to_order');
            $table->timestamps();

            $table->foreign('seat_id')
                ->references('id')
                ->on('seats');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('from')
                ->references('id')
                ->on('cities');

            $table->foreign('to')
                ->references('id')
                ->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_seat');
    }
}
