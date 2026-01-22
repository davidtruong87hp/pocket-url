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
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->default(0)->after('id');

            $table->index(['user_id', 'shortcode', 'clicked_at'], 'user_shortcode_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropIndex('user_shortcode_index');

            $table->dropColumn('user_id');
        });
    }
};
