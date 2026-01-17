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
        Schema::create('click_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('shortcode', 20)->index();
            $table->date('date')->index();

            // Counts
            $table->unsignedInteger('total_clicks')->default(0);
            $table->unsignedInteger('unique_clicks')->default(0);
            $table->unsignedInteger('bot_clicks')->default(0);
            $table->unsignedInteger('mobile_clicks')->default(0);

            // Top values
            $table->json('top_countries')->nullable();
            $table->json('top_cities')->nullable();
            $table->json('top_referrers')->nullable();
            $table->json('top_devices')->nullable();
            $table->json('top_browsers')->nullable();
            $table->json('top_platforms')->nullable();

            // Hourly breakdown
            $table->json('hourly_distribution')->nullable();

            $table->timestamps();

            $table->unique(['shortcode', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_statistics');
    }
};
