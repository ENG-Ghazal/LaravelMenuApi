<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Item;

class CategoryController extends Controller
{


    private function checkDepth(Category $category, $depth = 1) {
        if ($category->parent) {
            return $this->checkDepth($category->parent, $depth + 1);
        }

        return $depth;
    }
    public function index()
    {
        $categories = Category::with('parent')->get();
        $categoriesWithParentNames = $categories->map(function ($category) {

            $category->parent_name = $category->parent ? $category->parent->name : 'None';
            return $category;
        });

        return response()->json($categoriesWithParentNames);
    }

    public function createCategory(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'discount' => 'nullable'
        ]);

        $parent = $data['parent_id'] ? Category::find($data['parent_id']) : null;

        if ($parent) {
            if ($parent->hasItems()) {
                return response()->json(['error' => 'Parent category has items and cannot have subcategories.'], 400);
            }

            if ($this->checkDepth($parent) >= 4) {
                return response()->json(['error' => 'Exceeds maximum subcategory depth of 4 levels.'], 400);
            }
        }


        $category = Category::create($data);

        return response()->json([
            'category' => $category
        ], 201);
    }

    public function getItems(){

        $items = Item::all();
        return response()->json($items, 200);

    }
    public function createItem(Request $request) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required',
                'discount' =>'nullable'
            ]);

            $category = Category::find($data['category_id']);

            if ($category->hasSubcategories()) {
                return response()->json(['error' => 'Category has subcategories, cannot add items.'], 400);
            }

            $item = Item::create($data);

            return response()->json([
                'item' => $item
            ], 201);
        }




}
