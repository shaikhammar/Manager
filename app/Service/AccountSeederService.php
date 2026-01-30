<?php

namespace App\Service;

use App\Models\Business;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountSeederService
{
    public function seedStandardAccounts(Business $business)
    {
        DB::transaction(function () use ($business){
            // -- 1000 - ASSETS --
            $assets = $this->createAccounts($business, '1000','Assets', 'Asset', null, false);

            // 1100 - Cash & Bank
            $cashHeader = $this->createAccounts($business, '1100','Cash & Bank', 'Asset', $assets->id, false);
            $this->createAccounts($business,'1101', 'Main Operating Account', 'Asset', $cashHeader->id, true);

            // 1200 - Accounts Receivable (SYSTEM)
            $this->createAccounts($business, '1200','Accounts Receivable', 'Asset', $assets->id, true, true);

            // -- 2000 - LIABILITIES --
            $liabilities = $this->createAccounts($business, '2000','Liabilities', 'Liability', null, false);

            // 2100 - Accounts Payable (SYSTEM)
            $this->createAccounts($business, '2100','Accounts Payable', 'Liability', $liabilities->id, true, true);

            // -- 3000 - EQUITY --
            $equity = $this->createAccounts($business, '3000','Equity', 'Equity', null, false);
            $this->createAccounts($business, '3100', 'Opening Balance Equity', 'Equity', $equity->id, true, true);
            $this->createAccounts($business, '3200', 'Retained Earnings', 'Equity', $equity->id, true, true);

            // -- 4000 - INCOME --
            $income = $this->createAccounts($business, '4000','Income', 'Income', null, false);
            $this->createAccounts($business, '4100','Translation Services', 'Income', $income->id, true);

            // 4500 - FX Gain (SYSTEM)
            $this->createAccounts($business, '4500','Exchange Gain/Loss', 'Income', $income->id, true, true);

            // -- 5000 - EXPENSES --
            $expenses = $this->createAccounts($business, '5000','Expenses', 'Expense', null, false);
            $this->createAccounts($business, '5100','Freelancer Cost (COGS)', 'Expense', $expenses->id, true);
            $this->createAccounts($business, '5200', 'Bank Fee & Charges', 'Expense', $expenses->id, true);
        });
    }

    private function createAccounts(Business $business, string $code, string $name, string $type, ?int $parentId = null, bool $selectable = true, bool $system = false): Account
    {
        return Account::create([
            'business_id' => $business->id,
            'code' => $code,
            'name' => $name,
            'type' => $type,
            'parent_id' => $parentId,
            'is_selectable' => $selectable,
            'is_system' => $system,
        ]);
    }
}