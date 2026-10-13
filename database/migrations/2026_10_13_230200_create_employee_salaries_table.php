<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('month', 7); // e.g. '2026-10'
            $table->decimal('base_salary', 10, 2)->default(0.00);
            $table->decimal('bonus', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->date('paid_at')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'month']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('employee_salaries');
    }
};
