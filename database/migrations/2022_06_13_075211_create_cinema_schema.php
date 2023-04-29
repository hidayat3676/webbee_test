<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('show_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('total_seats');
            $table->enum('status', ['available', 'running']);
        });

        Schema::create('movies', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->dateTime('available_for_watching_at');
            $table->enum('status', ['available', 'booked']);
            $table->boolean('running_status');
            $table->timestamps();
        });
        Schema::create('movie_show_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('movie_id')->unsigned();
            $table->bigInteger('admin_id')->unsigned();
            $table->bigInteger('show_room_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();
            $table->foreign('show_room_id')->references('id')->on('show_rooms')->cascadeOnDelete();
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
            $table->timestamps();

        });

        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('price');
            $table->enum('status', ['available', 'booked']);
            $table->enum('type', ['couple', 'vip', 'super']);
            $table->bigInteger('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('movie_id')->unsigned();
            $table->bigInteger('seat_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('seat_id')->references('id')->on('seats')->cascadeOnDelete();
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();
            $table->string('status');
            $table->timestamps();
        });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('show_rooms');
        Schema::drop('movies');
        Schema::drop('movie_show_rooms');
        Schema::drop('admins');
        Schema::drop('seats');
        Schema::drop('bookings');
    }
}
