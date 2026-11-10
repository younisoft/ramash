<?php
require __DIR__.'/../vendor/autoload.php';
$app=require __DIR__.'/../bootstrap/app.php';
$kernel=$app->make(Illuminate\Contracts\Http\Kernel::class);
$request=Illuminate\Http\Request::create('http://127.0.0.1:8000/');
$response=$kernel->handle($request);
if($response->getStatusCode()!==200){fwrite(STDERR,'Home failed');exit(1);}
$html=$response->getContent();
$html=preg_replace('~(src|href)="/(images|css|js)/~','$1="$2/',$html);
$html=str_replace(['href="/favicon.svg','href="/favicon-32.png','href="/apple-touch-icon.png'],['href="favicon.svg','href="favicon-32.png','href="apple-touch-icon.png'],$html);
file_put_contents(__DIR__.'/../public/preview.html',$html);
$kernel->terminate($request,$response);
echo "Static preview exported.\n";
