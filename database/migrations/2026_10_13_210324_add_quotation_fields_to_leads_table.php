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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('quotation_status', 30)->default('draft')->after('next_followup'); // draft, pending_approval, approved, rejected
            $table->text('quotation_notes')->nullable()->after('quotation_status');
            $table->date('quotation_valid_until')->nullable()->after('quotation_notes');
            $table->decimal('quotation_discount', 10, 2)->default(0.00)->after('quotation_valid_until');
            $table->decimal('quotation_tax_percent', 5, 2)->default(0.00)->after('quotation_discount');
            $table->foreignId('quotation_approved_by')->nullable()->after('quotation_tax_percent')->constrained('users')->nullOnDelete();
            $table->timestamp('quotation_approved_at')->nullable()->after('quotation_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['quotation_approved_by']);
            $table->dropColumn([
                'quotation_status',
                'quotation_notes',
                'quotation_valid_until',
                'quotation_discount',
                'quotation_tax_percent',
                'quotation_approved_by',
                'quotation_approved_at',
            ]);
        });
    }
};
