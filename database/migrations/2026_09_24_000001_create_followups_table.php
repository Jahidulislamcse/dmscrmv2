<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['call', 'email', 'whatsapp', 'meeting', 'visit', 'other'])->default('call');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->dateTime('scheduled_at');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('outcome')->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('meeting_id')->nullable()->constrained('meetings')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('followups');
    }
};
