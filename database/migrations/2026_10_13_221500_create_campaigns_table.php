<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('platform', 50)->default('meta'); // meta, google, tiktok, linkedin, youtube, other
            $table->string('objective', 50)->default('leads'); // leads, sales, traffic, awareness, engagement
            $table->string('status', 50)->default('active'); // active, paused, completed, draft
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->decimal('spend', 12, 2)->default(0.00);
            $table->decimal('revenue', 12, 2)->default(0.00);
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('leads_count')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('target_audience')->nullable();
            $table->text('notes')->nullable();
            $table->string('ad_account_id', 100)->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('campaigns');
    }
};
