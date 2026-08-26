<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('id');
            }
        });

        // 🟢 Foreign key ko alag se try-catch ya check ke sath lagayein taaki duplicate error na aaye
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('department_id', 'users_department_id_foreign')
                      ->references('id')
                      ->on('departments')
                      ->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Agar constraint pehle se exist karti hai toh ignore ho jayegi
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_department_id_foreign');
            $table->dropColumn('department_id');
        });
    }
};