<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoyaltySettingUpdateRequest;
use App\Models\LoyaltySetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltySettingController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return $this->respond($this->resolve($request));
    }

    public function update(LoyaltySettingUpdateRequest $request): JsonResponse
    {
        $setting = $this->resolve($request);
        $payload = $request->validated();

        $setting->fill([
            'target' => $payload['target'],
            'stamp_per' => $payload['stamp_per'],
            'rewards' => array_values($payload['rewards'] ?? []),
        ])->save();

        return $this->respond($setting->fresh(), 'Updated');
    }

    private function resolve(Request $request): LoyaltySetting
    {
        $branchId = $request->user()->branch_id ? (string) $request->user()->branch_id : null;

        return LoyaltySetting::query()->firstOrCreate(
            ['branch_id' => $branchId],
            ['target' => 10, 'stamp_per' => 'transaksi', 'rewards' => []],
        );
    }

    private function respond(LoyaltySetting $setting, string $message = 'OK'): JsonResponse
    {
        return response()->json([
            'data' => $setting,
            'meta' => (object) [],
            'message' => $message,
            'errors' => null,
        ]);
    }
}
