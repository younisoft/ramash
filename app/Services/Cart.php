<?php
namespace App\Services;
use App\Models\ProductVariant;
class Cart {
 public function summary(): array {
  $lines=[];$subtotal=0;
  foreach(session('cart',[]) as $key=>$entry){
   $variant=ProductVariant::with('product.category')->find($entry['variant_id']);
   if(!$variant || !$variant->product->store_visible || $variant->product->price<=0) continue;
   $line=['key'=>$key,'variant'=>$variant,'product'=>$variant->product,'quantity'=>$entry['quantity'],'measurement'=>$entry['measurement']??null,'total'=>$variant->product->price*$entry['quantity']];
   $lines[]=$line;$subtotal+=$line['total'];
  }
  $shipping=$subtotal>0 && $subtotal<config('store.free_shipping')?config('store.shipping'):0;
  return compact('lines','subtotal','shipping')+['total'=>$subtotal+$shipping,'remaining'=>max(0,config('store.free_shipping')-$subtotal)];
 }
}
