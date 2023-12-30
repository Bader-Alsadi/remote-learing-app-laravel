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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("department_detile_id")->references("id")->on("department_detiles")->onDelete("cascade");
            $table->foreignId("subject_id")->references("id")->on("subjects")->onDelete("cascade");
            $table->foreignId("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->unique([
                "subject_id","user_id"]);
                $table->unique([
                    "subject_id","department_detile_id"]);
            $table->string("year");
            $table->string("scientific_method");
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
        Schema::dropIfExists('enrollments');
    }
};
