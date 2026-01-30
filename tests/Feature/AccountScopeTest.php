<?php

use App\Models\Business;
use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('account global scope filters by active business session', function () {
    $user = User::factory()->create();
    $country = Country::create(['name' => 'United States', 'iso_code' => 'US']);
    $currency = Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$']);

    // Business 1
    $businessToSee = Business::create([
        'name' => 'Visible Business',
        'user_id' => $user->id,
        'country_id' => $country->id,
        'currency_id' => $currency->id,
    ]);
    
    // Business 2
    $businessHidden = Business::create([
        'name' => 'Hidden Business',
        'user_id' => $user->id,
        'country_id' => $country->id,
        'currency_id' => $currency->id,
    ]);

    // Create accounts manually to avoid seeder noise if needed, or just rely on seeder
    // Seeder runs on creation, so accounts exist.
    
    // Set Session (for middleware simulation if needed)
    session(['active_business_id' => $businessToSee->id]);
    
    // Set BusinessManager (Logic used by Scope)
    app(\App\Service\Business\BusinessManager::class)->setBusiness($businessToSee);

    // Query
    $accounts = Account::all();

    // Verification
    // AccountSeeder creates multiple accounts. We just need to make sure we don't see BusinessHidden's accounts.
    
    // Check that we have some accounts
    expect($accounts->count())->toBeGreaterThan(0);

    // Check that all accounts belong to businessToSee
    foreach ($accounts as $account) {
        expect($account->business_id)->toBe($businessToSee->id);
    }
    
    // Explicitly check that we cannot see accounts from hidden business
    $hiddenAccountsCount = Account::withoutGlobalScope(\App\Models\Scopes\BusinessScope::class)->where('business_id', $businessHidden->id)->count();
    expect($hiddenAccountsCount)->toBeGreaterThan(0);
    
    // Ensure our scoped query didn't pick them up
    $visibleIds = $accounts->pluck('id');
    $hiddenAccounts = Account::withoutGlobalScope(\App\Models\Scopes\BusinessScope::class)->where('business_id', $businessHidden->id)->pluck('id');
    
    foreach ($hiddenAccounts as $hiddenId) {
        expect($visibleIds)->not->toContain($hiddenId);
    }
});
