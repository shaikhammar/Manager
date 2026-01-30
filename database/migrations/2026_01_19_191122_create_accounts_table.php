<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\BelongsToBusiness;

return new class extends Migration
{
    use BelongsToBusiness;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('code'); // 1000, 2000, 3000, 4000, etc.
            $table->enum('type', ['Asset', 'Liability', 'Equity', 'Income', 'Expense']);
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->boolean('is_selectable')->default(true); // Can we post to this account?
            $table->boolean('is_system')->default(false); // Is this an account created by the system?

            $table->foreignId('business_id')->constrained()->cascadeOnDelete();

            $table->unique(['code','business_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
