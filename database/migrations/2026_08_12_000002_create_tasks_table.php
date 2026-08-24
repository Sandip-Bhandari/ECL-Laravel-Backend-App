<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('tasks', function(Blueprint $table){ $table->id(); $table->string('title'); $table->text('description')->nullable(); $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); $table->enum('status', ['Pending','In Progress','Completed'])->default('Pending')->index(); $table->date('due_date')->nullable()->index(); $table->timestamp('completed_at')->nullable(); $table->timestamps(); $table->index(['department_id','status']); $table->index(['assigned_to','status']); }); }
 public function down(): void { Schema::dropIfExists('tasks'); }
};
