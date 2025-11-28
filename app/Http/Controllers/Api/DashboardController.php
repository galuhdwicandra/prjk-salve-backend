<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardSummaryRequest;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function summary(DashboardSummaryRequest $request, DashboardService $service)
    {
        Gate::authorize('dashboard.summary');

        $data = $service->summary(
            $request->fromDate(),
            $request->toDate(),
            $request->branchId(),
        );

        return response()->json([
            'data' => $data,
            'meta' => [
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'branch_id' => $request->input('branch_id'),
            ],
            'message' => 'OK',
        ], Response::HTTP_OK);
    }
}
