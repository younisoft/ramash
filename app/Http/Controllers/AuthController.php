<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class AuthController {
 public function login(){return view('auth',['register'=>false]);}
 public function register(){return view('auth',['register'=>true]);}
 public function authenticate(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required|string']);if(!Auth::attempt($data,$r->boolean('remember')))throw ValidationException::withMessages(['email'=>__('البريد الإلكتروني أو كلمة المرور غير صحيحة')]);$r->session()->regenerate();return redirect()->intended('/account');}
 public function create(Request $r){$data=$r->validate(['name'=>'required|string|max:100','email'=>'required|email|max:190|unique:users,email','password'=>'required|string|min:10|confirmed','terms'=>'accepted']);Auth::login(User::create(collect($data)->only(['name','email','password'])->all()));$r->session()->regenerate();return redirect('/account');}
 public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect('/');}
 public function account(Request $r){return view('account',['orders'=>$r->user()->orders()->latest()->paginate(10)]);}
}
