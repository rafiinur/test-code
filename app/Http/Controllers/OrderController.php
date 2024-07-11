<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $merchant = Auth::guard('merchant')->user();
        $orders = $merchant->orders()->with('customer')->get();
        return response()->json(['orders' => $orders], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'delivery_date' => 'required|date',
            'total_price' => 'required|numeric',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        $order = Order::create([
            'merchant_id' => $validatedData['merchant_id'],
            'customer_id' => Auth::id(),
            'delivery_date' => $validatedData['delivery_date'],
            'total_price' => $validatedData['total_price'],
        ]);
    
        foreach ($validatedData['items'] as $item) {
            $order->items()->create([
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => Menu::find($item['menu_id'])->price,
            ]);
        }
    
        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }
}
