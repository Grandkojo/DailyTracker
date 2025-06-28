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
            $table->string('employee_id')->unique()->after('email');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('position')->after('department_id');
            $table->string('phone')->after('position');
            $table->enum('role', ['admin', 'support_team'])->default('support_team')->after('phone');
            $table->boolean('is_active')->default(true)->after('role');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['employee_id', 'department_id', 'position', 'phone', 'role', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('employee_id');
        });
    }
};
