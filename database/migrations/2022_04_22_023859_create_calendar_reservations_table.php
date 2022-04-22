<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_reservations', function (Blueprint $table) {
            $table->increments("id");

            $table->unsignedInteger("lab_id")->nullable(false);
            $table->foreign("lab_id")->references("id")->on("calendar_labs")->onDelete('cascade');

            $table->unsignedInteger("instructor_id")->nullable(false);
            $table->foreign("instructor_id")->references("id")->on("calendar_instructors")->onDelete('cascade');

            $table->unsignedInteger("course_id")->nullable(false);
            $table->foreign("course_id")->references("id")->on("calendar_courses")->onDelete('cascade');

            $table->unsignedInteger("group_id")->nullable(false);
            $table->foreign("group_id")->references("id")->on("calendar_groups")->onDelete('cascade');

            $table->integer("select_year")->nullable(false);
            $table->integer("select_month")->nullable(false);
            $table->integer("select_day")->nullable(false);
            $table->integer("select_hour")->nullable(false);

            $table->string("tipo", 20)->nullable(false);
            $table->integer("grouping")->nullable(false);
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
        Schema::dropIfExists('calendar_reservations');
    }
}
