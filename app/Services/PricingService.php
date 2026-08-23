<?php
namespace App\Services;

use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Validation\ValidationException;

class PricingService
{
    /**
     * Ambil harga layanan untuk cabang tertentu.
     * Harga wajib berasal dari service_prices milik cabang tersebut.
     * Return string decimal 2 digit.
     */
    public function getPrice(string $serviceId, string $branchId): string
    {
        $override = ServicePrice::query()
            ->where('service_id', $serviceId)
            ->where('branch_id', $branchId)
            ->value('price');

        if ($override === null) {
            throw ValidationException::withMessages([
                'items' => ['Layanan tidak tersedia di cabang ini.'],
            ]);
        }

        return number_format((float) $override, 2, '.', '');
    }
}
