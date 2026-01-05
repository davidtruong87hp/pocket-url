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
        Schema::create('url_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shortened_url_id')->constrained('shortened_urls')->onDelete('cascade');
            $table->json('changes');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['shortened_url_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_edit_histories');
    }
};
