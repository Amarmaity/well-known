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
        Schema::create('access_modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('module_key')->unique();

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('icon')->nullable();

            $table->integer('sort_order')->default(0);

            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('parent_id')
                ->references('id')
                ->on('access_modules')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_modules');
    }
};
