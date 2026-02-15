<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::withSum('stocks', 'quantity')->withSum('stocks', 'reserved_qty')->get();
        return view('inventory.index', compact('products'));
    }
}
