<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        $images = ['classic-red-shemagh'=>'shemagh-red.jpg','heritage-red-shemagh'=>'shemagh-gift.jpg','winter-red-shemagh'=>'shemagh-red.jpg','white-ghutra'=>'ghutra-white.jpg'];
        foreach ($images as $slug=>$image) {
            DB::table('products')->where('slug',$slug)->whereIn('image',['shemagh-red.svg','ghutra-white.svg'])->update(['image'=>$image,'gallery'=>json_encode([$image])]);
        }
        DB::table('categories')->where('slug','shemagh')->where('image','shemagh-red.svg')->update(['image'=>'shemagh-red.jpg']);
    }
    public function down(): void {
        foreach (['shemagh-red.jpg','shemagh-gift.jpg','ghutra-white.jpg'] as $image) {
            $old=$image==='ghutra-white.jpg'?'ghutra-white.svg':'shemagh-red.svg';
            DB::table('products')->whereIn('slug',['classic-red-shemagh','heritage-red-shemagh','winter-red-shemagh','white-ghutra'])->where('image',$image)->update(['image'=>$old,'gallery'=>json_encode([$old])]);
        }
        DB::table('categories')->where('slug','shemagh')->where('image','shemagh-red.jpg')->update(['image'=>'shemagh-red.svg']);
    }
};
