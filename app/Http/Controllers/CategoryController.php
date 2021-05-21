<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->successResponse($categories, 'Get categories list successfully');
    }

    /**
     * Get pagination categories list
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request) {
        $query = DB::table('categories');

        $page = $request->get('page') ? (int)$request->get('page') : 1;
        $search = $request->get('search') ? strtolower($request->get('search')) : '';
        $numPerPage = $request->get('page_size') ? (int) $request->get('page_size') : env('ITEMS_PER_PAGE');

        $offset = ($page-1) * $numPerPage;
        
        if ($search) {
            $query->whereRaw("LOWER(name) LIKE '%$search%'");
        }

        $total = $query->count();

        $items = $query->offset($offset)->limit($numPerPage)->get();

        $data = [
            'items' => $items,
            'total' => $total,
            'page' => $page,
        ];

        return $this->successResponse($data, 'Get categories list successfully');
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
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->errors()->toJson(), 400);
        }

        $category = new Category([
            'name' => $inputs['name'],
            'desc' => $inputs['desc'],
        ]);

        $category->save();

        return $this->successResponse($category, 'Created category successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->errorResponse("Category was not found", 400);
        }

        return $this->successResponse($category, "Get category successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse("Category was not found", 400);
        }

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
        ]);

        if ($validator->fails()){
            return $this->errorResponse($validator->errors()->toJson(), 400);
        }

        $category->name = $inputs['name'];
        $category->desc = $inputs['desc'];

        $category->save();

        return $this->successResponse($category, 'Category has been updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse("Category was not found", 400);
        }

        $category->delete();

        return $this->successResponse($category, 'Category has been deleted successfully');
    }
}
