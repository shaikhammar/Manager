<?php

use App\Models\Business;
use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('user without businesses is redirected to create business page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('business.index'));
        
    // Following the redirect chain:
    // dashboard -> business.index -> business.create
    $this->actingAs($user)
        ->get(route('business.index'))
        ->assertRedirect(route('business.create'));
});

test('user with existing business can visit dashboard', function () {
    $user = User::factory()->create();
    $country = Country::create(['name' => 'United States', 'iso_code' => 'US']);
    $currency = Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$']);
    
    $business = Business::create([
        'name' => 'My Business',
        'user_id' => $user->id,
        'country_id' => $country->id,
        'currency_id' => $currency->id,
    ]);

    // Middleware SetDefaultBusinessContext should set the session
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk(); 
});