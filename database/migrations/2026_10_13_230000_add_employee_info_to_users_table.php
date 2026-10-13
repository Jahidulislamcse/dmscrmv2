<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'designation')) {
                $table->string('designation', 100)->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department', 100)->nullable()->after('designation');
            }
            if (!Schema::hasColumn('users', 'joining_date')) {
                $table->date('joining_date')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'base_salary')) {
                $table->decimal('base_salary', 10, 2)->default(0.00)->after('joining_date');
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->string('emergency_contact', 100)->nullable()->after('base_salary');
            }
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'designation', 'department', 'joining_date', 'base_salary', 'emergency_contact']);
        });
    }
};
