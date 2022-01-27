<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

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

    public function data(){
        $product = Product::orderBy('id_product', 'desc')->get();
        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('Added', function ($product) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('products.update', $product->id_product) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('products.destroy', $product->id_product) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['Added'])
            ->make(true);
    }

    // public function data()
    // {
    //     $product = Product::leftJoin('categories', 'categories.id_category', 'products.id_category')
    //         ->select('produk.*', 'category_name')
    //         ->get();

    //     return datatables()
    //         ->of($product)
    //         ->addIndexColumn()
    //         ->addColumn('select_all', function ($produk) {
    //             return '
    //                 <input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">
    //             ';
    //         })
    //         ->addColumn('kode_produk', function ($produk) {
    //             return '<span class="label label-success">'. $produk->kode_produk .'</span>';
    //         })
    //         ->addColumn('harga_beli', function ($produk) {
    //             return format_uang($produk->harga_beli);
    //         })
    //         ->addColumn('harga_jual', function ($produk) {
    //             return format_uang($produk->harga_jual);
    //         })
    //         ->addColumn('stok', function ($produk) {
    //             return format_uang($produk->stok);
    //         })
    //         ->addColumn('aksi', function ($produk) {
    //             return '
    //             <div class="btn-group">
    //                 <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
    //                 <button type="button" onclick="deleteData(`'. route('produk.destroy', $produk->id_produk) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
    //             </div>
    //             ';
    //         })
    //         ->rawColumns(['aksi', 'kode_produk', 'select_all'])
    //         ->make(true);
    // }

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
        $product = Product::latest()->first();
        $request['code_product'] = 'P-'. add_zero_front($product->id_product, 6);
        
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
