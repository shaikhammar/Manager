<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    use BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'name',
        'code',
        'type',
        'parent_id',
        'is_selectable',
        'is_system',
    ];

    protected $casts = [
        'is_selectable' => 'boolean',
        'is_system' => 'boolean',
        'code' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id')->orderBy('code');
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }


    // -- Scopes --

    /**
     * Only return accounts that can actually have transactions posted to them.
     */
    public function scopeSelectable(Builder $query): void
    {
        $query->where('is_selectable', true);
    }

    /**
     * Filter by the major accounting categories.
     */
    public function scopeType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    // --- Logic ---

    /**
     * Determine if this account has a "Debit" normal balance.
     * Assets and Expenses increase with Debits.
     * Liabilities, Equity, and Revenue increase with Credits.
     */
    public function isDebitNormal(): bool
    {
        return in_array($this->type, ['Asset', 'Expense']);
    }

    /**
     * Calculate the current balance in Base Currency (USD).
     * This query is fast but sums every entry. For high volume, 
     * consider a cached balance column or a materialized view.
     */
    public function getBalanceBase()
    {
        $debits = $this->ledgerEntries->sum('debit_base');
        $credits = $this->ledgerEntries->sum('credit_base');

        return $this->isDebitNormal() ? $debits - $credits : $credits - $debits;
    }

    protected static function booted(): void
    {
        static::deleting(function ($account) {
        // 1. Prevent deleting system accounts (A/R, A/P, etc.)
        if ($account->is_system) {
            throw new \Exception("Cannot delete a system-protected account.");
        }

        // 2. Prevent deleting accounts with history
        if ($account->ledgerEntries()->exists()) {
            throw new \Exception("Cannot delete an account that has transaction history.");
        }
    });
}
}
