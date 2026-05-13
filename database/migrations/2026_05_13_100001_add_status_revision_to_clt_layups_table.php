<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clt_layups', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('description');
            $table->unsignedInteger('revision_count')->default(0)->after('status');
            $table->json('revisions')->nullable()->after('revision_count');
        });
    }

    public function down(): void
    {
        Schema::table('clt_layups', function (Blueprint $table) {
            $table->dropColumn(['status', 'revision_count', 'revisions']);
        });
    }
};
