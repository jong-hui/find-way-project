<?php
    
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableInit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function(Blueprint  $table) {
            $table->increments("id");
            $table->string("username", 191)->unique();
            $table->string("password");
            $table->string("token");
            $table->rememberToken();
        });

        Schema::create("places", function(Blueprint  $table) {
            $table->increments("id");
            $table->string("name", 100);
            $table->float("latitude", 8, 6);
            $table->float("longitude", 8, 6);
            $table->integer("x");
            $table->integer("y");
            $table->string("image_path", 50);
            $table->text("description");
        });

        Schema::create("schedules", function(Blueprint  $table) {
            $table->increments("id");
            $table->enum("type", ["TRAIN", "BUS"]);
            $table->integer("line");
            $table->unsignedInteger("from_place_id");
            $table->unsignedInteger("to_place_id");
            $table->time("departure_time");
            $table->time("arrival_time");
            $table->integer("distance");
            $table->integer("speed");
            $table->enum("status", ["AVAILABLE", "UNAVAILABLE"]);
        });

        Schema::create("histories", function(Blueprint  $table) {
            $table->increments("id");
            $table->unsignedInteger("user_id");
            $table->unsignedInteger("place_id");
            $table->unsignedInteger("count");
        });

        schema::table("schedules", function(Blueprint $table) {
            $table->foreign("from_place_id")->references("id")->on("places")->onDelete("cascade");
            $table->foreign("to_place_id")->references("id")->on("places")->onDelete("cascade");
        });
        schema::table("histories", function(Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("place_id")->references("id")->on("places")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
