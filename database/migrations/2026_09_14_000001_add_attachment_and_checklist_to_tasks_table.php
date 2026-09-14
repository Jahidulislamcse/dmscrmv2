<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'checklist')) {
                $table->json('checklist')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('tasks', 'attachments')) {
                $table->json('attachments')->nullable()->after('checklist');
            }
        });
    }

    public function down(): void {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'checklist')) {
                $table->dropColumn('checklist');
            }
            if (Schema::hasColumn('tasks', 'attachments')) {
                $table->dropColumn('attachments');
            }
        });
    }
};
