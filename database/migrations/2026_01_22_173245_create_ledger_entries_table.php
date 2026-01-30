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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained();

            // Sub-Ledger entries
            $table->foreignId('contact_id')->nullable()->constrained();

            // Double entry columns
            $table->bigInteger('debit_base')->default(0);
            $table->bigInteger('credit_base')->default(0);

            // Exchange rate columns
            $table->bigInteger('debit_foreign')->default(0);
            $table->bigInteger('credit_foreign')->default(0);
            $table->string('currency_code', 3);
            $table->decimal('exchange_rate', 15, 8);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
