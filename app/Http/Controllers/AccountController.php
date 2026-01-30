<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::orderBy('code')->get();

        $grouped = $accounts->groupBy('parent_id');

        $accounts->each(function ($account) use ($grouped) {
            $account->setRelation('children', $grouped->get($account->id, collect()));
        });

        return Inertia::render("account/index", [
            "accounts" => $accounts->whereNull('parent_id')->values(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('account/create', [
            'parents' => Account::orderBy('code')->get(),
            'types' => ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $account = Account::create($request->validated());
        return redirect()->route("account.index")->with("success", "Account {$account->name} of type {$account->type} created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
