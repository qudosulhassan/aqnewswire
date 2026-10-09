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
        Schema::table('contributor_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('contributor_applications', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributor_applications', function (Blueprint $table) {
            if (Schema::hasColumn('contributor_applications', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
        });
    }
};
