<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Categories',
            'data' => $categories,
        ]);
    }
}
