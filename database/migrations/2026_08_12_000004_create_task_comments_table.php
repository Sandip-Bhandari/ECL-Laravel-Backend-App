<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { 
       Schema::create('task_comments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('task_id');
    $table->unsignedBigInteger('user_id');
    $table->string('user_name'); // Message bhejne wale ka naam
    $table->text('message');
    $table->timestamps();
});
    }

    public function down(): void { 
        Schema::dropIfExists('task_comments'); 
    }
};