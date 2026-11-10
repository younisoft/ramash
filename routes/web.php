<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{StoreController,CartController,CheckoutController,AuthController,AdminController};
Route::get('/',[StoreController::class,'home'])->name('home');
Route::get('/shop',[StoreController::class,'catalog'])->name('shop');
Route::get('/category/{category}',[StoreController::class,'catalog'])->name('category');
Route::get('/products/{slug}',[StoreController::class,'product'])->name('product');
Route::post('/products/{product}/reviews',[StoreController::class,'review'])->middleware(['auth','throttle:5,1']);
Route::get('/favorites',[StoreController::class,'favorites']);
Route::post('/favorites/{product}',[StoreController::class,'favorite']);
Route::get('/pages/{page}',[StoreController::class,'page']);
Route::post('/contact',[StoreController::class,'contact'])->middleware('throttle:5,1');
Route::post('/locale',function(Request $r){$r->validate(['locale'=>'required|in:ar,en']);session(['locale'=>$r->locale]);return back();});
Route::get('/cart',[CartController::class,'index'])->name('cart');
Route::post('/cart',[CartController::class,'add']);
Route::patch('/cart/{key}',[CartController::class,'update']);
Route::delete('/cart/{key}',[CartController::class,'remove']);
Route::get('/checkout',[CheckoutController::class,'index']);
Route::post('/checkout',[CheckoutController::class,'store'])->middleware('throttle:10,1');
Route::get('/orders/{reference}',[CheckoutController::class,'show'])->name('order.show');
Route::middleware('guest')->group(function(){
 Route::get('/login',[AuthController::class,'login'])->name('login');Route::post('/login',[AuthController::class,'authenticate'])->middleware('throttle:5,1');
 Route::get('/register',[AuthController::class,'register']);Route::post('/register',[AuthController::class,'create'])->middleware('throttle:5,1');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth');
Route::get('/account',[AuthController::class,'account'])->middleware('auth');
Route::prefix('admin')->middleware(['auth','admin'])->group(function(){
 Route::get('/categories',[AdminController::class,'categories']);
 Route::patch('/categories/{category}/visibility',[AdminController::class,'categoryVisibility']);
 Route::get('/',[AdminController::class,'index']);Route::get('/products/create',[AdminController::class,'create']);Route::post('/products',[AdminController::class,'store']);Route::patch('/reviews/{review}',[AdminController::class,'review']);Route::get('/products/{product}',[AdminController::class,'edit']);Route::put('/products/{product}',[AdminController::class,'update']);Route::patch('/orders/{order}',[AdminController::class,'status']);
});
