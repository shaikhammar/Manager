<?php

use App\Models\Business;
use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it seeds accounts when a business is created', function () {
    $user = User::factory()->create();
    $country = Country::create(['name' => 'United States', 'iso_code' => 'US']);
    $currency = Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$']);

    $business = Business::create([
        'name' => 'Test Business',
        'user_id' => $user->id,
        'country_id' => $country->id,
        'currency_id' => $currency->id,
    ]);

    // Check if Accounts were created
    expect(Account::where('business_id', $business->id)->count())->toBeGreaterThan(0);

    // -- ASSETS (1000) --
    $assets = Account::where('business_id', $business->id)->where('code', '1000')->first();
    expect($assets)->not->toBeNull()
        ->and($assets->name)->toBe('Assets')
        ->and($assets->type)->toBe('Asset');

    // 1100 - Cash & Bank (Parent: Assets)
    $cashHeader = Account::where('business_id', $business->id)->where('code', '1100')->first();
    expect($cashHeader)->not->toBeNull()
        ->and($cashHeader->name)->toBe('Cash & Bank')
        ->and($cashHeader->type)->toBe('Asset')
        ->and($cashHeader->parent_id)->toBe($assets->id);

    // 1101 - Main Operating Account (Parent: Cash & Bank)
    $mainOp = Account::where('business_id', $business->id)->where('code', '1101')->first();
    expect($mainOp)->not->toBeNull()
        ->and($mainOp->name)->toBe('Main Operating Account')
        ->and($mainOp->type)->toBe('Asset')
        ->and($mainOp->parent_id)->toBe($cashHeader->id);

    // 1200 - Accounts Receivable (Parent: Assets)
    $ar = Account::where('business_id', $business->id)->where('code', '1200')->first();
    expect($ar)->not->toBeNull()
        ->and($ar->name)->toBe('Accounts Receivable')
        ->and($ar->type)->toBe('Asset')
        ->and($ar->parent_id)->toBe($assets->id);

    // -- LIABILITIES (2000) --
    $liabilities = Account::where('business_id', $business->id)->where('code', '2000')->first();
    expect($liabilities)->not->toBeNull()
        ->and($liabilities->name)->toBe('Liabilities')
        ->and($liabilities->type)->toBe('Liability');

    // 2100 - Accounts Payable (Parent: Liabilities)
    $ap = Account::where('business_id', $business->id)->where('code', '2100')->first();
    expect($ap)->not->toBeNull()
        ->and($ap->name)->toBe('Accounts Payable')
        ->and($ap->type)->toBe('Liability')
        ->and($ap->parent_id)->toBe($liabilities->id);

    // -- EQUITY (3000) --
    $equity = Account::where('business_id', $business->id)->where('code', '3000')->first();
    expect($equity)->not->toBeNull()
        ->and($equity->name)->toBe('Equity')
        ->and($equity->type)->toBe('Equity');

    // 3100 - Opening Balance Equity (Parent: Equity)
    $obe = Account::where('business_id', $business->id)->where('code', '3100')->first();
    expect($obe)->not->toBeNull()
        ->and($obe->name)->toBe('Opening Balance Equity')
        ->and($obe->type)->toBe('Equity')
        ->and($obe->parent_id)->toBe($equity->id);

    // 3200 - Retained Earnings (Parent: Equity)
    $re = Account::where('business_id', $business->id)->where('code', '3200')->first();
    expect($re)->not->toBeNull()
        ->and($re->name)->toBe('Retained Earnings')
        ->and($re->type)->toBe('Equity')
        ->and($re->parent_id)->toBe($equity->id);

    // -- INCOME (4000) --
    $income = Account::where('business_id', $business->id)->where('code', '4000')->first();
    expect($income)->not->toBeNull()
        ->and($income->name)->toBe('Income')
        ->and($income->type)->toBe('Income');

    // 4100 - Translation Services (Parent: Income)
    $translation = Account::where('business_id', $business->id)->where('code', '4100')->first();
    expect($translation)->not->toBeNull()
        ->and($translation->name)->toBe('Translation Services')
        ->and($translation->type)->toBe('Income')
        ->and($translation->parent_id)->toBe($income->id);

    // 4500 - Exchange Gain/Loss (Parent: Income)
    $fx = Account::where('business_id', $business->id)->where('code', '4500')->first();
    expect($fx)->not->toBeNull()
        ->and($fx->name)->toBe('Exchange Gain/Loss')
        ->and($fx->type)->toBe('Income')
        ->and($fx->parent_id)->toBe($income->id);

    // -- EXPENSES (5000) --
    $expenses = Account::where('business_id', $business->id)->where('code', '5000')->first();
    expect($expenses)->not->toBeNull()
        ->and($expenses->name)->toBe('Expenses')
        ->and($expenses->type)->toBe('Expense');
        
    // 5100 - Freelancer Cost (COGS) (Parent: Expenses)
    $freelancer = Account::where('business_id', $business->id)->where('code', '5100')->first();
    expect($freelancer)->not->toBeNull()
        ->and($freelancer->name)->toBe('Freelancer Cost (COGS)')
        ->and($freelancer->type)->toBe('Expense')
        ->and($freelancer->parent_id)->toBe($expenses->id);

    // 5200 - Bank Fee & Charges (Parent: Expenses)
    $bankFees = Account::where('business_id', $business->id)->where('code', '5200')->first();
    expect($bankFees)->not->toBeNull()
        ->and($bankFees->name)->toBe('Bank Fee & Charges')
        ->and($bankFees->type)->toBe('Expense')
        ->and($bankFees->parent_id)->toBe($expenses->id);

});
