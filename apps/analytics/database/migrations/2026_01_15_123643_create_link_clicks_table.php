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
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('shortcode', 20)->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->string('referrer_domain', 255)->nullable();
            $table->text('referrer_url')->nullable();

            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_mobile')->default(false)->index();
            $table->json('raw_data')->nullable(); // for debugging

            $table->timestamp('clicked_at')->index();
            $table->timestamps();

            $table->index(['shortcode', 'clicked_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_clicks');
    }
};
