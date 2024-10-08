<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->string('url')->unique();
            $table->string('title')->nullable();
            $table->string('description')->nullable();

            $table->integer('interval')->default(60); // in minutes
            $table->timestamp('last_fetched_at')->nullable();
            $table->timestamp('next_fetch_at')->index();

            $table->integer('subscribers')->default(0);

            $table->string('conditional_get_last_modified')->nullable();
            $table->string('conditional_get_etag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
};
