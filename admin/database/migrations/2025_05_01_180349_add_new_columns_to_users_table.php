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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('contact_number');
            $table->string('id_picture')->nullable()->after('plain_password');
            $table->string('id_type')->nullable()->after('id_picture');
            $table->boolean('is_verified')->default(false)->after('id_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('id_picture');
            $table->dropColumn('id_type');
            $table->dropColumn('is_verified');
        });
    }
};
