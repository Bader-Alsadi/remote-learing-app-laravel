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
        Schema::create('department_detiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("department_id")->references("id")->on("departments")->onDelete("cascade");
            $table->string("semester");
            $table->string("Level");
            $table->date("strat_date");
            $table->date("end_date");
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
        Schema::dropIfExists('department_detiles');
    }
};
