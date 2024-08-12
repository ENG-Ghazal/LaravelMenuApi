<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','discount','parent_id'];

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function isLeaf() {
        return $this->children()->count() === 0 && $this->items()->count() === 0;
    }

    public function hasSubcategories() {
        return $this->children()->exists();
    }

    public function hasItems() {
        return $this->items()->exists();
    }
}
