<?php

namespace App\Http\Controllers;

use App\Http\Requests\Business\BusinessCreateRequest;
use App\Models\Business;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businesses = Auth::user()->businesses()
        ->select('id', 'name','code','currency_id','country_id')
        ->get();

        if($businesses->isEmpty()) {
            return redirect()->route('business.create');
        }

        return Inertia::render('business/index', [
            'businesses' => $businesses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::select('id', 'name','code')->get();
        $currencies = Currency::select('id', 'name','code','symbol')->get();
        return Inertia::render('business/create', [
            'countries' => $countries,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusinessCreateRequest $request)
    {
        $business = Auth::user()->businesses()->create($request->all());

        return redirect()->route('business.index')->with('success', 'Business {business->name} created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        return Inertia::render('business/show', [
            'business' => $business,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        return Inertia::render('business/edit', [
            'business' => $business,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        $business->update($request->all());

        return redirect()->route('business.index')->with('success', 'Business updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()->route('business.index')->with('success', 'Business deleted successfully');
    }

    public function switch(Request $request, Business $business)
    {
        if($business->user_id != Auth::user()->id) {
            return redirect()->route('business.index')->with('error', 'You do not have permission to switch to this business');
        }
        if(!$business){
            return redirect()->route('business.index')->with('error', 'Select a valid business');
        }
        $request->session()->put('business_id', $business->id);

        return redirect()->route('dashboard')->with('success', 'Business switched successfully');
    }
}
