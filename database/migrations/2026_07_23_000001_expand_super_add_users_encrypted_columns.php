<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('super_add_users', function (Blueprint $table) {
            $table->text('mobno')->nullable()->change();
            $table->text('salary')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('super_add_users', function (Blueprint $table) {
            $table->string('mobno', 15)->nullable()->change();
            $table->decimal('salary', 12, 2)->nullable()->change();
        });
    }
};
