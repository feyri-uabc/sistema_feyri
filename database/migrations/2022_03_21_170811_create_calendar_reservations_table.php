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
            $table->unsignedInteger("instructor_id")->nullable(false);
            $table->integer("select_year")->nullable(false);
            $table->integer("select_month")->nullable(false);
            $table->integer("select_day")->nullable(false);
            $table->integer("select_hour")->nullable(false);
            $table->timestamp("created_at")->useCurrent();

            $table->foreign("lab_id")->references("id")->on("calendar_labs")->onDelete('cascade');
            $table->foreign("instructor_id")->references("id")->on("calendar_instructors")->onDelete('cascade');
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
