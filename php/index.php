<?php

require_once 'vendor/autoload.php';
require_once 'request.php';

$url = 'https://gorest.co.in/public/v1/posts';
$parameters = ['data' => 'two'];

$response = SimpleJsonRequest::get($url, $parameters);

print_r($response);
