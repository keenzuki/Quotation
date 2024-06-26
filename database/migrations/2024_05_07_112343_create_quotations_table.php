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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('reference')->unique();
            $table->string('title')->nullable();
            $table->string('details')->nullable();
            $table->decimal('cost',11,2)->default('0');
            $table->integer('status')->default(0);
            $table->decimal('paid_amount',11,2)->default('0');
            $table->smallInteger('pay_status')->default(1);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
