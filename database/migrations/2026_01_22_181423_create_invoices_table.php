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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('invoice_number');
            $table->date('issue_date');
            $table->date('due_date')->nullable();

            // Financial snapshot
            $table->bigInteger('amount_foreign');
            $table->bigInteger('amount_base');
            $table->decimal('exchange_rate', 15, 8);
            $table->foreignId('currency_id')->constrained();

            $table->enum('status', ['Draft', 'Sent', 'Paid', 'Partially Paid', 'Overdue', 'Cancelled', 'Voided'])->default('Draft');

            $table->foreignId('business_id')->constrained();

            $table->unique(['invoice_number','business_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
