<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private const IMAGES = [
        'ivory-signature' => ['thobe.svg', 'thobe-ivory.jpg'],
        'sand-tailored' => ['thobe-sand.svg', 'thobe-sand.jpg'],
    ];

    public function up(): void {
        foreach (self::IMAGES as $slug => [$old, $new]) {
            DB::table('products')->where('slug', $slug)->where('image', $old)
                ->update(['image' => $new, 'gallery' => json_encode([$new])]);
        }
        DB::table('categories')->where('slug', 'thobes')->where('image', 'thobe.svg')
            ->update(['image' => 'thobe-detail.jpg']);
    }

    public function down(): void {
        foreach (self::IMAGES as $slug => [$old, $new]) {
            DB::table('products')->where('slug', $slug)->where('image', $new)
                ->update(['image' => $old, 'gallery' => json_encode([$old])]);
        }
        DB::table('categories')->where('slug', 'thobes')->where('image', 'thobe-detail.jpg')
            ->update(['image' => 'thobe.svg']);
    }
};
