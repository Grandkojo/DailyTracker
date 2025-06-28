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
        Schema::create('activity_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->enum('previous_status', ['pending', 'in_progress', 'done', 'cancelled'])->nullable();
            $table->enum('new_status', ['pending', 'in_progress', 'done', 'cancelled']);
            $table->text('remark')->nullable();
            $table->json('user_bio_details')->nullable(); // Store user details at time of update
            $table->timestamp('update_time');
            $table->timestamps();
            
            $table->index(['activity_id', 'update_time']);
            $table->index(['updated_by', 'update_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_updates');
    }
};
