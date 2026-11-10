<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $guarded=[];
 public function scopeVisible($query){return $query->where('active',true)->whereHas('category',fn($q)=>$q->visible());}
 public function getStoreVisibleAttribute(): bool {return $this->active && (bool)$this->category?->is_visible;}
 protected function casts(): array {return ['gallery'=>'array','featured'=>'boolean','tailored'=>'boolean','active'=>'boolean'];}
 public function reviews(){return $this->hasMany(Review::class);}
 public function category(){return $this->belongsTo(Category::class);}
 public function variants(){return $this->hasMany(ProductVariant::class);}
 public function getLabelAttribute(){return app()->getLocale()==='en'?$this->name_en:$this->name;}
 public function getCopyAttribute(){return app()->getLocale()==='en'?$this->description_en:$this->description;}
 public function getFabricAttribute(){return app()->getLocale()==='en'?$this->material_en:$this->material;}
 public function getDiscountAttribute(){return $this->compare_price>$this->price ? (int)round((1-$this->price/$this->compare_price)*100):0;}
}
