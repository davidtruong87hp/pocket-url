<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shortened_urls', function (Blueprint $table) {
            $driver = DB::connection()->getDriverName();

            $table->id();

            // Case-sensitive shortcode
            switch ($driver) {
                case 'mysql':
                case 'mariadb':
                    $table->string('shortcode', 10)->collation('utf8mb4_bin')->unique();
                    break;
                default:
                    $table->string('shortcode', 10)->primary();
                    break;
            }

            $table->text('url');
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortened_urls');
    }
};
