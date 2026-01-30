<?php

namespace App\Traits;

use App\Models\Business;
use App\Models\Scopes\BusinessScope;
use App\Service\Business\BusinessManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
trait BelongsToBusiness
{
    protected static function bootBelongsToBusiness()
    {
        static::addGlobalScope(new BusinessScope());

        static::creating(function (Model $model) {

            $manager = app(BusinessManager::class);

            if(!$model->business_id && $manager->hasBusiness()) {
                $model->business_id = $manager->getBusinessId();
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
