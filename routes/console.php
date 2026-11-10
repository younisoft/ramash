<?php
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
Artisan::command('store:admin', function () {
 $name=$this->ask('Admin name'); $email=$this->ask('Admin email'); $password=$this->secret('Password (12+ characters)');
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<12){$this->error('Valid email and 12+ character password required.');return 1;}
 if(User::where('email',$email)->exists()){$this->error('Email exists; no account was changed.');return 1;}
 $user=User::create(compact('name','email','password'));$user->is_admin=true;$user->save();$this->info('Admin created. Sign in at /login, then open /admin.');
})->purpose('Create an administrator without a default password');
