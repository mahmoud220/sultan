<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;

class PurchaseDetailController extends Controller
{
    public function index(){
        $id_purchase = session('id_purchase');
        $product = Product::orderBy('product_name')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $discount = Purchase::find($id_purchase)->discount ?? 0;

//        return session('id_supplier');
        if (! $supplier) {
            abort(404);
        }

        return view('purchase_detail.index', compact('id_purchase', 'product', 'supplier', 'discount'));
    }
}
