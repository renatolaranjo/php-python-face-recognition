<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('face_recognitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->binary('face_encoding');
            $table->string('image_path')->nullable();
            $table->timestamp('recognized_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('face_recognitions');
    }
};
