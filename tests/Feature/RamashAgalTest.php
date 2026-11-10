<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
class RamashAgalTest extends TestCase {
 use RefreshDatabase;
 public function test_agal_is_visible_but_not_purchasable_until_priced():void{
  $this->seed();$p=Product::where('slug','ramash-agal')->firstOrFail();
  $this->assertEquals(0,$p->price);$this->assertEquals(0,$p->variants->sum('stock'));
  $this->get('/category/shemagh')->assertOk()->assertSee('عقال رماش');
  $this->get('/products/ramash-agal')->assertOk()->assertSee('السعر قريبًا')->assertSee('/images/ramash-agal.jpg');
  $v=$p->variants->first();$v->update(['stock'=>5]);
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1])->assertNotFound();
  $p->update(['price'=>29900]);
  $this->post('/cart',['variant_id'=>$v->id,'quantity'=>1])->assertRedirect();
  $this->get('/cart')->assertSee('عقال رماش');
  $p->category->update(['is_visible'=>false]);
  $this->get('/products/ramash-agal')->assertNotFound();
 }
}
