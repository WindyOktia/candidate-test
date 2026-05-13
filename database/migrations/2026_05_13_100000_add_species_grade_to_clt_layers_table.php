<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clt_layers', function (Blueprint $table) {
            $table->string('species')->nullable()->after('angle');
            $table->string('grade')->nullable()->after('species');
        });
    }

    public function down(): void
    {
        Schema::table('clt_layers', function (Blueprint $table) {
            $table->dropColumn(['species', 'grade']);
        });
    }
};
