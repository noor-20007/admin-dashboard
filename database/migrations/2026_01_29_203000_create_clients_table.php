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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('gender')->nullable(); // Male, Female
            $table->integer('age')->nullable();
            $table->string('identity_num')->nullable();
            $table->string('phone')->nullable();
            $table->string('region')->nullable();
            $table->string('type')->nullable(); // e.g., VIP, Regular
            $table->string('client_group')->nullable(); // Group A, Group B
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
