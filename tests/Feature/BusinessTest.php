<?php

use App\Models\Business;
use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->country = Country::create(['name' => 'United States', 'iso_code' => 'US']);
    $this->currency = Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$']);
});

test('business model generates code automatically', function () {
    $business = Business::create([
        'name' => 'Acme Corp',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    expect($business->code)->not->toBeNull()
        ->and($business->code)->toBe('AC');
});

test('business model sets is_default for first business', function () {
    $firstBusiness = Business::create([
        'name' => 'First Business',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    expect($firstBusiness->is_default)->toBeTrue();

    $secondBusiness = Business::create([
        'name' => 'Second Business',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    expect($firstBusiness->user_id)->toBe($secondBusiness->user_id);
    expect($secondBusiness->is_default)->toBeFalse();
});

test('business code generation handles duplicates', function () {
    Business::create([
        'name' => 'Acme Corp',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $business2 = Business::create([
        'name' => 'Acme Corp',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    expect($business2->code)->toBe('AC1');
});

test('index displays user businesses', function () {
    $this->actingAs($this->user);

    $business = Business::create([
        'name' => 'My Business',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $response = $this->get(route('business.index'));

    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('business/index')
            ->has('businesses', 1)
            ->where('businesses.0.name', 'My Business')
        );
});

test('index redirects to create if no businesses exist', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('business.index'));

    $response->assertRedirect(route('business.create'));
});

test('create page renders with countries and currencies', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('business.create'));

    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('business/create')
            ->has('countries')
            ->has('currencies')
        );
});

test('store creates new business', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('business.store'), [
        'name' => 'New Business',
        'code' => 'NB', // Check if code is required in request validation?
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $response->assertRedirect(route('business.index'));
    
    $this->assertDatabaseHas('businesses', [
        'name' => 'New Business',
        'user_id' => $this->user->id,
    ]);
});

test('update modifies existing business', function () {
    $this->actingAs($this->user);

    $business = Business::create([
        'name' => 'Old Name',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $response = $this->put(route('business.update', $business), [
        'name' => 'New Name',
        'code' => 'NEWNAME',
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $response->assertRedirect(route('business.index'));

    $this->assertDatabaseHas('businesses', [
        'id' => $business->id,
        'name' => 'New Name',
        'code' => 'NEWNAME',
    ]);
});

test('switch changes active business in session', function () {
    $this->actingAs($this->user);

    $business = Business::create([
        'name' => 'My Business',
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $response = $this->post(route('business.switch', $business));

    $response->assertRedirect(route('dashboard'));
    
    expect(session('active_business_id'))->toBe($business->id);
});

test('cannot switch to another users business', function () {
    $otherUser = User::factory()->create();
    $otherBusiness = Business::create([
        'name' => 'Other Business',
        'user_id' => $otherUser->id,
        'country_id' => $this->country->id,
        'currency_id' => $this->currency->id,
    ]);

    $this->actingAs($this->user);

    $response = $this->post(route('business.switch', $otherBusiness));

    // Should probably redirect back or to index with error
    $response->assertRedirect(route('business.index'));
    
    expect(session('active_business_id'))->not->toBe($otherBusiness->id);
});
