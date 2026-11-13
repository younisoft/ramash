<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RamashAccessoriesSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'leather')->firstOrFail();
        $products = [
            [
                'slug' => 'ramash-classic-watch',
                'name' => 'ساعة رماش كلاسيك',
                'name_en' => 'Ramash Classic Watch',
                'description' => 'ساعة رجالية بمينا سوداء مستديرة وسوار معدني فضي.',
                'description_en' => 'A men’s watch with a round black dial and a silver-tone metal bracelet.',
            ],
            [
                'slug' => 'ramash-black-pen',
                'name' => 'قلم رماش الأسود',
                'name_en' => 'Ramash Black Pen',
                'description' => 'قلم أسود بتفاصيل فضية، بتصميم يناسب المكتب والهدايا.',
                'description_en' => 'A black pen with silver-tone details, suited to the desk or a gift.',
            ],
            [
                'slug' => 'ramash-wooden-misbaha',
                'name' => 'سبحة رماش الخشبية',
                'name_en' => 'Ramash Wooden Prayer Beads',
                'description' => 'سبحة بخرز خشبي داكن وشرّابة قصيرة.',
                'description_en' => 'Dark wooden prayer beads finished with a short tassel.',
            ],
            [
                'slug' => 'ramash-braided-bracelet',
                'name' => 'سوار رماش المجدول',
                'name_en' => 'Ramash Braided Bracelet',
                'description' => 'سوار رجالي أسود بتصميم مجدول وقفل بلون فضي.',
                'description_en' => 'A black braided men’s bracelet with a silver-tone clasp.',
            ],
        ];

        DB::transaction(function () use ($category, $products) {
            foreach ($products as $details) {
                $slug = $details['slug'];
                $image = $slug.'.jpg';
                $details['description'] .= ' الصورة تصوّر مبدئي للمنتج.';
                $details['description_en'] .= ' The image shows a product concept.';

                $product = Product::firstOrCreate(['slug' => $slug], $details + [
                    'category_id' => $category->id,
                    'material' => 'تُعتمد قبل الإطلاق',
                    'material_en' => 'To be confirmed before launch',
                    'price' => 0,
                    'image' => $image,
                    'gallery' => [$image],
                    'featured' => false,
                    'tailored' => false,
                    'active' => true,
                ]);

                $product->variants()->firstOrCreate(['size' => 'واحد'], ['stock' => 0]);
            }
        });
    }
}
