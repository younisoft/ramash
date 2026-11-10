<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('users', function(Blueprint $t) { $t->id(); $t->string('name'); $t->string('email')->unique(); $t->string('password'); $t->boolean('is_admin')->default(false); $t->rememberToken(); $t->timestamps(); });
  Schema::create('categories', function(Blueprint $t) { $t->id(); $t->string('slug')->unique(); $t->string('name'); $t->string('name_en'); $t->string('image'); $t->integer('sort')->default(0); $t->timestamps(); });
  Schema::create('products', function(Blueprint $t) { $t->id(); $t->foreignId('category_id')->constrained(); $t->string('slug')->unique(); $t->string('name'); $t->string('name_en'); $t->text('description'); $t->text('description_en'); $t->string('material'); $t->string('material_en'); $t->unsignedInteger('price'); $t->unsignedInteger('compare_price')->nullable(); $t->string('image'); $t->json('gallery')->nullable(); $t->boolean('featured')->default(false); $t->boolean('tailored')->default(false); $t->boolean('active')->default(true); $t->timestamps(); });
  Schema::create('product_variants', function(Blueprint $t) { $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->string('size'); $t->unsignedInteger('stock')->default(0); $t->unique(['product_id','size']); $t->timestamps(); });
  Schema::create('orders', function(Blueprint $t) { $t->id(); $t->uuid('reference')->unique(); $t->uuid('checkout_token')->unique(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $t->string('name'); $t->string('email'); $t->string('phone'); $t->string('city'); $t->text('address'); $t->text('notes')->nullable(); $t->unsignedInteger('subtotal'); $t->unsignedInteger('shipping'); $t->unsignedInteger('total'); $t->string('status')->default('pending'); $t->string('payment_status')->default('unpaid'); $t->boolean('is_demo')->default(true); $t->timestamps(); });
  Schema::create('order_items', function(Blueprint $t) { $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_variant_id')->constrained(); $t->string('name'); $t->string('size'); $t->string('measurement')->nullable(); $t->unsignedInteger('quantity'); $t->unsignedInteger('price'); $t->timestamps(); });
  Schema::create('inquiries', function(Blueprint $t) { $t->id(); $t->string('name'); $t->string('email'); $t->text('message'); $t->timestamps(); });
 }
 public function down(): void { foreach(['inquiries','order_items','orders','product_variants','products','categories','users'] as $table) Schema::dropIfExists($table); }
};
