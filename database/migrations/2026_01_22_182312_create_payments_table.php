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
            $table->timestamps();

            $table->foreignId('invoice_id')->constrained()->nullable();
            $table->foreignId('contact_id')->constrained();

            $table->bigInteger('amount_received_base');
            $table->bigInteger('bank_fees_base')->default(0);
            $table->date('payment_date');
            $table->string('reference')->nullable();

            $table->foreignId('business_id')->constrained();
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
