<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            // Pehle foreign keys aur primary key drop karein agar error aaye, 
            // ya seedhe id ko string/varchar me modify karein:
            $table->string('id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });
    }
};