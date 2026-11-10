<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Category,Product};
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $cats=[['thobes','ثياب وتفصيل','Thobes & tailoring','thobe-detail.jpg'],['shemagh','الأشمغة','Shemaghs','shemagh-red.jpg'],['casual','كاجوال','Everyday wear','shirt.jpg'],['outerwear','جاكيتات وسديري','Outerwear','jacket.jpg'],['perfumes','العطور','Fragrances','perfume.jpg'],['shoes','الأحذية','Footwear','shoes.jpg'],['leather','الشنط والإكسسوارات','Bags & accessories','bag.jpg']];
  foreach($cats as $i=>$c)Category::firstOrCreate(['slug'=>$c[0]],['name'=>$c[1],'name_en'=>$c[2],'image'=>$c[3],'sort'=>$i]);
  $items=[
   ['ivory-signature','ثوب سيجنتشر — عاجي','Signature thobe — ivory','thobes',79900,null,'thobe-ivory.jpg',true,true,'قطن ناعم','Soft cotton'],
   ['everyday-shirt','قميص إيفري داي','Everyday shirt','casual',24900,null,'shirt.jpg',true,false,'نسيج قطني','Cotton weave'],
   ['amber-fragrance','عطر أمبر — 100 مل','Amber fragrance — 100 ml','perfumes',32500,null,'perfume.jpg',true,false,'عنبر وخشب','Amber & wood'],
   ['leather-steps','حذاء كلاسيك','Classic shoes','shoes',44900,59900,'shoes.jpg',true,false,'جلد','Leather'],
   ['sand-tailored','ثوب رمل — تفصيل','Sand tailored thobe','thobes',89900,null,'thobe-sand.jpg',false,true,'مزيج قطني','Cotton blend'],
   ['city-jacket','جاكيت سيتي','City jacket','outerwear',59500,75000,'jacket.jpg',false,false,'نسيج متين','Durable fabric'],
   ['weekend-bag','حقيبة ويكند','Weekend bag','leather',39500,null,'bag.jpg',false,false,'نسيج مبطن','Lined fabric'],
   ['essential-tee','تيشيرت إسنشال','Essential T-shirt','casual',14900,null,'knit.jpg',false,false,'قطن','Cotton'],
   ['oud-evening','عطر مساء — 100 مل','Evening fragrance — 100 ml','perfumes',37500,null,'perfume.jpg',false,false,'خشبي دافئ','Warm woody notes'],
   ['winter-layer','جاكيت وينتر','Winter jacket','outerwear',65000,null,'jacket.jpg',false,false,'نسيج مبطن','Lined fabric'],
   ['daily-shoes','حذاء إيفري داي','Everyday shoes','shoes',29900,39900,'shoes.jpg',false,false,'جلد','Leather'],
   ['classic-shirt','قميص كلاسيك','Classic shirt','casual',27900,null,'shirt.jpg',false,false,'قطن','Cotton'],
   ['classic-red-shemagh','شماغ كلاسيك — أحمر','Classic shemagh — red','shemagh',14900,null,'shemagh-red.jpg',false,false,'قطن','Cotton'],
   ['heritage-red-shemagh','شماغ تراث — أحمر','Heritage shemagh — red','shemagh',39500,null,'shemagh-gift.jpg',false,false,'قطن فاخر بنقشة كثيفة','Fine cotton, dense pattern'],
   ['winter-red-shemagh','شماغ شتوي — أحمر','Winter shemagh — red','shemagh',24900,29900,'shemagh-red.jpg',false,false,'نسيج أثقل للشتاء','Heavier winter weave'],
   ['white-ghutra','غترة — أبيض','Ghutra — white','shemagh',11900,null,'ghutra-white.jpg',false,false,'قطن خفيف','Lightweight cotton']
  ];
  foreach($items as $i=>$p){$category=Category::where('slug',$p[3])->first();$product=Product::firstOrCreate(['slug'=>$p[0]],['category_id'=>$category->id,'name'=>$p[1],'name_en'=>$p[2],'price'=>$p[4],'compare_price'=>$p[5],'image'=>$p[6],'featured'=>$p[7],'tailored'=>$p[8],'material'=>$p[9],'material_en'=>$p[10],'description'=>'تفاصيل بسيطة، وحضور مختلف. قطعة مختارة لترافق يومك بسهولة، بتصميم متوازن يسهل تنسيقه مع مجموعتك. هذا منتج تجريبي لعرض تجربة المتجر؛ تعتمد المواصفات النهائية عند إضافة الكتالوج الحقيقي.','description_en'=>'Considered details, effortless style. A versatile piece selected to complement your everyday wardrobe. This is a demonstration product; final specifications will be supplied with the real catalog.','gallery'=>[$p[6]],'active'=>true]);
   $sizes=$p[8]?['تفصيل']:match($p[3]){'perfumes','leather','shemagh'=>['واحد'],'shoes'=>['40','41','42','43','44'],default=>['S','M','L','XL']};
   foreach($sizes as $size)$product->variants()->firstOrCreate(['size'=>$size],['stock'=>12]);
  }
  $this->call(RamashAgalSeeder::class);
 }
}
