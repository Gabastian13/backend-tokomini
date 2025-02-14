<?php
/**
 *  Created By : Yufan Amri
 *  email : yufan.amri@gmail.com
 *  2025
 *
 */
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user,product')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('pre_page',10));

        return response()->json([
            'success' => true,
            'message' => 'List of Orders',
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'data' => []
            ], 404);
        }

        $totalPrice = $product->price * $request->quantity;

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);

        }

}
