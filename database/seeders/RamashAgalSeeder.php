<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Category,Product};
class RamashAgalSeeder extends Seeder {
 public function run(): void {
  $category=Category::where('slug','shemagh')->firstOrFail();
  $p=Product::firstOrCreate(['slug'=>'ramash-agal'],[
   'category_id'=>$category->id,'name'=>'عقال رماش','name_en'=>'Ramash Agal',
   'description'=>'حضور يكتمل بالتفاصيل. عقال أسود بتصميم دائري مزدوج يكمّل إطلالتك مع الشماغ أو الغترة. صورة تسويقية مولّدة للتصور البصري؛ تعتمد الخامة والمقاسات والسعر والتوفر قبل إتاحة الشراء. المقاسات المعروضة أولية.',
   'description_en'=>'Presence, completed by the details. A classic black double-loop agal to pair with a shemagh or ghutra. AI-generated concept photography; material, sizes, price and availability will be confirmed before purchase is enabled. Displayed sizes are provisional.',
   'material'=>'تُعتمد قبل الإطلاق','material_en'=>'To be confirmed before launch',
   'price'=>0,'image'=>'ramash-agal.jpg','gallery'=>['ramash-agal.jpg'],
   'featured'=>true,'tailored'=>false,'active'=>true
  ]);
  foreach(['51','52','53','54'] as $size)$p->variants()->firstOrCreate(['size'=>$size],['stock'=>0]);
 }
}
