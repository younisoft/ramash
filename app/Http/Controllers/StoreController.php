<?php
namespace App\Http\Controllers;
use App\Models\{Category,Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class StoreController {
 public function home(){return view('home',['featured'=>Product::with('category','variants')->visible()->where('featured',true)->take(4)->get(),'categories'=>Category::visible()->orderBy('sort')->get(),'shemaghs'=>Product::with('category','variants')->visible()->whereHas('category',fn($q)=>$q->where('slug','shemagh'))->orderByDesc('featured')->orderBy('id')->take(4)->get(),'newProducts'=>Product::with('category','variants')->visible()->latest('id')->take(4)->get()]);}
 public function catalog(Request $request, ?string $category=null){
  $selected=$category?Category::visible()->where('slug',$category)->firstOrFail():null;
  $query=Product::with('category','variants')->visible();
  if($selected) $query->where('category_id',$selected->id);
  $filters=$request->validate(['q'=>'nullable|string|max:100','sort'=>'nullable|in:new,popular,price_asc,price_desc','min'=>'nullable|numeric|min:0|max:100000','max'=>'nullable|numeric|min:0|max:100000','type'=>'nullable|in:ready,tailored','sale'=>'nullable|in:1']);
  if($q=$request->input('q')) $query->where(fn($b)=>$b->where('name','like','%'.$q.'%')->orWhere('name_en','like','%'.$q.'%')->orWhere('description','like','%'.$q.'%'));
  if($request->filled('min'))$query->where('price','>=',(int)round($request->min*100));
  if($request->filled('max'))$query->where('price','<=',(int)round($request->max*100));
  if($request->filled('type'))$query->where('tailored',$request->type==='tailored');
  if($request->boolean('sale'))$query->whereColumn('compare_price','>','price');
  match($request->sort){'popular'=>$query->orderByDesc(DB::table('order_items')->join('product_variants','product_variants.id','=','order_items.product_variant_id')->join('orders','orders.id','=','order_items.order_id')->where('orders.status','!=','cancelled')->whereColumn('product_variants.product_id','products.id')->selectRaw('coalesce(sum(order_items.quantity),0)'))->latest('id'),'price_asc'=>$query->orderBy('price'),'price_desc'=>$query->orderByDesc('price'),default=>$query->latest('id')};
  return view('catalog',['products'=>$query->paginate(12)->withQueryString(),'selected'=>$selected,'categories'=>Category::visible()->orderBy('sort')->get()]);
 }
 public function product(string $slug){$product=Product::with(['variants','category','reviews'=>fn($q)=>$q->where('approved',true)->with('user')->latest()])->visible()->where('slug',$slug)->firstOrFail();return view('product',compact('product')+['related'=>Product::with(['variants','category','reviews'=>fn($q)=>$q->where('approved',true)->with('user')->latest()])->visible()->where('category_id',$product->category_id)->whereKeyNot($product->id)->take(4)->get()]);}
 public function review(Request $request,Product $product){
  abort_unless($product->store_visible,404);$data=$request->validate(['rating'=>'required|integer|min:1|max:5','body'=>'required|string|min:10|max:2000']);
  $product->reviews()->updateOrCreate(['user_id'=>$request->user()->id],$data+['approved'=>false]);return back()->with('success',__('شكرًا، سيظهر تقييمك بعد المراجعة'));
 }
 public function favorites(){return view('favorites',['products'=>Product::with('category','variants')->visible()->whereIn('id',session('favorites',[]))->get()]);}
 public function favorite(Product $product){abort_unless($product->store_visible,404);$ids=session('favorites',[]);$ids=in_array($product->id,$ids)?array_values(array_diff($ids,[$product->id])):array_merge($ids,[$product->id]);session(['favorites'=>$ids]);return back()->with('success',__('تم تحديث المفضلة'));}
 public function page(string $page){abort_unless(in_array($page,['about','sizes','shipping','returns','privacy','contact','branches']),404);return view('page',compact('page'));}
 public function contact(Request $request){$data=$request->validate(['name'=>'required|string|max:100','email'=>'required|email|max:190','message'=>'required|string|min:10|max:3000']);DB::table('inquiries')->insert($data+['created_at'=>now(),'updated_at'=>now()]);return back()->with('success',__('تم حفظ رسالتك بنجاح'));}
}
