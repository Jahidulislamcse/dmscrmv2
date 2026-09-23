<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reminders', function (Blueprint $table) {
            if (!Schema::hasColumn('reminders', 'title')) {
                $table->string('title')->nullable()->after('channel');
            }
        });

        Schema::table('reminder_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('reminder_templates', 'title')) {
                $table->string('title')->nullable()->after('name');
            }
        });
    }

    public function down(): void {
        Schema::table('reminders', function (Blueprint $table) {
            if (Schema::hasColumn('reminders', 'title')) {
                $table->dropColumn('title');
            }
        });

        Schema::table('reminder_templates', function (Blueprint $table) {
            if (Schema::hasColumn('reminder_templates', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
