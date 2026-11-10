<?php
namespace App\Http\Controllers;
use App\Models\ProductVariant;
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class CartController {
 public function index(Cart $cart){return view('cart',$cart->summary());}
 public function add(Request $request){
  $data=$request->validate(['variant_id'=>'required|integer|exists:product_variants,id','quantity'=>'required|integer|min:1|max:10','measurement'=>'nullable|in:previous,visit']);
  $variant=ProductVariant::with('product.category')->findOrFail($data['variant_id']);abort_unless($variant->product->store_visible && $variant->product->price>0,404);
  if($variant->product->tailored && empty($data['measurement'])) throw ValidationException::withMessages(['measurement'=>__('اختر طريقة أخذ المقاس')]);
  $measurement=$variant->product->tailored?$data['measurement']:null;
  $key=$variant->id.'-'.($measurement??'ready');$cart=session('cart',[]);
  $quantity=($cart[$key]['quantity']??0)+$data['quantity'];
  $allQuantity=$data['quantity'];foreach($cart as $line) if($line['variant_id']==$variant->id)$allQuantity+=$line['quantity'];
  if($allQuantity>$variant->stock || $quantity>10) throw ValidationException::withMessages(['quantity'=>__('الكمية المطلوبة غير متاحة')]);
  $cart[$key]=['variant_id'=>$variant->id,'quantity'=>$quantity,'measurement'=>$measurement];session(['cart'=>$cart]);
  return back()->with('success',__('أضيف المنتج إلى حقيبتك'));
 }
 public function update(Request $request,string $key){
  $data=$request->validate(['quantity'=>'required|integer|min:1|max:10']);$cart=session('cart',[]);abort_unless(isset($cart[$key]),404);
  $variant=ProductVariant::with('product.category')->findOrFail($cart[$key]['variant_id']);abort_unless($variant->product->store_visible && $variant->product->price>0,404);$total=$data['quantity'];foreach($cart as $k=>$line)if($k!==$key&&$line['variant_id']==$variant->id)$total+=$line['quantity'];
  if($total>$variant->stock)throw ValidationException::withMessages(['quantity'=>__('الكمية المطلوبة غير متاحة')]);
  $cart[$key]['quantity']=$data['quantity'];session(['cart'=>$cart]);return back()->with('success',__('تم تحديث الحقيبة'));
 }
 public function remove(string $key){$cart=session('cart',[]);unset($cart[$key]);session(['cart'=>$cart]);return back()->with('success',__('تم حذف المنتج'));}
}
