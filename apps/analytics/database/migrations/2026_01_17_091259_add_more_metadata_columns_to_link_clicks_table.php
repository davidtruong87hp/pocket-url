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
            // Geolocation
            $table->string('country', 2)->nullable()->index();
            $table->string('country_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Device Information
            $table->string('device_type', 20)->nullable()->index(); // mobile, tablet, desktop
            $table->string('device_brand', 50)->nullable();
            $table->string('device_model', 100)->nullable();
            $table->string('os_name', 50)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->string('browser_name', 50)->nullable();
            $table->string('browser_version', 50)->nullable();

            // Indexes
            $table->index(['country', 'clicked_at']);
            $table->index(['device_type', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_clicks', function (Blueprint $table) {
            $table->dropIndex(['country', 'clicked_at']);
            $table->dropIndex(['device_type', 'clicked_at']);

            $table->dropColumn([
                'country',
                'country_name',
                'city',
                'region',
                'latitude',
                'longitude',
                'device_type',
                'device_brand',
                'device_model',
                'os_name',
                'os_version',
                'browser_name',
                'browser_version',
            ]);
        });
    }
};
