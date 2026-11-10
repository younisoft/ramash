<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model {
 protected $guarded=[];
 protected function casts(): array {return ['is_visible'=>'boolean'];}
 public function scopeVisible($query){return $query->where('is_visible',true);}
 public function getLabelAttribute(){return app()->getLocale()==='en'?$this->name_en:$this->name;}
 public function products(){return $this->hasMany(Product::class);}
}
