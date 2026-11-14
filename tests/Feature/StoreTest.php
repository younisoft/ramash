<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Product,User,Order};
use Illuminate\Support\Str;
class StoreTest extends TestCase {
 use RefreshDatabase;
 protected function setUp():void{parent::setUp();$this->seed();}
 private function variant(string $slug='everyday-shirt'){return Product::where('slug',$slug)->first()->variants()->first();}
 private function checkoutData(string $token):array{return ['name'=>'Test Customer','email'=>'test@example.com','phone'=>'0501234567','city'=>'جدة','address'=>'Test street, Building 12','terms'=>'1','checkout_token'=>$token];}
 public function test_public_pages_render_in_arabic_and_english():void{
  foreach(['/','/shop','/category/thobes','/category/shemagh','/products/ivory-signature','/cart','/favorites','/login','/register','/pages/about','/pages/sizes','/pages/shipping','/pages/returns','/pages/privacy','/pages/branches','/pages/contact'] as $url)$this->get($url)->assertOk();
  $this->get('/')->assertSee('/images/ramash-horizontal-ar.svg')->assertDontSee('/images/ramash-horizontal-en.svg');
  $this->post('/locale',['locale'=>'en'])->assertRedirect();$this->get('/')->assertOk()->assertSee('Your presence,')->assertSee('dir="ltr"',false)->assertSee('/images/ramash-horizontal-en.svg')->assertDontSee('/images/ramash-horizontal-ar.svg');
  $this->post('/locale',['locale'=>'ar'])->assertRedirect();$this->get('/')->assertSee('/images/ramash-horizontal-ar.svg')->assertDontSee('/images/ramash-horizontal-en.svg');
 }
 public function test_search_price_and_type_filters():void{
  $this->get('/shop?'.http_build_query(['q'=>'سيجنتشر']))->assertOk()->assertSee('ثوب سيجنتشر')->assertDontSee('قميص إيفري داي');
  $this->get('/shop?type=tailored&max=800')->assertOk()->assertSee('ثوب سيجنتشر')->assertDontSee('ثوب رمل');
  $this->get('/shop?sort=price_asc')->assertOk();$this->get('/shop?q=nonexistentpiece')->assertSee('لم نجد قطعة');
 }
 public function test_cart_uses_server_price_and_enforces_stock():void{
  $v=$this->variant();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1,'price'=>1])->assertRedirect();
  $this->get('/cart')->assertOk()->assertSee('249');
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>10])->assertSessionHasErrors('quantity');
  $this->patch('/cart/'.$v->id.'-ready',['quantity'=>2])->assertRedirect();
  $this->get('/cart')->assertSee('498')->assertSee('طلبك يستحق شحنًا مجانيًا');
  $this->delete('/cart/'.$v->id.'-ready')->assertRedirect();$this->get('/cart')->assertSee('حقيبتك تنتظر');
 }
 public function test_tailoring_requires_measurement_method():void{
  $v=$this->variant('ivory-signature');$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1])->assertSessionHasErrors('measurement');
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1,'measurement'=>'visit'])->assertRedirect();$this->get('/cart')->assertSee('مقاس جديد');
 }
 public function test_stock_is_validated_across_multiple_measurement_lines():void{
  $v=$this->variant('ivory-signature');$v->update(['stock'=>2]);
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>2,'measurement'=>'visit']);
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1,'measurement'=>'previous'])->assertSessionHasErrors('quantity');
 }
 public function test_seo_robots_sitemap_and_structured_data():void{
  $this->get('/google0e964b9cf4dd4bb9.html')->assertNotFound(); // static file, served by the web server
  $this->assertFileExists(public_path('google0e964b9cf4dd4bb9.html'));
  $this->get('/robots.txt')->assertOk()->assertHeader('Content-Type','text/plain; charset=UTF-8')->assertSee('Disallow: /admin')->assertSee('Sitemap: '.url('/sitemap.xml'));
  $sitemap=$this->get('/sitemap.xml')->assertOk()->assertSee(url('/category/shemagh'))->assertSee(route('product','ivory-signature'))->assertSee(url('/pages/privacy'))->getContent();
  $this->assertNotFalse(simplexml_load_string($sitemap));
  $this->get('/')->assertSee('<meta name="robots" content="index,follow,max-image-preview:large">',false)->assertSee('"@type":"Organization"',false)->assertSee('"@type":"WebSite"',false)->assertSee('og:image',false);
  $this->get('/products/ivory-signature')->assertSee('"@type":"Product"',false)->assertSee('"price":"799.00"',false)->assertSee('<meta property="og:type" content="product">',false);
  $this->get('/shop?q=test')->assertSee('<meta name="robots" content="noindex,follow">',false)->assertSee('<link rel="canonical" href="'.url('/shop').'">',false);
  $this->get('/cart')->assertSee('noindex,follow');
  config(['store.indexable'=>false]);
  $this->get('/')->assertSee('<meta name="robots" content="noindex,nofollow">',false);
  $this->get('/robots.txt')->assertSee('Disallow: /');
 }
 public function test_policy_page_and_footer():void{
  $this->get('/pages/privacy')->assertOk()->assertSee('سياسة الاستخدام والخصوصية')->assertSee('حقوقك على بياناتك')->assertSee('375 ر.س')->assertSee('الهيئة السعودية للبيانات والذكاء الاصطناعي');
  $this->get('/')->assertOk()->assertSee('روابط سريعة')->assertSee('خدمات العملاء')->assertSee('وسائل التواصل')->assertSee('title="Visa"',false)->assertSee('title="mada"',false)->assertSee('title="Tabby"',false)->assertSee('طرق الدفع المتاحة عند الإطلاق');
  config(['store.social.instagram'=>'https://instagram.com/example']);$this->get('/')->assertSee('href="https://instagram.com/example"',false);
  $this->post('/locale',['locale'=>'en']);$this->get('/pages/privacy')->assertOk()->assertSee('Terms of use &amp; privacy',false)->assertSee('Your data rights')->assertSee('Quick links');
 }
 public function test_coming_soon_badge_shows_on_all_pages_and_can_be_disabled():void{
  foreach(['/','/shop','/products/ivory-signature','/cart','/login','/pages/about'] as $url)$this->get($url)->assertOk()->assertSee('<span class="soon-badge">قريباً</span>',false);
  config(['store.coming_soon'=>false]);$this->get('/')->assertOk()->assertDontSee('soon-badge');
 }
 public function test_shemagh_category_and_best_selling_sort():void{
  $this->get('/category/shemagh')->assertOk()->assertSee('الأشمغة')->assertSee('5 منتج')->assertSee('شماغ تراث')->assertSee('غترة')->assertDontSee('ثوب سيجنتشر');
  $this->get('/category/shemagh?sort=price_asc')->assertOk()->assertSeeInOrder(['غترة','شماغ كلاسيك','شماغ شتوي','شماغ تراث']);
  $sell=function(string $slug,int $qty,string $status){$v=$this->variant($slug);$o=Order::create(['reference'=>Str::random(10),'checkout_token'=>(string)Str::uuid(),'name'=>'T','email'=>'t@example.com','phone'=>'0501234567','city'=>'جدة','address'=>'Test street','subtotal'=>0,'shipping'=>0,'total'=>0,'status'=>$status]);$o->items()->create(['product_variant_id'=>$v->id,'name'=>'x','size'=>$v->size,'quantity'=>$qty,'price'=>0]);};
  $sell('classic-red-shemagh',3,'pending');$sell('heritage-red-shemagh',1,'completed');$sell('white-ghutra',9,'cancelled');
  $this->get('/category/shemagh?sort=popular')->assertOk()->assertSeeInOrder(['شماغ كلاسيك','شماغ تراث','غترة']);
  $this->get('/shop?sort=invalid')->assertSessionHasErrors('sort');
 }
 public function test_checkout_creates_order_and_decrements_stock_once():void{
  $v=$this->variant();$token=(string)Str::uuid();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1]);
  $data=$this->checkoutData($token);$response=$this->withSession(['checkout_token'=>$token])->post('/checkout',$data);$response->assertRedirect();
  $order=Order::firstOrFail();$this->assertEquals(27400,$order->total);$this->assertEquals('unpaid',$order->payment_status);$this->assertEquals(11,$v->fresh()->stock);
  $this->get('/orders/'.$order->reference)->assertOk()->assertSee('اختياراتك وصلت إلينا');
  $this->post('/checkout',$data)->assertRedirect('/orders/'.$order->reference);$this->assertEquals(1,Order::count());$this->assertEquals(11,$v->fresh()->stock);
 }
 public function test_checkout_revalidates_stock_and_rolls_back():void{
  $v=$this->variant();$token=(string)Str::uuid();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>2]);$v->update(['stock'=>1]);
  $this->withSession(['checkout_token'=>$token])->post('/checkout',$this->checkoutData($token))->assertSessionHasErrors('stock');
  $this->assertEquals(0,Order::count());$this->assertEquals(1,$v->fresh()->stock);
 }
 public function test_shipping_boundary_is_inclusive():void{
  $v=$this->variant('oud-evening');$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1]);$token=(string)Str::uuid();
  $this->withSession(['checkout_token'=>$token])->post('/checkout',$this->checkoutData($token))->assertRedirect();
  $this->assertEquals(0,Order::first()->shipping);$this->assertEquals(37500,Order::first()->total);
 }
 public function test_order_cannot_be_viewed_by_another_session():void{
  $v=$this->variant();$token=(string)Str::uuid();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1]);$this->withSession(['checkout_token'=>$token])->post('/checkout',$this->checkoutData($token));$order=Order::first();
  $this->flushSession();$this->get('/orders/'.$order->reference)->assertNotFound();
 }
 public function test_register_login_and_admin_access():void{
  $this->post('/register',['name'=>'Test','email'=>'shopper@example.com','password'=>'long-password-123','password_confirmation'=>'long-password-123','terms'=>'1'])->assertRedirect('/account');
  $this->get('/account')->assertOk();$this->get('/admin')->assertForbidden();$user=User::first();$this->assertFalse($user->is_admin);
  $this->post('/logout')->assertRedirect('/');$this->get('/account')->assertRedirect('/login');
  $this->post('/login',['email'=>'shopper@example.com','password'=>'long-password-123'])->assertRedirect('/account');
 }
 public function test_admin_can_update_price_and_stock():void{
  $user=User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'long-password-123']);$user->is_admin=true;$user->save();$p=Product::first();$v=$p->variants->first();
  $this->actingAs($user)->get('/admin')->assertOk();$this->get('/admin/products/'.$p->id)->assertOk();
  $this->put('/admin/products/'.$p->id,['name'=>$p->name,'name_en'=>$p->name_en,'description'=>$p->description,'description_en'=>$p->description_en,'price'=>123.45,'category_id'=>$p->category_id,'stocks'=>[$v->id=>7],'active'=>1])->assertRedirect('/admin');
  $this->assertEquals(12345,$p->fresh()->price);$this->assertEquals(7,$v->fresh()->stock);
 }
 public function test_favorites_contact_and_hidden_product():void{
  $p=Product::first();$this->post('/favorites/'.$p->id)->assertRedirect();$this->get('/favorites')->assertSee($p->name);
  $this->post('/contact',['name'=>'Test','email'=>'test@example.com','message'=>'Please help with product sizing.'])->assertRedirect();$this->assertDatabaseCount('inquiries',1);
  $p->update(['active'=>false]);$this->get('/products/'.$p->slug)->assertNotFound();
 }

 public function test_reviews_require_login_and_moderation():void{
  $p=Product::first();$this->post('/products/'.$p->id.'/reviews',['rating'=>5,'body'=>'Excellent sample product.'])->assertRedirect('/login');
  $u=User::create(['name'=>'Reviewer','email'=>'reviewer@example.com','password'=>'long-password-123']);
  $this->actingAs($u)->post('/products/'.$p->id.'/reviews',['rating'=>5,'body'=>'Excellent sample product.'])->assertRedirect();
  $this->get('/products/'.$p->slug)->assertDontSee('Excellent sample product.');
  $u->is_admin=true;$u->save();$review=\App\Models\Review::first();
  $this->patch('/admin/reviews/'.$review->id)->assertRedirect();$this->get('/products/'.$p->slug)->assertSee('Excellent sample product.');
 }
 public function test_admin_creates_product_with_validated_image():void{
  $u=User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'long-password-123']);$u->is_admin=true;$u->save();
  $this->actingAs($u)->get('/admin/products/create')->assertOk();
  $data=['name'=>'منتج اختبار','name_en'=>'Test product','slug'=>'test-product','description'=>'Test description','description_en'=>'Test description','price'=>199,'category_id'=>\App\Models\Category::where('slug','thobes')->value('id'),'material'=>'قطن','material_en'=>'Cotton','sizes'=>'S,M,L','stock'=>4,'image'=>\Illuminate\Http\UploadedFile::fake()->image('product.jpg')];
  $this->post('/admin/products',$data)->assertRedirect('/admin');
  $p=Product::where('slug','test-product')->firstOrFail();$this->assertEquals(3,$p->variants()->count());$this->assertEquals(19900,$p->price);
  $this->assertFileExists(public_path('images/'.$p->image));unlink(public_path('images/'.$p->image));
 }
 public function test_cancelling_restores_stock_once_and_rejects_invalid_transition():void{
  $v=$this->variant();$token=(string)Str::uuid();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1]);$this->withSession(['checkout_token'=>$token])->post('/checkout',$this->checkoutData($token));$order=Order::first();
  $u=User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>'long-password-123']);$u->is_admin=true;$u->save();
  $this->actingAs($u)->patch('/admin/orders/'.$order->id,['status'=>'cancelled'])->assertRedirect();$this->assertEquals(12,$v->fresh()->stock);
  $this->patch('/admin/orders/'.$order->id,['status'=>'cancelled'])->assertStatus(422);$this->assertEquals(12,$v->fresh()->stock);
 }
 public function test_invalid_checkout_token_and_customer_fields_are_rejected():void{
  $v=$this->variant();$this->post('/cart',['variant_id'=>$v->id,'quantity'=>1]);$token=(string)Str::uuid();
  $this->withSession(['checkout_token'=>(string)Str::uuid()])->post('/checkout',$this->checkoutData($token))->assertStatus(419);
  $data=$this->checkoutData($token);$data['phone']='123';$this->post('/checkout',$data)->assertSessionHasErrors('phone');$this->assertDatabaseCount('orders',0);
 }
 public function test_checkout_rolls_back_earlier_item_when_later_item_has_no_stock():void{
  $first=$this->variant();$second=$this->variant('classic-shirt');$token=(string)Str::uuid();
  $this->post('/cart',['variant_id'=>$first->id,'quantity'=>1]);$this->post('/cart',['variant_id'=>$second->id,'quantity'=>1]);$second->update(['stock'=>0]);
  $this->withSession(['checkout_token'=>$token])->post('/checkout',$this->checkoutData($token))->assertSessionHasErrors('stock');
  $this->assertEquals(12,$first->fresh()->stock);$this->assertDatabaseCount('orders',0);
 }
 public function test_invalid_product_and_non_admin_mutations_are_rejected():void{
  $p=Product::first();$this->get('/products/not-a-product')->assertNotFound();
  $this->post('/cart',['variant_id'=>99999,'quantity'=>1])->assertSessionHasErrors('variant_id');
  $u=User::create(['name'=>'Shopper','email'=>'shopper@example.com','password'=>'long-password-123']);
  $this->actingAs($u)->put('/admin/products/'.$p->id,['price'=>1])->assertForbidden();
  $this->assertEquals(79900,$p->fresh()->price);
 }
}
