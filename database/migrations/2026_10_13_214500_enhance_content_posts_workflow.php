<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Alter existing enum columns to varchar so custom stages & formats don't get restricted
        DB::statement("ALTER TABLE content_posts MODIFY COLUMN status VARCHAR(50) DEFAULT 'draft'");
        DB::statement("ALTER TABLE content_posts MODIFY COLUMN post_type VARCHAR(50) DEFAULT 'design'");

        Schema::table('content_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('content_posts', 'caption')) {
                $table->text('caption')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('content_posts', 'creative_url')) {
                $table->string('creative_url', 1000)->nullable()->after('caption');
            }
            if (!Schema::hasColumn('content_posts', 'internal_feedback')) {
                $table->text('internal_feedback')->nullable()->after('creative_url');
            }
            if (!Schema::hasColumn('content_posts', 'client_feedback')) {
                $table->text('client_feedback')->nullable()->after('internal_feedback');
            }
            if (!Schema::hasColumn('content_posts', 'publish_time')) {
                $table->string('publish_time', 20)->nullable()->after('date');
            }
        });
    }

    public function down(): void {
        Schema::table('content_posts', function (Blueprint $table) {
            $table->dropColumn(['caption', 'creative_url', 'internal_feedback', 'client_feedback', 'publish_time']);
        });
    }
};
