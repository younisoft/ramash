<?php
chdir(dirname(__DIR__));
if (!is_file('.env')) copy('.env.example', '.env');
if (!is_file('database/database.sqlite')) touch('database/database.sqlite');
$env=file_get_contents('.env');
$commands=[];
if (!preg_match('/^APP_KEY=.+$/m',$env)) $commands[]='key:generate';
$commands[]='migrate --force';$commands[]='db:seed --force';
foreach($commands as $command){passthru(escapeshellarg(PHP_BINARY).' artisan '.$command,$status);if($status)exit($status);}
echo "Ready. Run: php artisan serve\n";
