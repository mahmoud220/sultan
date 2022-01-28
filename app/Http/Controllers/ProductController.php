<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all()->pluck('category_name', 'id_category');

        return view('products.index', compact('category'));
    }

    public function data()
    {
        $product = Product::leftJoin('categories', 'categories.id_category', 'products.id_category')
            ->select('products.*', 'category_name')
            ->orderBy('id_product', 'asc')
            ->get();
        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                    <input type="checkbox" name="id_product[]" value="'. $product->id_product .'">
                ';
            })
            ->addColumn('code_product', function ($product) {
                return '<span class="label label-success">'. $product->code_product .'</span>';
            })
            ->addColumn('purchas_price', function ($product) {
                return format_uang($product->purchas_price);
            })
            ->addColumn('sell_price', function ($product) {
                return format_uang($product->sell_price);
            })
            ->addColumn('stock', function ($product) {
                return format_uang($product->stok);
            })
            ->addColumn('Added', function ($product) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('products.update', $product->id_product) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('products.destroy', $product->id_product) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['Added', 'code_product', 'select_all'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::latest()->first() ?? new Product();
        $request['code_product'] = 'P'. add_zero_front((int)$product->id_product +1, 6);

        $product = Product::create($request->all());

        return response()->json('Data Added Succfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());

        return response()->json('Data Updated Succfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_product as $id) {
            $product = Product::find($id);
            $product->delete();
        }

        return response(null, 204);
    }

    public function printBarcode(Request $request)
    {
        $dataproduct = array();
        foreach ($request->id_product as $id) {
            $product = Product::find($id);
            $dataproduct[] = $product;
        }

        $no  = 1;
        $pdf = PDF::loadView('products.barcode', compact('dataproduct', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('product.pdf');
    }
}
