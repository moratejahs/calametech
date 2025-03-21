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
        Schema::create('s_o_s', function (Blueprint $table) {
            $table->id();
            $table->string('lat');
            $table->string('long');
            $table->enum('status', ['pending', 'resolved', 'dismissed']);
            $table->enum('type', ['fire', 'flood'])->nullable();
            $table->string('image_path')->nullable();
            $table->string('address')->nullable();
            $table->foreignId(column: 'user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_o_s');
    }
};
