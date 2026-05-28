<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('wanted_item')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('renewed_count')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['wanted_item', 'expires_at', 'renewed_count']);
        });
    }
};
