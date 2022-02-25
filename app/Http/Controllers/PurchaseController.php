<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;

class PurchaseController extends Controller
{
    public function index(){
        $supplier = Supplier::orderBy('name')->get();
        return view('purchase.index', compact('supplier'));
    }

    public function create($id){
        $purchase = new Purchase();
        $purchase->id_supplier = $id;
        $purchase->total_item = 0;
        $purchase->total_price = 0;
        $purchase->discount = 0;
        $purchase->purcahese = 0;
        $purchase->save();

        session(['id_purchase'=> $purchase->id_purchase]);
        session(['id_supplier'=> $purchase->id_supplier]);

        return redirect()->route('purchase_detail.index');
    }
}
