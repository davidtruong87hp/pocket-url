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
        Schema::create('shortcode_pool', function (Blueprint $table) {
            $driver = DB::connection()->getDriverName();

            // Case-sensitive shortcode
            switch ($driver) {
                case 'mysql':
                case 'mariadb':
                    $table->string('shortcode', 10)->collation('utf8mb4_bin')->primary();
                    break;
                default:
                    $table->string('shortcode', 10)->primary();
                    break;
            }

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortcode_pool');
    }
};
