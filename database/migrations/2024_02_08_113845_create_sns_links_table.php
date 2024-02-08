<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sns_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sns_message_id');
            $table->string('service');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sns_links');
    }
};
