<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServicePrice;

class PricingService
{
    /**
     * Ambil harga layanan untuk cabang tertentu.
     * Urutan: service_prices(price) → fallback services.price_default.
     * Return string decimal 2 digit.
     */
    public function getPrice(string $serviceId, string $branchId): string
    {
        $override = ServicePrice::query()
            ->where('service_id', $serviceId)
            ->where('branch_id', $branchId)
            ->value('price');

        if ($override !== null) {
            return number_format((float) $override, 2, '.', '');
        }

        $default = Service::query()->where('id', $serviceId)->value('price_default') ?? '0.00';
        return number_format((float) $default, 2, '.', '');
    }
}
