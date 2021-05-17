<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'categories.name as category_name')
                ->get();
        return $this->successResponse($products, 'Get products list successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->errors()->toJson());
        }

        $product = new Product([
            'name' => $inputs['name'],
            'category_id' => $inputs['category_id'],
            'price' => $inputs['price'],
        ]);

        $product->save();

        return $this->successResponse($product, 'Created product successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Product was not found');
        }
        return $this->successResponse($product, 'Get product detail successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            return $this->errorResponse('Product was not found');
        }

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()){
            return $this->errorResponse($validator->errors()->toJson());
        }

        $product->name = $inputs['name'];
        $product->price = $inputs['price'];
        $product->category_id = $inputs['category_id'];

        $product->save();

        return $this->successResponse($product, 'Product has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            return $this->errorResponse('Product was not found');
        }

        $product->delete();

        return $this->successResponse($product, 'Product has been deleted successfully');
    }
}
