<?php
// App configuration
$dbParams = [
'driver' => 'pdo_mysql',
'host' =>'192.168.1.145',
'dbname' =>'speedtrade',
'user' => 'root',
'password' =>'123456',
'charset'=>'utf8'
];
// Dev mode?
$applicationMode ="development";
$logging = 0;
$mainCurrency = \Speed\Trade\Helper\BalanceConstant::CURRENCY_CNY;
