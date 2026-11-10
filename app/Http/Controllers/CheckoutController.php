<?php
namespace App\Http\Controllers;
use App\Models\{Order,ProductVariant};
use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class CheckoutController {
 public function index(Cart $cart){$summary=$cart->summary();if(!$summary['lines'])return redirect('/cart');if(!session('checkout_token'))session(['checkout_token'=>(string)Str::uuid()]);return view('checkout',$summary);}
 public function store(Request $request,Cart $cart){
  $data=$request->validate(['name'=>'required|string|max:100','email'=>'required|email|max:190','phone'=>['required','regex:/^(05[0-9]{8}|\+9665[0-9]{8})$/'],'city'=>'required|in:جدة,الرياض,مكة,المدينة,الدمام,الخبر','address'=>'required|string|min:10|max:500','notes'=>'nullable|string|max:1000','terms'=>'accepted','checkout_token'=>'required|uuid']);
  $existing=Order::where('checkout_token',$data['checkout_token'])->first();
  if($existing){
   abort_unless(($request->user() && $existing->user_id === $request->user()->id) || in_array($existing->reference, session('guest_orders',[])), 404);
   return redirect()->route('order.show',$existing->reference);
  }
  abort_unless(hash_equals((string)session('checkout_token'),$data['checkout_token']),419);
  $summary=$cart->summary();if(!$summary['lines'])return redirect('/cart');
  $order=DB::transaction(function()use($request,$data,$summary){
   $subtotal=0;$items=[];
   foreach($summary['lines'] as $line){
    $v=ProductVariant::with('product.category')->lockForUpdate()->findOrFail($line['variant']->id);
    if(!$v->product->store_visible || $v->product->price<=0 || $v->stock<$line['quantity'])throw ValidationException::withMessages(['stock'=>__('تغير المخزون، راجع الحقيبة وحاول مجددًا')]);
    $updated=ProductVariant::whereKey($v->id)->where('stock','>=',$line['quantity'])->decrement('stock',$line['quantity']);
    if(!$updated)throw ValidationException::withMessages(['stock'=>__('الكمية المطلوبة غير متاحة')]);
    $subtotal+=$v->product->price*$line['quantity'];
    $items[]=['product_variant_id'=>$v->id,'name'=>$v->product->name,'size'=>$v->size,'measurement'=>$line['measurement'],'quantity'=>$line['quantity'],'price'=>$v->product->price];
   }
   $shipping=$subtotal>=config('store.free_shipping')?0:config('store.shipping');
   $order=Order::create(collect($data)->only(['name','email','phone','city','address','notes','checkout_token'])->all()+['reference'=>(string)Str::uuid(),'user_id'=>$request->user()?->id,'subtotal'=>$subtotal,'shipping'=>$shipping,'total'=>$subtotal+$shipping,'is_demo'=>config('store.demo'),'payment_status'=>'unpaid']);
   $order->items()->createMany($items);return $order;
  });
  session(['cart'=>[],'guest_orders'=>array_merge(session('guest_orders',[]),[$order->reference])]);
  // Keep the token for safe retries; the success page rotates it before the next checkout.
  return redirect()->route('order.show',$order->reference)->with('order_placed',true);
 }
 public function show(Request $request,string $reference){
  $order=Order::with('items')->where('reference',$reference)->firstOrFail();
  abort_unless(($request->user()&&$order->user_id===$request->user()->id)||in_array($reference,session('guest_orders',[])),404);
  if(session('checkout_token')===$order->checkout_token)session()->forget('checkout_token');
  return view('order',compact('order'));
 }
}
