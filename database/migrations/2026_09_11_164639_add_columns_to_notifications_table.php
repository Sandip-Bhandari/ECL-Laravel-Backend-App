<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('notifications', 'task_id')) {
                $table->foreignId('task_id')->after('user_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->after('task_id');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title');
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->default('general')->after('message')->index();
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('type')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['task_id']);
            $table->dropColumn(['user_id', 'task_id', 'title', 'message', 'type', 'is_read']);
        });
    }
};