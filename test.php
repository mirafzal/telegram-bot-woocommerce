<?php
$time_start = microtime(true);
require_once 'woocommerce_connect.php';
//require __DIR__ . '/vendor/autoload.php';
//
//use Automattic\WooCommerce\Client;
//
//$woocommerce = new Client(
//    'https://ymo.pays.uz/',
//    'ck_a70f48ff9a7c25a9ce255b7187a6b29ca16d7945',
//    'cs_5f5ddc8e96a436273344dbffab05cde36bf111db',
//    [
//        'version' => 'wc/v3',
//    ]
//);
global $woocommerce;

$categories = $woocommerce->get('products/categories', []);

echo '<code><pre>'. print_r($categories, true). '</pre></code>';

//$data = file_get_contents('https://ymo.pays.uz/wp-json/wc/v3/categories?per_page=100&hide_empty=true&parent=0&consumer_key=ck_a70f48ff9a7c25a9ce255b7187a6b29ca16d7945&consumer_secret=cs_5f5ddc8e96a436273344dbffab05cde36bf111db');

echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);
