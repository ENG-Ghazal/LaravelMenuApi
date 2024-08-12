<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Menu;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubcategoryRequest;
use App\Http\Requests\ItemRequest;
class MenuController extends Controller
{

    public function index(){
        $menus = Menu::all();
      return response()->json($menus, 201);
    }
    public function createMenu(Request $request) {
        $data = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id',
        ]);

        $menu = Menu::create([
            'user_id' => auth()->id(),
        ]);

        $menu->items()->attach($data['item_ids']);

        return response()->json($menu->load('items'), 201);
    }

    public function getMenu($id) {
        $menu = Menu::with('items')->findOrFail($id);

        if ($menu->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($menu);
    }


    public function calculateTotalPrice(Request $request)
    {
        $itemIds = $request->input('item_ids');
        $items = Item::whereIn('id', $itemIds)->get();

        $totalPrice = 0.0;
        foreach ($items as $item) {
            $price = $item->price;
            $discount = $item->getEffectiveDiscount();
            $totalPrice += $price * (1 - $discount / 100); 
        }

        return response()->json([
            'total_price' => $totalPrice
        ]);
    }
}
