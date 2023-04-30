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




     /**

            movies: A table to store information about movies, such as id, title, description, image_url, duration, etc.

            shows: A table to store information about shows, such as id, movie_id, start_time, end_time, room_id, etc.

            rooms: A table to store information about cinema rooms, such as id, name, capacity, cinema_id, etc.

            cinemas: A table to store information about cinemas, such as id, name, location, owner_id, etc.

            seats: A table to store information about seats in a room, such as id, room_id, row, number, type, premium, etc.

            bookings: A table to store information about bookings, such as id, show_id, user_id, seat_id, booking_time, etc.

            prices: A table to store information about prices, such as id, show_id, seat_type, price, etc.

            Here are some key and foreign keys that can be used in the above tables:

            movies: Primary key is id.

            shows: Primary key is id. movie_id is a foreign key referencing movies.id. room_id is a foreign key referencing rooms.id.

            rooms: Primary key is id. cinema_id is a foreign key referencing cinemas.id.

            cinemas: Primary key is id. owner_id is a foreign key referencing users.id (assuming there is a separate users table for cinema owners).

            seats: Primary key is id. room_id is a foreign key referencing rooms.id.

            bookings: Primary key is id. show_id is a foreign key referencing shows.id. seat_id is a foreign key referencing seats.id. user_id is a foreign key referencing users.id (assuming there is a separate users table for users).
            
            prices: Primary key is id. show_id is a foreign key referencing shows.id. seat_type can be an enum or a separate table with its own foreign key.


        **/


    public function up()
    {



         Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('poster_url');
            $table->timestamps();
        });

        // Create `showrooms` table
        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create `shows` table
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies');
            $table->foreignId('showroom_id')->constrained('showrooms');
            $table->dateTime('start_time');
            $table->timestamps();
        });

        // Create `seats` table
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create `seat_types` table
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('premium_percent', 5, 2)->default(0);
            $table->timestamps();
        });

        // Create `show_seat_types` table
        Schema::create('show_seat_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('seat_type_id')->constrained('seat_types');
            $table->timestamps();
        });

        // Create `bookings` table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('seat_id')->constrained('seats');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });

        // Create `booked_seats` table
        Schema::create('booked_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('seat_id')->constrained('seats');
            $table->timestamps();
        });


        throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists("seats");
        Schema::dropIfExists("showrooms");
        Schema::dropIfExists("shows");
        Schema::dropIfExists("movies");
        Schema::dropIfExists("bookings");
        Schema::dropIfExists("booked_seats");
        Schema::dropIfExists("show_seats");
        Schema::dropIfExists("show_seat_types");

    }
}
