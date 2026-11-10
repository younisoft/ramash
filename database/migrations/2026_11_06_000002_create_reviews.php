<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void{Schema::create('reviews',function(Blueprint $t){$t->id();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->unsignedTinyInteger('rating');$t->text('body');$t->boolean('approved')->default(false);$t->unique(['product_id','user_id']);$t->timestamps();});}
 public function down():void{Schema::dropIfExists('reviews');}
};
