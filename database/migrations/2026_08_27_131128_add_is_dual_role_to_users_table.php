<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check kar rahe hain ki column pehle se toh nahi hai
            if (!Schema::hasColumn('users', 'is_dual_role')) {
                $table->boolean('is_dual_role')->default(false)->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_dual_role')) {
                $table->dropColumn('is_dual_role');
            }
        });
    }
};