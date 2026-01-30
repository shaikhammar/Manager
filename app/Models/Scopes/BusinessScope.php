<?php

namespace App\Models\Scopes;

use App\Service\Business\BusinessManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BusinessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $manager = app(BusinessManager::class);

        if($manager->hasBusiness()) {
                $builder->where('business_id', $manager->getBusinessId());
            }
    }
}
