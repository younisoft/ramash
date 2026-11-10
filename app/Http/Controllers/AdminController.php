<?php
namespace App\Http\Controllers;
use App\Models\{Product,Category,Order,Review};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminController {
 public function categories(){return view('admin.categories',['categories'=>Category::withCount('products')->orderBy('sort')->get()]);}
 public function categoryVisibility(Request $request, Category $category){
  $data=$request->validate(['is_visible'=>'required|boolean']);
  $category->update($data);
  return redirect('/admin/categories')->with('success',$category->is_visible?'تم إظهار القسم في المتجر':'تم إخفاء القسم ومنتجاته من المتجر');
 }
 public function index(){return view('admin.index',['reviews'=>Review::with('product','user')->where('approved',false)->latest()->get(),'products'=>Product::with('variants')->latest()->get(),'orders'=>Order::with('items')->latest()->paginate(20),'inquiries'=>DB::table('inquiries')->latest()->limit(30)->get()]);}
 public function review(Review $review){$review->update(['approved'=>true]);return back()->with('success','تم نشر التقييم');}
 public function create(){return view('admin.create',['categories'=>Category::all()]);}
 public function store(Request $r){
  $d=$r->validate(['name'=>'required|string|max:150','name_en'=>'required|string|max:150','slug'=>'required|alpha_dash:ascii|max:120|unique:products,slug','description'=>'required|string|max:5000','description_en'=>'required|string|max:5000','price'=>'required|numeric|min:1|max:100000','category_id'=>'required|exists:categories,id','material'=>'required|string|max:150','material_en'=>'required|string|max:150','sizes'=>'required|string|max:200','stock'=>'required|integer|min:0|max:10000','image'=>'required|image|mimes:jpg,jpeg,png,webp|max:5120']);
  $filename=(string)\Illuminate\Support\Str::uuid().'.'.$r->file('image')->extension();
  $r->file('image')->move(public_path('images'),$filename);
  DB::transaction(function()use($d,$r,$filename){
   $p=Product::create(collect($d)->except(['price','stock','sizes','image'])->all()+['price'=>(int)round($d['price']*100),'image'=>$filename,'tailored'=>$r->boolean('tailored'),'active'=>true,'featured'=>$r->boolean('featured')]);
   $sizes=$p->tailored?['تفصيل']:array_unique(array_filter(array_map('trim',explode(',',$d['sizes']))));
   if(!$sizes)$sizes=['واحد'];
   foreach($sizes as $size)$p->variants()->create(['size'=>mb_substr($size,0,50),'stock'=>$d['stock']]);
  });
  return redirect('/admin')->with('success','تمت إضافة المنتج');
 }
 public function edit(Product $product){return view('admin.product',compact('product')+['categories'=>Category::all()]);}
 public function update(Request $r,Product $product){$d=$r->validate(['name'=>'required|string|max:150','name_en'=>'required|string|max:150','description'=>'required|string|max:5000','description_en'=>'required|string|max:5000','price'=>'required|numeric|min:1|max:100000','compare_price'=>'nullable|numeric|gt:price|max:100000','category_id'=>'required|exists:categories,id','stocks'=>'required|array','stocks.*'=>'required|integer|min:0|max:10000']);DB::transaction(function()use($r,$d,$product){$product->update(collect($d)->except(['stocks','price','compare_price'])->all()+['price'=>(int)round($d['price']*100),'compare_price'=>empty($d['compare_price'])?null:(int)round($d['compare_price']*100),'active'=>$r->boolean('active'),'featured'=>$r->boolean('featured')]);foreach($d['stocks'] as $id=>$stock)$product->variants()->whereKey($id)->update(['stock'=>$stock]);});return redirect('/admin')->with('success',__('تم حفظ المنتج'));}
 public function status(Request $r,Order $order){$d=$r->validate(['status'=>'required|in:pending,processing,shipped,completed,cancelled']);DB::transaction(function()use($order,$d){$locked=Order::lockForUpdate()->findOrFail($order->id);$allowed=['pending'=>['processing','cancelled'],'processing'=>['shipped','cancelled'],'shipped'=>['completed'],'completed'=>[],'cancelled'=>[]];abort_unless(in_array($d['status'],$allowed[$locked->status]??[]),422);if($d['status']==='cancelled')foreach($locked->items as $i)DB::table('product_variants')->where('id',$i->product_variant_id)->increment('stock',$i->quantity);$locked->update($d);});return back()->with('success',__('تم تحديث الطلب'));}
}
