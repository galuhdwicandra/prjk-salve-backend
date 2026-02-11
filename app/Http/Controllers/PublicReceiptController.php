<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicReceiptController extends Controller
{
    public function show(Request $request, Order $order)
    {
        $printedAt = now(); // atau Carbon::now()

        return view('orders.receipt', [
            'order' => $order,
            'printedAt' => $printedAt,
        ]);
    }
}
