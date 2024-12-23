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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')->constrained(
                table: 'users'
            );
            $table->foreignId('category_id')->constrained(
                table: 'categories'
            );

            $table->float('amount', 10, 2)->default(0);

            $table->string('image')->nullable();
            $table->string('type');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
