<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{Category,Product,User};
class CategoryVisibilityTest extends TestCase {
 use RefreshDatabase;
 protected function setUp():void{parent::setUp();$this->seed();}
 public function test_only_admin_can_change_visibility_with_valid_input():void{
  $c=Category::where('slug','shemagh')->firstOrFail();$url='/admin/categories/'.$c->id.'/visibility';
  $this->get('/admin/categories')->assertRedirect('/login');
  $this->patch($url,['is_visible'=>0])->assertRedirect('/login');
  $u=User::create(['name'=>'User','email'=>'category@example.com','password'=>'long-password-123']);
  $this->actingAs($u)->get('/admin/categories')->assertForbidden();
  $this->patch($url,['is_visible'=>0])->assertForbidden();
  $this->assertTrue($c->fresh()->is_visible);
  $u->is_admin=true;$u->save();
  $this->get('/admin/categories')->assertOk()->assertSee('إدارة أقسام المتجر');
  $this->patch($url,['is_visible'=>'invalid'])->assertSessionHasErrors('is_visible');
  $this->patch($url,[])->assertSessionHasErrors('is_visible');
  $this->patch($url,['is_visible'=>0])->assertRedirect('/admin/categories');
  $this->assertFalse($c->fresh()->is_visible);
  $this->get('/admin/categories')->assertSee($c->name)->assertSee('إظهار القسم');
  $this->patch($url,['is_visible'=>1])->assertRedirect('/admin/categories');
  $this->assertTrue($c->fresh()->is_visible);
 }
 public function test_hidden_category_disappears_and_restores_without_data_loss():void{
  $p=Product::where('slug','classic-red-shemagh')->firstOrFail();$v=$p->variants->first();$c=$p->category;
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1])->assertRedirect();
  $this->post('/favorites/'.$p->id)->assertRedirect();
  $c->update(['is_visible'=>false]);
  $this->get('/')->assertOk()->assertDontSee('href="/category/shemagh"',false)->assertDontSee('id="shemaghs"',false);
  $this->get('/shop?q=شماغ')->assertOk()->assertDontSee($p->name);
  $this->get('/favorites')->assertDontSee($p->name);
  $this->get('/category/shemagh')->assertNotFound();
  $this->get('/products/'.$p->slug)->assertNotFound();
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1])->assertNotFound();
  $this->patch('/cart/'.$v->id.'-ready',['quantity'=>2])->assertNotFound();
  $this->get('/cart')->assertDontSee($p->name)->assertSee('حقيبتك تنتظر');
  $this->get('/checkout')->assertRedirect('/cart');
  $this->assertTrue($p->fresh()->active);$this->assertEquals(12,$v->fresh()->stock);
  $c->update(['is_visible'=>true]);
  $this->get('/category/shemagh')->assertOk()->assertSee($p->name);
  $this->get('/products/'.$p->slug)->assertOk();
  $this->get('/favorites')->assertSee($p->name);
  $this->get('/cart')->assertSee($p->name);
  Category::whereIn('slug',['thobes','perfumes'])->update(['is_visible'=>false]);
  $this->get('/')->assertOk()->assertDontSee('href="/category/thobes"',false)->assertDontSee('href="/category/perfumes"',false)->assertDontSee('class="editorial"',false);
  $this->post('/locale',['locale'=>'en']);
  $this->get('/')->assertOk()->assertDontSee('href="/category/thobes"',false);
 }
}
