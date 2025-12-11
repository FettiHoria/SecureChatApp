<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('conversations', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['private', 'group'])->default('private');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('conversations');
}

};
