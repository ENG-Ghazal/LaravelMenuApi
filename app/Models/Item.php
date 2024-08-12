<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Menu;
class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name','price','discount','category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function menus(){
        return $this->belongsToMany(Menu::class,'menu_items');
    }
    public function getEffectiveDiscount(): float
    {

        if ($this->discount) {
            return $this->discount;
        }
        $category = $this->category;
        while ($category) {
            if ($category->discount) {
                return $category->discount;
            }
            $category = $category->parentCategory;
        }
        return 0.0;
    }
}
