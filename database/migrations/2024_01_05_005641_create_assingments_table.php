<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assingments', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->double("grade");
            $table->foreignId("enrollment_id")->references("id")->on("enrollments")->onDelete("cascade");
            $table->dateTime("deadline");
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
        Schema::dropIfExists('assingments');
    }
};
