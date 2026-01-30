<?php

namespace App\Service\Business;

use App\Models\Business;

class BusinessManager
{
    protected ?Business $business = null;

    public function setBusiness(Business $business): void
    {
        $this->business = $business;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function getBusinessId(): ?string
    {
        return $this->business?->id;
    }

    public function getBusinessName(): ?string
    {
        return $this->business?->name;
    }

    public function hasBusiness(): bool
    {
        return $this->business !== null;
    }
}
