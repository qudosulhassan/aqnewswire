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
            $table->string('role')->default('subscriber')->after('email'); // admin, editor, writer, contributor, subscriber
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('title')->nullable()->after('slug'); // e.g. "Senior Markets Editor", "Tech Contributor"
            $table->text('bio')->nullable()->after('title');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('website')->nullable()->after('avatar');
            $table->string('twitter')->nullable()->after('website');
            $table->string('linkedin')->nullable()->after('twitter');
            $table->boolean('is_verified')->default(false)->after('linkedin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'slug')) {
                $table->dropUnique(['slug']);
            }
            $table->dropColumn([
                'role',
                'slug',
                'title',
                'bio',
                'avatar',
                'website',
                'twitter',
                'linkedin',
                'is_verified'
            ]);
        });
    }
};
