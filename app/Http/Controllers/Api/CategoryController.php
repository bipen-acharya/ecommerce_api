<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryProduct as CategoryProductResource;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CategoryController extends BaseController
{
    public function getCategories(Request $request)
    {
        // $categoeries = Category::all();
        // return $this->sendResponse(CategoryResource::collection($categoeries), 'Category Fetched');
        $pageSize = $request->page_size ?? 5;
        // return response()->json($pageSize, 200);
        $categories = Category::orderby('id', 'desc')->paginate($pageSize);
        return CategoryResource::collection($categories);
    }

    public function getCategory($id)
    {
        $categoery = Category::find($id);
        if (is_null($categoery)) {
            return $this->sendError('Category does not exist.');
        }
        return $this->sendResponse(new CategoryResource($categoery), 'Single Category fetched.');
    }
    public function getCategoryDelete($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category does not exist.');
        }
        if ($category->category_image) {
            unlink('site/uploads/category/' . $category->category_image);
        }
        $category->delete();
        return $this->sendResponse('Hello', 'Category Deleted.');
    }
    public function postAddCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|unique:categories,category_name',
            'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }

        $category_name = $request->input('category_name');
        $slug = Str::slug($category_name);
        $category_image = $request->file('category_image');
        $category_description = $request->input('category_description');
        if ($category_image) {
            $uniquename = md5(time());
            $extension = $category_image->getClientOriginalExtension();
            $image_name = $uniquename . '.' . $extension;
            $category_image->move('site/uploads/category/', $image_name);
        }
        $category = new Category();
        $category->category_name = $category_name;
        $category->slug = $slug;
        $category->category_description = $category_description;
        if ($category_image) {
            $category->category_image = $image_name;
        }
        $category->save();
        return $this->sendResponse(new CategoryResource($category), ' Category Added');
    }


    public function postEditCategory($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'category_name' => 'required|unique:categories,category_name,',
            'category_name' => 'required|unique:categories,category_name,' . $id . ',id',
            'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category does not exist.');
        }

        $category_name = $request->input('category_name');
        $slug = Str::slug($category_name);
        $category_image = $request->file('category_image');
        $category_description = $request->input('category_description');
        if ($category_image) {
            $uniquename = md5(time());
            $extension = $category_image->getClientOriginalExtension();
            $image_name = $uniquename . '.' . $extension;
            $category_image->move('site/uploads/category/', $image_name);
            if ($category->category_image) {
                unlink('site/uploads/category/' . $category->category_image);
            }
        }


        $category->category_name = $category_name;
        $category->slug = $slug;
        $category->category_description = $category_description;
        if ($category_image) {
            $category->category_image = $image_name;
        }
        $category->save();
        return $this->sendResponse(new CategoryResource($category), ' Category Edited');
    }

    public function getProductsWithCategory($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category does not exist.');
        }
        // return CategoryProductResource::collection($category);
        // return $this->sendResponse(new CategoryProductResource($category), 'Single Category fetched.');
        // $products = Product::where('category_id', $category->id)->get();
        return response()->json($category->products, 200);
    }
}
