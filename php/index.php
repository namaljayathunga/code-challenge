<?php

require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'cache.php';
require_once 'request.php';

$url = 'https://gorest.co.in/public/v1/posts';
$parameters = ['data' => 'two'];

$response = SimpleJsonRequest::get($url, $parameters);

print_r(json_encode($response));
