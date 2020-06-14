<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;


$woocommerce = new Client(
    'https://roboss.uz/wpsite/',
    'ck_847cb2cd3a1909ebb57116e7801e6613e46ccb95',
    'cs_2f7f50fd032a0f6a5e14b023aa64dabadeed4375',
//    'https://ymo.pays.uz/',
//    'ck_a70f48ff9a7c25a9ce255b7187a6b29ca16d7945',
//    'cs_5f5ddc8e96a436273344dbffab05cde36bf111db',
    [
        'version' => 'wc/v3',
    ]
);