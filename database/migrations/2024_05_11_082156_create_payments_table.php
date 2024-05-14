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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('sys_ref')->unique()->nullable();
            $table->string('reference')->unique()->nullable();
            $table->decimal('amount',11,2);
            $table->decimal('allocated',11,2)->default(0);
            $table->string('mode');
            $table->string('bank')->nullable();
            $table->string('account')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->dateTime('paid_on')->default(now());
            $table->boolean('processed')->default(false);
            $table->smallInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
