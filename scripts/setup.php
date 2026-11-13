<?php
chdir(dirname(__DIR__));
if (!is_file('.env')) copy('.env.example', '.env');
$env=file_get_contents('.env');
$connection=preg_match('/^DB_CONNECTION=(\w+)/m',$env,$m)?$m[1]:'sqlite';
if ($connection==='sqlite' && !is_file('database/database.sqlite')) touch('database/database.sqlite');
if ($connection!=='sqlite') foreach (['DB_DATABASE','DB_USERNAME'] as $key) {
    if (!preg_match('/^'.$key.'=\S+/m',$env)) { fwrite(STDERR,"Set {$key} in .env (create the database and user in cPanel > MySQL Databases first).\n"); exit(1); }
}
$commands=[];
if (!preg_match('/^APP_KEY=.+$/m',$env)) $commands[]='key:generate';
$commands[]='migrate --force';$commands[]='db:seed --force';
foreach($commands as $command){passthru(escapeshellarg(PHP_BINARY).' artisan '.$command,$status);if($status)exit($status);}
echo "Ready ({$connection}). Run: php artisan serve\n";
